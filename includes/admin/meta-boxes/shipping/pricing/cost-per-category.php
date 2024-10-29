<?php

defined( 'ABSPATH' ) || exit;
$afads_category_price_rule = (array) get_post_meta( $current_post_id, 'afads_category_price_rule', true );

?>
<table class="afads-pricing-table">
	<tr>
		<th>
			<label><?php esc_html_e( 'Category', 'advanced-shipping' ); ?></label>
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

	<?php
	if ( count( $afads_category_price_rule ) >= 1 ) {

		for ( $i = 0; $i < count( $afads_category_price_rule ); $i++ ) :

			$category     = isset( $afads_category_price_rule['category'][ $i ] ) ? $afads_category_price_rule['category'][ $i ] : '';
			$min_qunatity = isset( $afads_category_price_rule['min_quantity'][ $i ] ) ? $afads_category_price_rule['min_quantity'][ $i ] : '';
			$max_quantity = isset( $afads_category_price_rule['max_quantity'][ $i ] ) ? $afads_category_price_rule['max_quantity'][ $i ] : '';
			$fee          = isset( $afads_category_price_rule['fee'][ $i ] ) ? $afads_category_price_rule['fee'][ $i ] : '';
			$get_term     = get_term_by( 'slug', $category, 'product_cat' );

			if ( ! is_a( $get_term, 'WP_Term' ) ) {
				continue;
			}

			?>
			<tr>
				<td>
					<select class="afads_pricing_select afads_category_search" name="afads_category_price_rule[category][]" style="width: 180px;">
						<option value="<?php echo esc_attr( $get_term->slug ); ?>" selected>
							<?php echo esc_html( $get_term->name ); ?>
						</option>
					</select>
				</td>
				<td>
					<input name="afads_category_price_rule[min_quantity][]" type="number" min="1" value="<?php echo intval( $min_qunatity ); ?>">
				</td>
				<td>
					<input name="afads_category_price_rule[max_quantity][]" type="number" min="1" value="<?php echo intval( $max_quantity ); ?>">
				</td>
				<td>
					<input name="afads_category_price_rule[fee][]" type="number" min="1" value="<?php echo floatval( $fee ); ?>">
				</td>
				<td>
					<span title="Remove" class="dashicons dashicons-no-alt"></span>
				</td>
			</tr>
		<?php endfor; ?>
	<?php } else { ?>
		<tr>
			<td>
				<select class="afads_pricing_select afads_category_search" name="afads_category_price_rule[category][]" style="width: 180px;"></select>
			</td>
			<td>
				<input name="afads_category_price_rule[min_quantity][]" type="number" min="1" value="">
			</td>
			<td>
				<input name="afads_category_price_rule[max_quantity][]" type="number" min="1" value="">
			</td>
			<td>
				<input name="afads_category_price_rule[fee][]" type="number" min="1" value="">
			</td>
			<td>
			</td>
		</tr>
	<?php } ?>
</table>
<button type="button" class="button button-primary button-large afads_add_category_price_rule"><?php esc_html_e( 'Add new', 'advanced-shipping' ); ?></button>
