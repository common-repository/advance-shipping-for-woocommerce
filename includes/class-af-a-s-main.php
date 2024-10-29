<?php

defined( 'ABSPATH' ) || exit;

class AF_A_S_Main {

	public function __construct() {
		$this->include_files();

		add_action( 'init', array( $this, 'register_posts_type' ) );
		add_action( 'before_woocommerce_init', array( $this, 'af_a_s_HOPS_Compatibility' ) );
		add_action( 'after_setup_theme', array( $this, 'af_a_s_init' ) );
	}//end __construct()


	public function include_files() {

		include_once AFADS_PLUGIN_DIR . '/includes/afads-general-functions.php';
		include_once AFADS_PLUGIN_DIR . '/includes/class-af-a-s-ajax.php';

		if ( is_admin() ) {
			include_once AFADS_PLUGIN_DIR . 'includes/class-af-a-s-admin.php';
		} else {
			include_once AFADS_PLUGIN_DIR . 'includes/class-af-a-s-front.php';
		}
	}//end include_files()


	public function register_posts_type() {

		$labels = array(
			'name'               => esc_html__( 'Shipping Rules', 'advanced-shipping' ),
			'singular_name'      => esc_html__( 'Shipping Rule', 'advanced-shipping' ),
			'add_new'            => esc_html__( 'Add New Rule', 'advanced-shipping' ),
			'add_new_item'       => esc_html__( 'Add New Rule', 'advanced-shipping' ),
			'edit_item'          => esc_html__( 'Edit Rule', 'advanced-shipping' ),
			'new_item'           => esc_html__( 'New Rule', 'advanced-shipping' ),
			'view_item'          => esc_html__( 'View Rule', 'advanced-shipping' ),
			'search_items'       => esc_html__( 'Search Rule', 'advanced-shipping' ),
			'not_found'          => esc_html__( 'No rule found', 'advanced-shipping' ),
			'not_found_in_trash' => esc_html__( 'No rule found in trash', 'advanced-shipping' ),
			'parent_item_colon'  => '',
			'all_items'          => esc_html__( 'Advanced Shipping', 'advanced-shipping' ),
			'menu_name'          => esc_html__( 'Shipping Rules', 'advanced-shipping' ),
			'attributes'         => esc_html__( 'Rule Priority', 'advanced-shipping' ),
			'item_published'     => esc_html__( 'Shipping rule published', 'advanced-shipping' ),
			'item_updated'       => esc_html__( 'Shipping rule updated', 'advanced-shipping' ),
		);

		$args = array(
			'labels'              => $labels,
			'menu_icon'           => plugin_dir_url( __FILE__ ) . 'assets/images/small_logo_white.png',
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => 'woocommerce',
			'query_var'           => true,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => 30,
			'rewrite'             => array(
				'slug'       => 'af_ship_rule',
				'with_front' => false,
			),
			'supports'            => array( 'title' ),
		);

		register_post_type( 'af_ship_rule', $args );

		$labels = array(
			'name'               => esc_html__( 'Shipping Rules', 'advanced-shipping' ),
			'singular_name'      => esc_html__( 'Shipping Rule', 'advanced-shipping' ),
			'add_new'            => esc_html__( 'Add New Rule', 'advanced-shipping' ),
			'add_new_item'       => esc_html__( 'Add New Rule', 'advanced-shipping' ),
			'edit_item'          => esc_html__( 'Edit Rule', 'advanced-shipping' ),
			'new_item'           => esc_html__( 'New Rule', 'advanced-shipping' ),
			'view_item'          => esc_html__( 'View Rule', 'advanced-shipping' ),
			'search_items'       => esc_html__( 'Search Rule', 'advanced-shipping' ),
			'not_found'          => esc_html__( 'No rule found', 'advanced-shipping' ),
			'not_found_in_trash' => esc_html__( 'No rule found in trash', 'advanced-shipping' ),
			'parent_item_colon'  => '',
			'all_items'          => esc_html__( 'Advanced Shipping', 'advanced-shipping' ),
			'menu_name'          => esc_html__( 'Shipping Rules', 'advanced-shipping' ),
			'attributes'         => esc_html__( 'Rule Priority', 'advanced-shipping' ),
			'item_published'     => esc_html__( 'Shipping rule published', 'advanced-shipping' ),
			'item_updated'       => esc_html__( 'Shipping rule updated', 'advanced-shipping' ),
		);

		$args = array(
			'labels'              => $labels,
			'menu_icon'           => plugin_dir_url( __FILE__ ) . 'assets/images/small_logo_white.png',
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'query_var'           => true,
			'exclude_from_search' => true,
			'capability_type'     => 'post',
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => 30,
			'rewrite'             => array(
				'slug'       => 'af_ship_rule_alt',
				'with_front' => false,
			),
			'supports'            => array( 'title' ),
		);

		register_post_type( 'af_ship_rule_alt', $args );
	}//end register_posts_type()


	public function af_a_s_HOPS_Compatibility() {

		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
	public function af_a_s_init() {

		if ( function_exists( 'load_plugin_textdomain' ) ) {

			load_plugin_textdomain( 'advanced-shipping', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}
	}
}//end class


new AF_A_S_Main();
