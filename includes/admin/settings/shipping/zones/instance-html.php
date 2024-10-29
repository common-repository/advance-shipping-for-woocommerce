<?php

defined( 'ABSPATH' ) || exit;


$nonce = isset( $_GET['afads_ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['afads_ajax_nonce'] ) ) : 0;
if (isset( $_GET['afads_ajax_nonce'] )  && ! wp_verify_nonce( $nonce, 'afads_ajax_nonce' ) ) {
	wp_die( esc_html__( 'Security Violated!', 'advanced-shipping' ) );
}

$instance_id = isset( $_GET['instance_id'] ) ? intval( sanitize_text_field(wp_unslash ( $_GET['instance_id'] ) ) ) : 0;

?>

<div class="afads-advanced-shipping">
	<?php require_once AFADS_PLUGIN_DIR . 'includes/admin/settings/shipping/zones/instance-settings.php'; ?>
	<?php require_once AFADS_PLUGIN_DIR . 'includes/admin/settings/shipping/zones/instance-pricing.php'; ?>
	<?php require_once AFADS_PLUGIN_DIR . 'includes/admin/settings/shipping/zones/instance-conditions.php'; ?>
</div>
