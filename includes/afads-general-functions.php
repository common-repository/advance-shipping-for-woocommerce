<?php

defined( 'ABSPATH' ) || exit;

function afads_get_condition_types() {

	return array(
		'cart-based'    => array(
			'subtotal'          => __( 'Subtotal', 'advanced-shipping' ),
			'subtotal-excl-tax' => __( 'Subtotal Excl. tax', 'advanced-shipping' ),
			'tax'               => __( 'Tax', 'advanced-shipping' ),
			'quantity'          => __( 'Quantity', 'advanced-shipping' ),
			'product'           => __( 'Contains Product', 'advanced-shipping' ),
			'category'          => __( 'Contains Category', 'advanced-shipping' ),
			'coupon'            => __( 'Coupon', 'advanced-shipping' ),
			'weight'            => __( 'Weight', 'advanced-shipping' ),
			'shipping-class'    => __( 'Contains Shipping class', 'advanced-shipping' ),
		),
		'user-based'    => array(
			'zip-code'  => __( 'Zip code', 'advanced-shipping' ),
			'state'     => __( 'State', 'advanced-shipping' ),
			'city'      => __( 'City', 'advanced-shipping' ),
			'country'   => __( 'Country', 'advanced-shipping' ),
			'user-role' => __( 'User role', 'advanced-shipping' ),
			'customer'  => __( 'Specific customer', 'advanced-shipping' ),
		),
		'product-based' => array(
			'width'        => __( 'Width', 'advanced-shipping' ),
			'height'       => __( 'Height', 'advanced-shipping' ),
			'length'       => __( 'length', 'advanced-shipping' ),
			'stock'        => __( 'Stock', 'advanced-shipping' ),
			'stock-status' => __( 'Stock Status', 'advanced-shipping' ),
		),
		'products'      => array(
			'products'   => __( 'Products', 'advanced-shipping' ),
			'categories' => __( 'Products Categories', 'advanced-shipping' ),
			'tags'       => __( 'Products Tags', 'advanced-shipping' ),
		),
	);
}//end afads_get_condition_types()


function afads_get_instance_post_id( $instance_id ) {

	$post = get_posts(
		array(
			'post_type'   => 'af_ship_rule_alt',
			'post_status' => 'publish',
			'numberposts' => -1,
			'meta_key'    => 'afads_instance_id',
			'meta_value'  => $instance_id,
			'fields'      => 'ids',
		)
	);

	return current( $post );
}//end afads_get_instance_post_id()


function afads_get_values_for_all_operators() {
	return array(
		'subtotal',
		'subtotal-excl-tax',
		'tax',
		'quantity',
		'weight',
		'width',
		'height',
		'length',
		'stock',
		'amount-spent',
		'order-count',
		'previous-order-amount',
	);
}//end afads_get_values_for_all_operators()


function afads_get_values_for_equal_operators() {
	return array(
		'product',
		'category',
		'coupon',
		'shipping-class',
		'zip-code',
		'state',
		'city',
		'country',
		'stock-status',
		'products',
		'categories',
		'tags',
		'attributes',
		'previous-order',
	);
}//end afads_get_values_for_equal_operators()


function afads_get_group_label( $group_val ) {
	switch ( $group_val ) {
		case 'cart-based':
			return __( 'Cart Based', 'advanced-shipping' );
		case 'user-based':
			return __( 'User Based', 'advanced-shipping' );
		case 'product-based':
			return __( 'Product Based', 'advanced-shipping' );
		case 'products':
			return __( 'Products', 'advanced-shipping' );
		case 'customer-based':
			return __( 'Customer Based', 'advanced-shipping' );
	}
}//end afads_get_group_label()


function afads_get_operators() {
	return array(
		'='  => __( 'is Equal to', 'advanced-shipping' ),
		'!=' => __( 'is Not equal to', 'advanced-shipping' ),
		'>'  => __( 'is Greater than', 'advanced-shipping' ),
		'<'  => __( 'is Less than', 'advanced-shipping' ),
		'>=' => __( 'is Greater than or equals', 'advanced-shipping' ),
		'<=' => __( 'is Less than or equals', 'advanced-shipping' ),
	);
}//end afads_get_operators()


