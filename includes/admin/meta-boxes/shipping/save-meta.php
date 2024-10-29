<?php

defined( 'ABSPATH' ) || exit;

$afads_shipping_title = isset( $_POST['afads_shipping_title'] ) ? sanitize_text_field( wp_unslash( $_POST['afads_shipping_title'] ) ) : '';

if ( isset( $_POST['afads_cost_per_item'] ) ) {

	$nonce = isset( $_POST['afads_ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['afads_ajax_nonce'] ) ) : 0;


	if ( ! wp_verify_nonce( $nonce, 'afads_ajax_nonce' ) ) {

		wp_die( esc_html__( 'Security Violated!', 'advanced-shipping' ) );

	}
}

update_post_meta( $post_id, 'afads_shipping_title', $afads_shipping_title );

$afads_shipping_cost = isset( $_POST['afads_shipping_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['afads_shipping_cost'] ) ) : '';

update_post_meta( $post_id, 'afads_shipping_cost', $afads_shipping_cost );

$afads_shipping_fee = isset( $_POST['afads_shipping_fee'] ) ? sanitize_text_field( wp_unslash( $_POST['afads_shipping_fee'] ) ) : '';

update_post_meta( $post_id, 'afads_shipping_fee', $afads_shipping_fee );

$afads_cost_per_item = isset( $_POST['afads_cost_per_item'] ) ? sanitize_text_field( wp_unslash( $_POST['afads_cost_per_item'] ) ) : '';

update_post_meta( $post_id, 'afads_cost_per_item', $afads_cost_per_item );

$afads_cost_per_weight = isset( $_POST['afads_cost_per_weight'] ) ? sanitize_text_field( wp_unslash( $_POST['afads_cost_per_weight'] ) ) : '';

update_post_meta( $post_id, 'afads_cost_per_weight', $afads_cost_per_weight );

$afads_tax_status = isset( $_POST['afads_tax_status'] ) ? sanitize_text_field( wp_unslash( $_POST['afads_tax_status'] ) ) : '';

update_post_meta( $post_id, 'afads_tax_status', $afads_tax_status );


$afads_weight_price_rule = isset( $_POST['afads_weight_price_rule'] ) ? sanitize_meta( '', wp_unslash( $_POST['afads_weight_price_rule'] ), '' ) : array();

update_post_meta( $post_id, 'afads_weight_price_rule', $afads_weight_price_rule );

$afads_shipping_class_price_rule = isset( $_POST['afads_shipping_class_price_rule'] ) ? sanitize_meta( '', wp_unslash( $_POST['afads_shipping_class_price_rule'] ), '' ) : array();

update_post_meta( $post_id, 'afads_shipping_class_price_rule', $afads_shipping_class_price_rule );

$afads_product_price_rule = isset( $_POST['afads_product_price_rule'] ) ? sanitize_meta( '', wp_unslash( $_POST['afads_product_price_rule'] ), '' ) : array();

update_post_meta( $post_id, 'afads_product_price_rule', $afads_product_price_rule );

$afads_category_price_rule = isset( $_POST['afads_category_price_rule'] ) ? sanitize_meta( '', wp_unslash( $_POST['afads_category_price_rule'] ), '' ) : array();

update_post_meta( $post_id, 'afads_category_price_rule', $afads_category_price_rule );

$afads_condition_type = isset( $_POST['afads_condition_type'] ) ? sanitize_meta( '', wp_unslash( $_POST['afads_condition_type'] ), '' ) : array();

update_post_meta( $post_id, 'afads_condition_type', $afads_condition_type );

$afads_condition_operator = isset( $_POST['afads_condition_operator'] ) ? sanitize_meta( '', wp_unslash( $_POST['afads_condition_operator'] ), '' ) : array();

update_post_meta( $post_id, 'afads_condition_operator', $afads_condition_operator );

$afads_condition_value = isset( $_POST['afads_condition_value'] ) ? sanitize_meta( '', wp_unslash( $_POST['afads_condition_value'] ), '' ) : array();

update_post_meta( $post_id, 'afads_condition_value', $afads_condition_value );
