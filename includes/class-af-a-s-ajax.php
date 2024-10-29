<?php

defined( 'ABSPATH' ) || exit;

class AF_A_S_Ajax {
	public function __construct() {
		// Product search.
		add_action( 'wp_ajax_afads_search_products', array( $this, 'search_products' ) );
		// Product Categories search.
		add_action( 'wp_ajax_afads_search_product_categories', array( $this, 'search_product_categories' ) );
		// Product Categories search.
		add_action( 'wp_ajax_afads_search_product_tags', array( $this, 'search_product_tags' ) );
		// Users search.
		add_action( 'wp_ajax_afads_search_users', array( $this, 'search_users' ) );
	}//end __construct()


	public function search_users() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'afads-ajax-nonce' ) ) {
			die( esc_html__( 'Failed Ajax security check!', 'advanced-shipping' ) );
		}

		$s = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$users = new WP_User_Query(
			array(
				'search'         => '*' . esc_html( $s ) . '*',
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'user_url',
				),
				'orderby'        => 'relevance',
				'order'          => 'ASC',
			)
		);

		$users_found = $users->get_results();
		$data_array  = array();

		if ( ! empty( $users_found ) ) {
			foreach ( $users_found as $user ) {
				$title        = $user->display_name . '(' . $user->user_email . ')';
				$data_array[] = array( $user->ID, $title ); // array( User ID, User name and email ).
			}
		}

		wp_send_json( $data_array );
		die();
	}//end search_users()


	public function search_products() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'afads-ajax-nonce' ) ) {
			die( esc_html__( 'Failed Ajax security check!', 'advanced-shipping' ) );
		}

		$s = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$args = array(
			'post_type'   => array( 'product' ),
			'post_status' => 'publish',
			'numberposts' => 50,
			's'           => $s,
			'orderby'     => 'relevance',
			'order'       => 'ASC',
		);

		$products   = get_posts( $args );
		$data_array = array();

		if ( ! empty( $products ) ) {

			foreach ( $products as $product ) {

				$title        = ( mb_strlen( $product->post_title ) > 50 ) ? mb_substr( $product->post_title, 0, 49 ) . '...' : $product->post_title;
				$data_array[] = array( $product->ID, $title ); // array( Post ID, Post Title )
			}
		}

		wp_send_json( $data_array );
		die();
	}//end search_products()


	public function search_product_categories() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'afads-ajax-nonce' ) ) {
			die( esc_html__( 'Failed Ajax security check!', 'advanced-shipping' ) );
		}

		$s = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$args = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'name__like' => $s,
			'orderby'    => 'relevance',
			'order'      => 'ASC',
		);

		$categories = get_terms( $args );
		$data_array = array();

		if ( ! empty( $categories ) ) {

			foreach ( $categories as $category ) {

				$title        = ( mb_strlen( $category->name ) > 50 ) ? mb_substr( $category->name, 0, 49 ) . '...' : $category->name;
				$data_array[] = array( $category->slug, $title ); // array( Post ID, Post Title )
			}
		}

		wp_send_json( $data_array );
		die();
	}//end search_product_categories()


	public function search_product_tags() {

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'afads-ajax-nonce' ) ) {
			die( esc_html__( 'Failed Ajax security check!', 'advanced-shipping' ) );
		}

		$s = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		$args = array(
			'taxonomy'   => 'product_tag',
			'hide_empty' => false,
			'name__like' => $s,
			'orderby'    => 'relevance',
			'order'      => 'ASC',
		);

		$categories = get_terms( $args );
		$data_array = array();

		if ( ! empty( $categories ) ) {

			foreach ( $categories as $category ) {

				$title        = ( mb_strlen( $category->name ) > 50 ) ? mb_substr( $category->name, 0, 49 ) . '...' : $category->name;
				$data_array[] = array( $category->slug, $title ); // array( Post ID, Post Title )
			}
		}

		wp_send_json( $data_array );
		die();
	}//end search_product_tags()
}//end class


new AF_A_S_Ajax();
