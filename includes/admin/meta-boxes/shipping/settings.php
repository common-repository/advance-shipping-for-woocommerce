<?php

defined( 'ABSPATH' ) || exit;

global $post;

$afads_shipping_title  = get_post_meta( $post->ID, 'afads_shipping_title', true );
$afads_shipping_cost   = get_post_meta( $post->ID, 'afads_shipping_cost', true );
$afads_shipping_fee    = get_post_meta( $post->ID, 'afads_shipping_fee', true );
$afads_cost_per_item   = get_post_meta( $post->ID, 'afads_cost_per_item', true );
$afads_cost_per_weight = get_post_meta( $post->ID, 'afads_cost_per_weight', true );
$afads_tax_status      = get_post_meta( $post->ID, 'afads_tax_status', true );
wp_nonce_field( 'afads_ajax_nonce', 'afads_ajax_nonce' );
?>
<div class="afads-metabox-fields">
	<table class="cloudtech-table-option">
		<tr class="cloudtech-option-field">
			<th>
				<?php echo esc_html__( 'Shipping Title', 'advanced-shipping' ); ?>
			</th>
			<td>
				<input type="text" name="afads_shipping_title" value="<?php echo esc_attr( $afads_shipping_title ); ?>"  >
				<?php echo wp_kses_post( wc_help_tip( 'Add shipping title.', 'advanced-shipping' ) ); ?>
			</td>
		</tr>
		<tr class="cloudtech-option-field">
			<th>
				<?php echo esc_html__( 'Shipping Cost', 'advanced-shipping' ); ?>
			</th>
			<td>
				<input type="number" min="1" name="afads_shipping_cost" value="<?php echo esc_attr( $afads_shipping_cost ); ?>"  >
				<span class="dashicons dashicons-info" title="<?php echo esc_html__( 'Cost of Shipping.', 'advanced-shipping' ); ?>"></span>
			</td>
		</tr>
		<tr class="cloudtech-option-field">
			<th>
				<?php echo esc_html__( 'Additional Fee', 'advanced-shipping' ); ?>
			</th>
			<td>
				<input type="number" min="1" name="afads_shipping_fee" value="<?php echo esc_attr( $afads_shipping_fee ); ?>"  >
				<span class="dashicons dashicons-info" title="<?php echo esc_html__( 'Additional fee for Shipping.', 'advanced-shipping' ); ?>"></span>
			</td>
		</tr>

		<tr class="cloudtech-option-field">
			<th>
				<?php echo esc_html__( 'Cost Per Item', 'advanced-shipping' ); ?>
			</th>
			<td>
				<input type="number" min="1" name="afads_cost_per_item" value="<?php echo esc_attr( $afads_cost_per_item ); ?>" >
				<span class="dashicons dashicons-info" title="<?php echo esc_html__( 'Per item cost for shipping.', 'advanced-shipping' ); ?>"></span>
			</td>
		</tr>
		<tr class="cloudtech-option-field">
			<th>
				<?php echo esc_html__( 'Cost Per Wight', 'advanced-shipping' ); ?>
			</th>
			<td>
				<input type="number" min="1" name="afads_cost_per_weight" value="<?php echo esc_attr( $afads_cost_per_weight ); ?>" >
				<span class="dashicons dashicons-info" title="<?php echo esc_html__( 'per kg cost for shipping.', 'advanced-shipping' ); ?>"></span>
			</td>
		</tr>
		<tr class="cloudtech-option-field">
			<th>
				<?php echo esc_html__( 'Tax Status', 'advanced-shipping' ); ?>
			</th>
			<td>
				<select name="afads_tax_status">
					<option value="taxable" <?php echo selected( 'taxable', $afads_tax_status ); ?>><?php esc_html_e( 'Taxable', 'advanced-shipping' ); ?></option>
					<option value="none" <?php echo selected( 'none', $afads_tax_status ); ?>><?php esc_html_e( 'None', 'advanced-shipping' ); ?></option>
				</select>
				<span class="dashicons dashicons-info" title="<?php echo esc_html__( 'Tax Status for Shipping cost.', 'advanced-shipping' ); ?>"></span>
			</td>
		</tr>
	</table>
</div>
