<?php

defined( 'ABSPATH' ) || exit;

$shipping_classes = get_terms(
	array(
		'taxonomy'   => 'product_shipping_class',
		'hide_empty' => false,
	)
);

?>
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
		<span class="dashicons dashicons-no-alt"></span>
	</td>
</tr>
