<?php

defined( 'ABSPATH' ) || exit;
?>
<div class="afads-condition-wrap">
  <div class="afads-condition-type">
	<select class="input-select afads-condition-type-select" data-condition_id="{{condition_id}}" data-group_id="{{group_id}}" name="afads_condition_type[{{group_id}}][{{condition_id}}]">
	  <?php foreach ( afads_get_condition_types() as $group_val => $group ) : ?>
		<optgroup label="<?php echo esc_html( afads_get_group_label( $group_val ) ); ?>">
			<?php foreach ( $group as $type_val => $type_label ) : ?>
			<option value="<?php echo esc_attr( $type_val ); ?>"><?php echo esc_html( $type_label ); ?></option>
		  <?php endforeach ?>
		</optgroup>
	  <?php endforeach ?>
	</select>
  </div>
  <div class="afads-condition-operator">
	<select class="input-select afads-condition-operator-select" name="afads_condition_operator[{{group_id}}][{{condition_id}}]">
	  <?php foreach ( afads_get_operators() as $operator_val => $operator_label ) : ?>
		<option value="<?php echo esc_attr( $operator_val ); ?>"><?php echo esc_html( $operator_label ); ?></option>
	  <?php endforeach ?>
	</select>
  </div>
  <div class="afads-condition-value">
	<input type="number" min="1" name="afads_condition_value[{{group_id}}][{{condition_id}}]" class="input-text afads-condition-value-input"/>
  </div>
  <div class="afads-condition-remove">
	<span title="<?php esc_html_e( 'Remove Condition', 'advanced-shipping' ); ?>" class="dashicons dashicons-no-alt"></span>
  </div>
  <fieldset class="and"><legend>&</legend></fieldset>
</div>
