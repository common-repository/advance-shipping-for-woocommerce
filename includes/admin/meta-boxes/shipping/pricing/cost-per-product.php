<?php

defined( 'ABSPATH' ) || exit;

$afads_product_price_rule = get_post_meta( $current_post_id, 'afads_product_price_rule', true );

if ( empty( $afads_product_price_rule ) ) {
	$total_product_rules = 0;
} else {
	$total_product_rules = is_array( $afads_product_price_rule ) ? count( current( $afads_product_price_rule ) ) : 0;
}

?>
<table class="afads-pricing-table">
	<tr>
		<th>
			<label><?php esc_html_e( 'Product', 'advanced-shipping' ); ?></label>
		</th>
		<th>
			<label><?php esc_html_e( 'Min Quantity', 'advanced-shipping' ); ?></label>
		</th>
		<th>
			<label><?php esc_html_e( 'Max Quantity', 'advanced-shipping' ); ?></label>
		</th>
		<th>
			<label><?php esc_html_e( 'Fee Amount', 'advanced-shipping' ); ?></label>
		</th>
		<th></th>
	</tr>
	<?php if ( ! empty( $total_product_rules ) ) : ?>
		<?php
		for ( $i = 0; $i < $total_product_rules; $i++ ) :

			$product      = isset( $afads_product_price_rule['product'][ $i ] ) ? $afads_product_price_rule['product'][ $i ] : '';
			$min_quantity = isset( $afads_product_price_rule['min_quantity'][ $i ] ) ? $afads_product_price_rule['min_quantity'][ $i ] : '';
			$max_quantity = isset( $afads_product_price_rule['max_quantity'][ $i ] ) ? $afads_product_price_rule['max_quantity'][ $i ] : '';
			$fee          = isset( $afads_product_price_rule['fee'][ $i ] ) ? $afads_product_price_rule['fee'][ $i ] : '';
			?>
			<tr>
				<td>
					<select class="afads_pricing_select afads_product_search" name="afads_product_price_rule[product][]" style="width: 180px;">

						<?php if ( ! empty( $product ) && 0 != $product ) { ?>

							<option value="<?php echo intval( $product ); ?>" selected>
								<?php echo esc_html( get_the_title( $product ) ); ?>
							</option>

						<?php } ?>

					</select>
				</td>
				<td>
					<input name="afads_product_price_rule[min_quantity][]"  type="number" min="1"  value="<?php echo intval( $min_quantity ); ?>">
				</td>
				<td>
					<input name="afads_product_price_rule[max_quantity][]"  type="number" min="1"  value="<?php echo intval( $max_quantity ); ?>">
				</td>
				<td>
					<input name="afads_product_price_rule[fee][]"  type="number" min="1"  value="<?php echo floatval( $fee ); ?>">
				</td>
				<td>
					<span title="Remove" class="dashicons dashicons-no-alt"></span>
				</td>
			</tr>
		<?php endfor; ?>
	<?php endif; ?>

	<?php if ( empty( $total_product_rules ) ) : ?>
		<tr>
			<td>
				<select class="afads_pricing_select afads_product_search" name="afads_product_price_rule[product][]" style="width: 180px;"></select>
			</td>
			<td>
				<input name="afads_product_price_rule[min_quantity][]" type="number" min="1"  value="">
			</td>
			<td>
				<input name="afads_product_price_rule[max_quantity][]" type="number" min="1"  value="">
			</td>
			<td>
				<input name="afads_product_price_rule[fee][]" type="number" min="1"  value="">
			</td>
			<td>
			</td>
		</tr>
	<?php endif; ?>
</table>
<button type="button" class="button button-primary button-large afads_add_product_price_rule"><?php esc_html_e( 'Add new', 'advanced-shipping' ); ?></button>