function afads_get_custom_dropdown_fields( $s_condition_val = '', $s_field_val = '', $group_id = '', $condition_id = '' ) {

	if ( empty( $s_condition_val ) ) {
		return array(
			'product'      => afads_get_products_dropdown(),
			'category'     => afads_get_product_categories_dropdown(),
			'country'      => afads_get_country_dropdown(),
			'customer'     => afads_get_customer_dropdown(),
			'user-role'    => afads_get_user_role_dropdown(),
			'stock-status' => afads_get_stock_status_dropdown(),
			'products'     => afads_get_products_dropdown_multiple(),
			'categories'   => afads_get_product_categories_dropdown_multiple(),
			'tags'         => afads_get_product_tags_dropdown(),
		);
	} else {
		switch ( $s_condition_val ) {
			case 'product':
				afads_get_products_dropdown( $s_field_val, $group_id, $condition_id );
				break;
			case 'category':
				afads_get_product_categories_dropdown( $s_field_val, $group_id, $condition_id );
				break;
			case 'country':
				afads_get_country_dropdown( $s_field_val, $group_id, $condition_id );
				break;
			case 'customer':
				afads_get_customer_dropdown( $s_field_val, $group_id, $condition_id );
				break;
			case 'user-role':
				afads_get_user_role_dropdown( $s_field_val, $group_id, $condition_id );
				break;
			case 'stock-status':
				afads_get_stock_status_dropdown( $s_field_val, $group_id, $condition_id );
				break;
			case 'products':
				afads_get_products_dropdown_multiple( $s_field_val, $group_id, $condition_id );
				break;
			case 'categories':
				afads_get_product_categories_dropdown_multiple( $s_field_val, $group_id, $condition_id );
				break;
			case 'tags':
				afads_get_product_tags_dropdown( $s_field_val, $group_id, $condition_id );
				break;
		}
	}
}//end afads_get_custom_dropdown_fields()


function afads_get_text_input( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<input type="text" name="afads_condition_value[{{group_id}}][{{condition_id}}]" class="input-text afads-text-input"/>
		<?php
		return ob_get_clean();
	} else {
		?>
		<input type="text" name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]" class="input-text afads-text-input" value="<?php echo esc_attr( $s_field_val ); ?>"/>
		<?php
	}
}//end afads_get_text_input()


function afads_get_number_input( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<input type="number" min="1" name="afads_condition_value[{{group_id}}][{{condition_id}}]" class="input-number afads-number-input"/>
		<?php
		return ob_get_clean();
	} else {
		?>
		<input type="number" min="1" name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]" class="input-number afads-number-input" value="<?php echo esc_attr( $s_field_val ); ?>"/>
		<?php
	}
}//end afads_get_number_input()


function afads_get_conditions_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<select class="input-select afads-condition-type-select" name="afads_condition_type[{{group_id}}][{{condition_id}}]">
			<?php foreach ( afads_get_condition_types() as $group_val => $group ) : ?>
				<optgroup label="<?php echo esc_html( afads_get_group_label( $group_val ) ); ?>">
					<?php foreach ( (array) $group as $type_val => $type_label ) : ?>
						<option value="<?php echo esc_attr( $type_val ); ?>"><?php echo esc_html( $type_label ); ?></option>
					<?php endforeach ?>
				</optgroup>
			<?php endforeach ?>
		</select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select class="input-select afads-condition-type-select" name="afads_condition_type[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
			<?php foreach ( afads_get_condition_types() as $group_val => $group ) : ?>
				<optgroup label="<?php echo esc_html( afads_get_group_label( $group_val ) ); ?>">
					<?php foreach ( (array) $group as $type_val => $type_label ) : ?>
						<option value="<?php echo esc_attr( $type_val ); ?>" <?php echo selected( $type_val, $s_field_val ); ?>><?php echo esc_html( $type_label ); ?></option>
					<?php endforeach ?>
				</optgroup>
			<?php endforeach ?>
		</select>
		<?php
	}
}//end afads_get_conditions_dropdown()


function afads_get_operators_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<select class="input-select afads-condition-operator-select" name="afads_condition_operator[{{group_id}}][{{condition_id}}]">
			<?php foreach ( afads_get_operators() as $operator_val => $operator_label ) : ?>
				<option value="<?php echo esc_attr( $operator_val ); ?>"><?php echo esc_html( $operator_label ); ?></option>
			<?php endforeach ?>
		</select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select class="input-select afads-condition-operator-select" name="afads_condition_operator[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
			<?php foreach ( afads_get_operators() as $operator_val => $operator_label ) : ?>
				<option value="<?php echo esc_attr( $operator_val ); ?>" <?php echo selected( $operator_val, $s_field_val ); ?>><?php echo esc_html( $operator_label ); ?></option>
			<?php endforeach ?>
		</select>
		<?php
	}
}//end afads_get_operators_dropdown()


