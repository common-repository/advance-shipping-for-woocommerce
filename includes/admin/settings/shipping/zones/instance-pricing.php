<?php

defined( 'ABSPATH' ) || exit;

$current_post_id = afads_get_instance_post_id( $instance_id ) ? afads_get_instance_post_id( $instance_id ) : 0;

$afads_shipping_title  = get_post_meta( $current_post_id, 'afads_shipping_title', true );
$afads_shipping_cost   = get_post_meta( $current_post_id, 'afads_shipping_cost', true );
$afads_shipping_fee    = get_post_meta( $current_post_id, 'afads_shipping_fee', true );
$afads_cost_per_item   = get_post_meta( $current_post_id, 'afads_cost_per_item', true );
$afads_cost_per_weight = get_post_meta( $current_post_id, 'afads_cost_per_weight', true );
$afads_tax_status      = get_post_meta( $current_post_id, 'afads_tax_status', true );

?>
<div class="afads-metabox-fields">

  <div id="afads-tabs" class="afads-tabs">
	<ul class="afads-tabs-ul">
	  <li><a href="#afads-tab-pricing-weight"><?php esc_html_e( 'Cost per weight', 'advanced-shipping' ); ?></a></li>
	  <li><a href="#afads-tab-pricing-product"><?php esc_html_e( 'Cost per product', 'advanced-shipping' ); ?></a></li>
	  <li><a href="#afads-tab-pricing-shipping-class"><?php esc_html_e( 'Cost per shipping class', 'advanced-shipping' ); ?></a></li>
	  <li><a href="#afads-tab-pricing-category"><?php esc_html_e( 'Cost per category', 'advanced-shipping' ); ?></a></li>
	</ul>
	<div class="afads-tabs-content" id="afads-tab-pricing-weight">
	  <?php require_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/cost-per-weight.php'; ?>
	</div>
	<div class="afads-tabs-content"  id="afads-tab-pricing-shipping-class">
	  <?php require_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/cost-per-shipping-class.php'; ?>
	</div>
	<div class="afads-tabs-content" id="afads-tab-pricing-category">
	  <?php require_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/cost-per-category.php'; ?>
	</div>
	<div class="afads-tabs-content" id="afads-tab-pricing-product">
	  <?php require_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/cost-per-product.php'; ?>
	</div>
  </div>
</div>
