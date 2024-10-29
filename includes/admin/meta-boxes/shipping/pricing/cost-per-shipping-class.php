<?php

defined( 'ABSPATH' ) || exit;

$afads_shipping_class_price_rule = get_post_meta( $current_post_id, 'afads_shipping_class_price_rule', true );

if ( empty( $afads_shipping_class_price_rule ) ) {
	$total_shipping_class_rules = 0;
} else {
	$total_shipping_class_rules = is_array( $afads_shipping_class_price_rule ) ? count( current( $afads_shipping_class_price_rule ) ) : 0;
}

$shipping_classes = get_terms(
	array(
		'taxonomy'   => 'product_shipping_class',
		'hide_empty' => false,
	)
);

?>
<table class="afads-pricing-table">
	<tr>
		<th>
			<label><?php esc_html_e( 'Shipping Class', 'advanced-shipping' ); ?></label>
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
	<?php if ( ! empty( $total_shipping_class_rules ) ) : ?>
		<?php
		for ( $i = 0; $i < $total_shipping_class_rules; $i++ ) :

			$shipping_class = isset( $afads_shipping_class_price_rule['shipping_class'][ $i ] ) ? $afads_shipping_class_price_rule['shipping_class'][ $i ] : '';
			$min_quantity   = isset( $afads_shipping_class_price_rule['min_quantity'][ $i ] ) ? $afads_shipping_class_price_rule['min_quantity'][ $i ] : '';
			$max_quantity   = isset( $afads_shipping_class_price_rule['max_quantity'][ $i ] ) ? $afads_shipping_class_price_rule['max_quantity'][ $i ] : '';
			$fee            = isset( $afads_shipping_class_price_rule['fee'][ $i ] ) ? $afads_shipping_class_price_rule['fee'][ $i ] : '';

			?>
			<tr>
				<td>
					<select class="afads_pricing_select" name="afads_shipping_class_price_rule[shipping_class][]">
						<option value=""><?php esc_html_e( 'Choose your shipping class', 'advanced-shipping' ); ?></option>
						<?php foreach ( $shipping_classes as $class ) : ?>
							<option value="<?php echo esc_attr( $class->slug ); ?>" <?php echo selected( $shipping_class, $class->slug ); ?>><?php echo esc_html( $class->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td>
					<input name="afads_shipping_class_price_rule[min_quantity][]" type="number" min="1" value="<?php echo esc_attr( $min_quantity ); ?>">
				</td>
				<td>
					<input name="afads_shipping_class_price_rule[max_quantity][]" type="number" min="1" value="<?php echo esc_attr( $max_quantity ); ?>">
				</td>
				<td>
					<input name="afads_shipping_class_price_rule[fee][]" type="number" min="1" value="<?php echo esc_attr( $fee ); ?>">
				</td>
				<td>
					<span title="Remove" class="dashicons dashicons-no-alt"></span>
				</td>
			</tr>
		<?php endfor; ?>
	<?php endif; ?>
	<?php if ( empty( $total_shipping_class_rules ) ) : ?>
	<tr>
		<td>
			<select class="afads_pricing_select" name="afads_shipping_class_price_rule[shipping_class][]">
				<option value=""><?php esc_html_e( 'Choose your shipping class', 'advanced-shipping' ); ?></option>
				<?php foreach ( $shipping_classes as $class ) : ?>
					<option value="<?php echo esc_attr( $class->slug ); ?>"><?php echo esc_html( $class->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
		<td>
			
			<input name="afads_shipping_class_price_rule[min_quantity][]" type="number" min="1" value="">
		</td>
		<td>
			
			<input name="afads_shipping_class_price_rule[max_quantity][]" type="number" min="1" value="">
		</td>
		<td>
			
			<input name="afads_shipping_class_price_rule[fee][]" type="number" min="1" value="">
		</td>
		<td>
		</td>
	</tr>
	<?php endif; ?>
</table>
<button type="button" class="button button-primary button-large afads_add_shipping_class_price_rule"><?php esc_html_e( 'Add new', 'advanced-shipping' ); ?></button>
