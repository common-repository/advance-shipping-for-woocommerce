<?php

defined( 'ABSPATH' ) || exit;

$afads_weight_price_rule = get_post_meta( $current_post_id, 'afads_weight_price_rule', true );

if ( empty( $afads_weight_price_rule ) ) {
	$total_weight_rules = 0;
} else {
	$total_weight_rules = is_array( $afads_weight_price_rule ) ? count( current( $afads_weight_price_rule ) ) : 0;
}

?>
<table class="afads-pricing-table">
	<tr>
		<th>
			<label><?php esc_html_e( 'Min Weight', 'advanced-shipping' ); ?></label>
		</th>
		<th>
			<label><?php esc_html_e( 'Max Weight', 'advanced-shipping' ); ?></label>
		</th>
		<th>
			<label><?php esc_html_e( 'Fee Amount', 'advanced-shipping' ); ?></label>
		</th>
		<th></th>
	</tr>
	<?php if ( ! empty( $total_weight_rules ) ) : ?>
		<?php
		for ( $i = 0; $i < $total_weight_rules; $i++ ) :

			$min_weight = isset( $afads_weight_price_rule['min_weight'][ $i ] ) ? $afads_weight_price_rule['min_weight'][ $i ] : '';
			$max_weight = isset( $afads_weight_price_rule['max_weight'][ $i ] ) ? $afads_weight_price_rule['max_weight'][ $i ] : '';
			$fee        = isset( $afads_weight_price_rule['fee'][ $i ] ) ? $afads_weight_price_rule['fee'][ $i ] : '';
			?>
			<tr>
				<td>
					<input name="afads_weight_price_rule[min_weight][]" type="number" min="1" value="<?php echo esc_attr( $min_weight ); ?>">
				</td>
				<td>
					<input type="number" min="1" name="afads_weight_price_rule[max_weight][]" value="<?php echo esc_attr( $max_weight ); ?>">
				</td>
				<td>
					<input type="number" min="1" name="afads_weight_price_rule[fee][]" value="<?php echo esc_attr( $fee ); ?>">
				</td>
				<td>
					<span title="Remove" class="dashicons dashicons-no-alt"></span>
				</td>
			</tr>
		<?php endfor; ?>
	<?php endif; ?>
	<?php if ( empty( $total_weight_rules ) ) : ?>
	<tr>
		<td>
			<input class="afads_pricing_select" name="afads_weight_price_rule[min_weight][]" type="number" min="1" value="">
		</td>
		<td>
			<input type="number" min="1" name="afads_weight_price_rule[max_weight][]" value="">
		</td>
		<td>
			<input type="number" min="1" name="afads_weight_price_rule[fee][]" value="">
		</td>
		<td>
		</td>
	</tr>
	<?php endif; ?>
</table>
<button type="button" class="button button-primary button-large afads_add_weight_price_rule"><?php esc_html_e( 'Add new', 'advanced-shipping' ); ?></button>
