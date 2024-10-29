<?php

defined( 'ABSPATH' ) || exit;

global $post;

$afads_condition_type     = get_post_meta( $post->ID, 'afads_condition_type', true );
$afads_condition_operator = get_post_meta( $post->ID, 'afads_condition_operator', true );
$afads_condition_value    = get_post_meta( $post->ID, 'afads_condition_value', true );
$group_id                 = 0;
$condition_id             = 0;

?>
<div class="afads-metabox-fields">
  <div class="div-conditions-wrapper">
	<?php if ( ! empty( $afads_condition_type ) ) : ?>
		<?php
		foreach ( $afads_condition_type as $group_id => $condition ) {
			?>
		<div class="div-conditions-group">
		  <div class="abs-number"><?php echo intval( $group_id ) + 1; ?></div>
		  <div class="afads-group-actions">
			<a href="#" class="afads-remove-group-btn" title="<?php esc_html_e( 'Remove group', 'advanced-shipping' ); ?>"><?php esc_html_e( 'Remove', 'advanced-shipping' ); ?></a>
		  </div>
		  <div class="afads-conditions-wrap">
			<?php
			foreach ( $condition as $condition_id => $condition_val ) {
				$s_condition_val = $condition_val;
				$s_operator_val  = isset( $afads_condition_operator[ $group_id ][ $condition_id ] ) ? $afads_condition_operator[ $group_id ][ $condition_id ] : '';
				$s_field_val     = isset( $afads_condition_value[ $group_id ][ $condition_id ] ) ? $afads_condition_value[ $group_id ][ $condition_id ] : '';
				?>
			<div class="afads-condition-wrap">
			  <div class="afads-condition-type">
				<select class="input-select afads-condition-type-select" data-condition_id="<?php echo intval( $condition_id ); ?>" data-group_id="<?php echo intval( $group_id ); ?>" name="afads_condition_type[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
				  <?php foreach ( afads_get_condition_types() as $group_val => $group ) : ?>
					<optgroup label="<?php echo esc_html( afads_get_group_label( $group_val ) ); ?>">
						<?php foreach ( $group as $type_val => $type_label ) : ?>
						<option value="<?php echo esc_attr( $type_val ); ?>" <?php echo selected( $type_val, $s_condition_val ); ?>><?php echo esc_html( $type_label ); ?></option>
					  <?php endforeach ?>
					</optgroup>
				  <?php endforeach ?>
				</select>
			  </div>
			  <div class="afads-condition-operator">
				<select class="input-select afads-condition-operator-select" name="afads_condition_operator[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
				  <?php foreach ( afads_get_operators() as $operator_val => $operator_label ) : ?>
					<option value="<?php echo esc_attr( $operator_val ); ?>" <?php echo selected( $operator_val, $s_operator_val ); ?>><?php echo esc_html( $operator_label ); ?></option>
				  <?php endforeach ?>
				</select>
			  </div>
			  <div class="afads-condition-value">
				<?php
				if ( in_array( $s_condition_val, afads_get_values_for_all_operators() ) ) {
					$s_field_val = empty( $s_field_val ) ? '0.0' : $s_field_val;
					afads_get_number_input( $s_field_val, $group_id, $condition_id );
				} elseif ( in_array( $s_condition_val, array_keys( afads_get_custom_dropdown_fields() ) ) ) {
					afads_get_custom_dropdown_fields( $s_condition_val, $s_field_val, $group_id, $condition_id );
				} else {
					$s_field_val = empty( $s_field_val ) ? ' ' : $s_field_val;
					afads_get_text_input( $s_field_val, $group_id, $condition_id );
				}
				?>
			  </div>
			  <div class="afads-condition-remove">
				<span title="<?php esc_html_e( 'Remove Condition', 'advanced-shipping' ); ?>" class="dashicons dashicons-no-alt"></span>
			  </div>
			  <fieldset class="and"><legend>&</legend></fieldset>
			</div>
				<?php
			}
			?>
		  </div>
		  <div class="add-condition-button">
			<button title="<?php esc_html_e( 'Add new condition', 'advanced-shipping' ); ?>" class="button afads-add-group-condition" data-group_id="<?php echo intval( $group_id ); ?>" type="button"><?php esc_html_e( 'Add Condition', 'advanced-shipping' ); ?></button>
		  </div>
		  <div class="afads-group-seperator">
			<p class="afads-or-text">
			  <strong>Or</strong>
			</p>
		  </div>
		</div>
			<?php
		}
	endif;
	?>
	<?php if ( empty( $afads_condition_type ) ) : ?>
	  <div class="div-conditions-group">
		<div class="abs-number">1</div>
		<div class="afads-conditions-wrap">
		  <div class="afads-condition-wrap">
			<div class="afads-condition-type">
			  <select class="input-select afads-condition-type-select" data-condition_id="0" data-group_id="0" name="afads_condition_type[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
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
			  <select class="input-select afads-condition-operator-select" name="afads_condition_operator[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
				<?php foreach ( afads_get_operators() as $operator_val => $operator_label ) : ?>
				  <option value="<?php echo esc_attr( $operator_val ); ?>"><?php echo esc_html( $operator_label ); ?></option>
				<?php endforeach ?>
			  </select>
			</div>
			<div class="afads-condition-value">
			  <input type="number" min="1" name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]" class="input-text afads-condition-value-input"/>
			</div>
			<div class="afads-condition-remove">
			  <span title="<?php esc_html_e( 'Remove Condition', 'advanced-shipping' ); ?>" class="dashicons dashicons-no-alt"></span>
			</div>
			<fieldset class="and"><legend>&</legend></fieldset>
		  </div>
		</div>
		<div class="add-condition-button">
		  <button title="<?php esc_html_e( 'Add new condition', 'advanced-shipping' ); ?>" class="button afads-add-group-condition" data-group_id="0" type="button"><?php esc_html_e( 'Add Condition', 'advanced-shipping' ); ?></button>
		</div>
		<div class="afads-group-seperator">
		  <p class="afads-or-text">
			<strong>Or</strong>
		  </p>
		</div>
	  </div>
	<?php endif; ?>
  </div>
  <div class="add-group-button">
	<button title="<?php esc_html_e( 'Add OR group', 'advanced-shipping' ); ?>" class="button button-primary button-large button-afads-add-group" type="button"><?php esc_html_e( 'Add group', 'advanced-shipping' ); ?></button>
  </div>
</div>