function afads_get_country_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	if ( empty( $s_field_val ) ) {
		ob_start()
		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}]" class=">
			<?php foreach ( wc()->countries->get_countries() as $country_code => $country_name ) : ?>
			<option value="<?php echo esc_attr( $country_code ); ?>"><?php echo esc_html( $country_name ); ?></option>
		<?php endforeach; ?>
	</select>
		<?php
		return ob_get_clean();
	} else {
		?>
	<select name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]" class=">
		<?php foreach ( wc()->countries->get_countries() as $country_code => $country_name ) : ?>
		<option value="<?php echo esc_attr( $country_code ); ?>" <?php echo selected( $country_code, $s_field_val ); ?>><?php echo esc_html( $country_name ); ?></option>
	<?php endforeach; ?>
</select>
		<?php
	}
}//end afads_get_country_dropdown()


function afads_get_customer_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {

	if ( empty( $s_field_val ) ) {
		ob_start()
		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}]" class="afads-customers">
			<option value=""><?php esc_html_e( 'Choose customer', 'advanced-shipping' ); ?></option>
		</select>
		<?php
		return ob_get_clean();

	} else {

		$user  = get_user_by( 'id', $s_field_val );
		$title = $user->display_name . '(' . $user->user_email . ')';
		?>
		<select name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]" class="afads-customers">
			<option value="<?php echo (int) $s_field_val; ?>"><?php echo esc_html( $title ); ?></option>
		</select>
		<?php
	}
}//end afads_get_customer_dropdown()


function afads_get_user_role_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	global $wp_roles;
	$user_roles          = (array) $wp_roles->get_names();
	$user_roles['guest'] = 'Guest';
	if ( empty( $s_field_val ) ) {

		ob_start();
		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}]" class="afads-countries">
			<option value=""><?php esc_html_e( 'Choose user role', 'advanced-shipping' ); ?></option>
			<?php foreach ( $user_roles  as $role_key => $role_name ) : ?>
				<option value="<?php echo esc_attr( $role_key ); ?>" ><?php echo esc_html( $role_name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]" class="afads-countries">
			<option value=""><?php esc_html_e( 'Choose user role', 'advanced-shipping' ); ?></option>
			<?php foreach ( $user_roles  as $role_key => $role_name ) : ?>
				<option value="<?php echo esc_attr( $role_key ); ?>" <?php echo selected( $role_key, $s_field_val ); ?>><?php echo esc_html( $role_name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}//end afads_get_user_role_dropdown()


function afads_get_shipping_class_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	$shipping_classes = get_terms(
		array(
			'taxonomy'   => 'product_shipping_class',
			'hide_empty' => false,
		)
	);
	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<select class="afads-shipping-classes" name="afads_condition_value[{{group_id}}][{{condition_id}}]">
			<option value=""><?php esc_html_e( 'Choose shipping class', 'advanced-shipping' ); ?></option>
			<?php foreach ( (array) $shipping_classes as $class ) : ?>
				<option value="<?php echo esc_attr( $class->slug ); ?>"><?php echo esc_html( $class->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select class="afads-shipping-classes" name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
			<option value=""><?php esc_html_e( 'Choose shipping class', 'advanced-shipping' ); ?></option>
			<?php foreach ( (array) $shipping_classes as $class ) : ?>
				<option value="<?php echo esc_attr( $class->slug ); ?>" <?php echo selected( $class->slug, $s_field_val ); ?>><?php echo esc_html( $class->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}//end afads_get_shipping_class_dropdown()


function afads_get_stock_status_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	$stock_status = array(
		'instock'     => __( 'In Stock', 'advanced-shipping' ),
		'outofstock'  => __( 'out of Stock', 'advanced-shipping' ),
		'onbackorder' => __( 'On backorder', 'advanced-shipping' ),
	);
	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<select class="afads-stock-statuses" name="afads_condition_value[{{group_id}}][{{condition_id}}]">
			<option value=""><?php esc_html_e( 'Choose stock status', 'advanced-shipping' ); ?></option>
			<?php foreach ( (array) $stock_status as $stock_val => $stock_label ) : ?>
				<option value="<?php echo esc_attr( $stock_val ); ?>"><?php echo esc_html( $stock_label ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select class="afads-stock-statuses" name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]">
			<option value=""><?php esc_html_e( 'Choose stock status', 'advanced-shipping' ); ?></option>
			<?php foreach ( (array) $stock_status as $stock_val => $stock_label ) : ?>
				<option value="<?php echo esc_attr( $stock_val ); ?>" <?php echo selected( $stock_val, $s_field_val ); ?>><?php echo esc_html( $stock_label ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}//end afads_get_stock_status_dropdown()


function afads_get_products_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {

	global $wp_roles;

	if ( empty( $s_field_val ) ) {
		ob_start()
		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}]" class="afads_product_search"></select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select name="afads_condition_value[<?php echo esc_attr( (int) $group_id ); ?>][<?php echo esc_attr( (int) $condition_id ); ?>]" class="afads_product_search">
			<?php $product = wc_get_product( $s_field_val ); if ( $product ) : ?>
			<option value="<?php echo esc_attr( $s_field_val ); ?>" selected ><?php echo esc_html( $product->get_name() ); ?></option>
		<?php endif ?>
	</select>
		<?php
	}
}//end afads_get_products_dropdown()


