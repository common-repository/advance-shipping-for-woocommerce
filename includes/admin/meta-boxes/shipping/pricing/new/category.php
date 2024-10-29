<?php

defined( 'ABSPATH' ) || exit;

?>
<tr>
	<td>
		<select class="afads_pricing_select afads_category_search" name="afads_category_price_rule[category][]" style="width: 180px;"></select>
	</td>
	<td>
		<input name="afads_category_price_rule[min_quantity][]" type="number" min="1" value="<?php echo intval( $min_qunatity ); ?>">
	</td>
	<td>
		<input name="afads_category_price_rule[max_quantity][]" type="number" min="1" value="<?php echo intval( $max_qunatity ); ?>">
	</td>
	<td>
		<input name="afads_category_price_rule[fee][]" type="number" min="1" value="<?php echo floatval( $fee ); ?>">
	</td>
	<td>
		<span class="dashicons dashicons-no-alt"></span>
	</td>
</tr>
