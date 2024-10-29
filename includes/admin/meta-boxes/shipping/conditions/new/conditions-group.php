<div class="div-conditions-group">
  <div class="afads-conditions-wrap">
	<div class="abs-number">{{group_number}}</div>
	<div class="afads-group-actions">
	  <a href="#" class="afads-remove-group-btn" title="<?php esc_html_e( 'Remove group', 'advanced-shipping' ); ?>"><?php esc_html_e( 'Remove', 'advanced-shipping' ); ?></a>
	</div>
	<div class="afads-condition-wrap">
	  <div class="afads-condition-type">
		<select name="afads_condition_type[{{group_id}}][0]" data-condition_id="0" data-group_id="{{group_id}}" class="input-select afads-condition-type-select">
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
		<select name="afads_condition_operator[{{group_id}}][0]" class="input-select afads-condition-operator-select">
		  <?php foreach ( afads_get_operators() as $operator_val => $operator_label ) : ?>
			<option value="<?php echo esc_attr( $operator_val ); ?>"><?php echo esc_html( $operator_label ); ?></option>
		  <?php endforeach ?>
		</select>
	  </div>
	  <div class="afads-condition-value">
		<input name="afads_condition_value[{{group_id}}][0]" min="1" type="number" class="input-text afads-condition-value-input"/>
	  </div>
	  <div class="afads-condition-remove">
		<span title="Remove Condition" class="dashicons dashicons-no-alt"></span>
	  </div>
	</div>
	<fieldset><legend>&</legend></fieldset>
  </div>
  <div class="add-condition-button">
  <button class="button afads-add-group-condition" data-group_id="{{group_id}}" type="button"><?php esc_html_e( 'Add Condition', 'advanced-shipping' ); ?></button>
  </div>
  <div class="afads-group-seperator">
	<p class="afads-or-text">
	  <strong>Or</strong>
	</p>
  </div>
</div>