function afads_get_product_categories_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	global $wp_roles;

	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}]" class="afads_category_search">
		</select>
		<?php
		return ob_get_clean();
	} else {

		$term = get_term_by( 'slug', $s_field_val, 'product_cat' );
		?>
		<select name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>]" class="afads_category_search">
			<?php if ( $term ) : ?>
				<option value="<?php echo esc_attr( $term->slug ); ?>" selected><?php echo esc_html( $term->name ); ?></option>
			<?php endif ?>
		</select>
		<?php
	}
}//end afads_get_product_categories_dropdown()


function afads_get_products_dropdown_multiple( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	global $wp_roles;

	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}][]" class="afads_product_search_multiple" multiple></select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select ss name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>][]" class="afads_product_search_multiple" multiple>
			<?php
			foreach ( (array) $s_field_val as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}
				?>
				<option value="<?php echo esc_attr( $product_id ); ?>" selected><?php echo esc_html( $product->get_name() ); ?></option>
			<?php } ?>
		</select>
		<?php
	}
}//end afads_get_products_dropdown_multiple()


function afads_get_product_categories_dropdown_multiple( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	global $wp_roles;

	if ( empty( $s_field_val ) ) {
		ob_start();

		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}][]" class="afads_category_search_multiple" multiple>
		</select>
		<?php
		return ob_get_clean();
	} else {

		?>
		<select name="afads_condition_value[<?php echo (int) $group_id; ?>][<?php echo intval( $condition_id ); ?>][]" class="afads_category_search_multiple afads_category_search_multiple<?php echo intval( $condition_id ); ?><?php echo (int) $group_id; ?>" multiple>
			<?php
			foreach ( (array) $s_field_val as $tag_slug ) :
				$term = get_term_by( 'slug', $tag_slug, 'product_cat' );
				if ( ! $term ) {
					continue;
				}
				?>
				<option value="<?php echo esc_attr( $tag_slug ); ?>" selected><?php echo esc_html( $term->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}//end afads_get_product_categories_dropdown_multiple()


function afads_get_product_tags_dropdown( $s_field_val = '', $group_id = '', $condition_id = '' ) {
	global $wp_roles;
	if ( empty( $s_field_val ) ) {
		ob_start();
		?>
		<select name="afads_condition_value[{{group_id}}][{{condition_id}}][]" class="afads_tag_search_multiple" multiple>
		</select>
		<?php
		return ob_get_clean();
	} else {
		?>
		<select name="afads_condition_value[<?php echo intval( $group_id ); ?>][<?php echo intval( $condition_id ); ?>][]" class="afads_tag_search_multiple" multiple>
			<?php
			foreach ( (array) $s_field_val as $tag_slug ) :
				$term = get_term_by( 'slug', $tag_slug, 'product_tag' );
				if ( ! is_a( $term, 'WP_Term' ) ) {
					continue;
				}
				?>
				<option value="<?php echo esc_attr( $tag_slug ); ?>" selected><?php echo esc_html( $term->name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}
}//end afads_get_product_tags_dropdown()

