<?php

defined('ABSPATH') || exit;

class AF_A_S_Front {


	public function __construct() {
		add_filter('woocommerce_package_rates', array( $this, 'add_custom_rates' ), 100, 2);
	}//end __construct()


	public function add_custom_rates( $package_rates, $package ) {

		$default_pcakage_rate = $package_rates;

		foreach ($default_pcakage_rate as $rate_id => $rate) {

			$shipping_id = afads_get_instance_post_id($rate->get_instance_id());

			if (!$this->valid_shipping_rule($shipping_id, $package)) {

				unset($package_rates[ $rate_id ]);

				continue;
			}

			$afads_enable_setting = get_post_meta($shipping_id, 'afads_enable_setting', true);


			if (!empty($afads_enable_setting)) {

				$new_rate = (float) $this->get_advanced_pricing($shipping_id, $package) + $rate->get_cost();
				$rate->set_cost($new_rate);

			}

			$package_rates[ $rate_id ] = $rate;
		}

		$shipping_rules = $this->get_shipping_rules();

		foreach ($shipping_rules as $shipping_id) {

			if (!$this->valid_shipping_rule($shipping_id, $package)) {

				continue;
			}

			$afads_shipping_title = get_post_meta($shipping_id, 'afads_shipping_title', true);

			$afads_tax_status = get_post_meta($shipping_id, 'afads_tax_status', true);

			$afads_tax_status = get_post_meta($shipping_id, 'afads_tax_status', true);
			$afads_total_shipping_fee = (float) $this->get_advanced_pricing($shipping_id, $package);

			$wc_shipping_rate = new WC_Shipping_Rate();
			$wc_shipping_rate->set_id('af_ship_rule:' . $shipping_id);
			$wc_shipping_rate->set_method_id('af_ship_rule:' . $shipping_id);
			$wc_shipping_rate->set_instance_id($shipping_id);
			$wc_shipping_rate->set_label($afads_shipping_title);
			$wc_shipping_rate->set_cost($afads_total_shipping_fee);
			
			if( 'none' != $afads_tax_status ){
				$taxes = WC_Tax::calc_shipping_tax($afads_total_shipping_fee, WC_Tax::get_shipping_tax_rates());
				$wc_shipping_rate->set_taxes($taxes);
			}

			$package_rates[ $wc_shipping_rate->get_id() ] = $wc_shipping_rate;
		}

		return $package_rates;
	}//end add_custom_rates()


	public function get_package_weight( $package ) {

		$weight = 0;

		foreach ($package['contents'] as $id => $item) {

			$product = isset($item['data']) ? $item['data'] : 0;
			if ($product) {

				$weight += $item['quantity'] * intval($product->get_weight());
			}
		}
		return $weight;
	}//end get_package_weight()


	public function fall_in_range( $value, $min = 0.0, $max = 0.0 ) {

		$flag = false;

		if (0.0 === (float) $min && 0.0 === (float) $max) {

			$flag = true;

		} elseif (0.0 === (float) $min && $value <= (float) $max) {


			$flag = true;

		} elseif (0.0 === (float) $max && $value >= $min) {

			$flag = true;

		} elseif ($min <= $value && $value <= $max) {

			$flag = true;
		}

		return $flag;
	}//end fall_in_range()


	public function get_shipping_class_quantity( $shipping_class, $package ) {

		$quantity = 0;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if (has_term($shipping_class, 'product_shipping_class', $product_id)) {
				$quantity += $item['quantity'];
			}
		}

		return $quantity;
	}//end get_shipping_class_quantity()


	public function get_category_quantity( $category, $package ) {

		$quantity = 0;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if (has_term($category, 'product_cat', $product_id)) {
				$quantity += $item['quantity'];
			}
		}

		return $quantity;
	}//end get_category_quantity()


	public function get_product_quantity( $product, $package ) {

		$quantity = 0;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if ($product_id == $product) {
				$quantity += $item['quantity'];
			}
		}

		return $quantity;
	}//end get_product_quantity()


	public function get_product_category_quantity( $category, $package ) {

		$quantity = 0;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if (has_term($category, 'product_cat', $product_id)) {
				$quantity += $item['quantity'];
			}
		}

		return $quantity;
	}//end get_product_category_quantity()


	public function get_package_items_quantity( $package ) {

		$quantity = 0;

		foreach ($package['contents'] as $id => $item) {
			$quantity += isset($item['quantity']) ? $item['quantity'] : 0;
		}

		return $quantity;
	}//end get_package_items_quantity()


	public function get_package_total_items( $package ) {

		return is_array($package) && count($package) >= 1 ? count($package['contents']) : 0;
	}//end get_package_total_items()

	public function get_advanced_pricing( $shipping_id, $package ) {

		$afads_shipping_cost = get_post_meta($shipping_id, 'afads_shipping_cost', true) ? get_post_meta($shipping_id, 'afads_shipping_cost', true) : 0.00;

		$afads_cost_per_weight = get_post_meta($shipping_id, 'afads_cost_per_weight', true) ? get_post_meta($shipping_id, 'afads_cost_per_weight', true) : 0.00;

		$afads_cost_per_item = get_post_meta($shipping_id, 'afads_cost_per_item', true) ? get_post_meta($shipping_id, 'afads_cost_per_item', true) : 0.00;

		$afads_shipping_fee = get_post_meta($shipping_id, 'afads_shipping_fee', true) ? get_post_meta($shipping_id, 'afads_shipping_fee', true) : 0.00;

		$afads_shipping_cost += $afads_shipping_fee;

		$weight_pricing = $this->get_advanced_pricing_weight($shipping_id, $package);

		if ($weight_pricing) {
			$afads_shipping_cost += $weight_pricing;
		}

		$shipping_class_pricing = $this->get_advanced_pricing_shipping_class($shipping_id, $package);

		if ($shipping_class_pricing) {
			$afads_shipping_cost += $shipping_class_pricing;
		}

		$product_pricing = $this->get_advanced_pricing_product($shipping_id, $package);

		if ($product_pricing) {
			$afads_shipping_cost += $product_pricing;
		}

		$category_pricing = $this->get_advanced_pricing_category($shipping_id, $package);

		if ($category_pricing) {
			$afads_shipping_cost += $category_pricing;
		}

		$weight = $this->get_package_weight($package);

		if (!empty($afads_cost_per_weight) && !empty($weight)) {
			$afads_shipping_cost += $weight * floatval($afads_cost_per_weight);
		}

		if (!empty($afads_cost_per_item)) {
			$total_items = $this->get_package_total_items($package);
			$afads_shipping_cost += $total_items * floatval($afads_cost_per_item);
		}

		return floatval($afads_shipping_cost);
	}//end get_advanced_pricing()


	public function get_advanced_pricing_weight( $shipping_id, $package ) {

		$weight_price_rules = get_post_meta($shipping_id, 'afads_weight_price_rule', true);

		$weight = $this->get_package_weight($package);

		$final_fee = 0;

		if (!empty($weight_price_rules['min_weight'])) {

			for ($i = 0; $i <= count($weight_price_rules['min_weight']); $i++) {

				$min_weight = isset($weight_price_rules['min_weight'][ $i ]) ? $weight_price_rules['min_weight'][ $i ] : 0.0;
				$max_weight = isset($weight_price_rules['max_weight'][ $i ]) ? $weight_price_rules['max_weight'][ $i ] : 0.0;
				$fee = isset($weight_price_rules['fee'][ $i ]) ? $weight_price_rules['fee'][ $i ] : '';

				if (!empty($fee) && $fee >= 0.1 && $this->fall_in_range($weight, $min_weight, $max_weight)) {

					$final_fee += $fee;

				}
			}
		}
		return $final_fee;
	}//end get_advanced_pricing_weight()


	public function get_advanced_pricing_shipping_class( $shipping_id, $package ) {

		$shipping_class_price_rules = get_post_meta($shipping_id, 'afads_shipping_class_price_rule', true);
		$final_fee = 0;

		if (!empty($shipping_class_price_rules['min_quantity'])) {

			for ($i = 0; $i <= count($shipping_class_price_rules['min_quantity']); $i++) {

				$shipping_class = isset($shipping_class_price_rules['shipping_class'][ $i ]) ? $shipping_class_price_rules['shipping_class'][ $i ] : '';
				$min_quantity = isset($shipping_class_price_rules['min_quantity'][ $i ]) ? $shipping_class_price_rules['min_quantity'][ $i ] : 0.0;
				$max_quantity = isset($shipping_class_price_rules['max_quantity'][ $i ]) ? $shipping_class_price_rules['max_quantity'][ $i ] : 0.0;
				$fee = isset($shipping_class_price_rules['fee'][ $i ]) ? $shipping_class_price_rules['fee'][ $i ] : 0.0;

				$shipping_class_qty = $this->get_shipping_class_quantity($shipping_class, $package);

				if (!empty($fee) && $fee >= 0.1 && $this->fall_in_range($shipping_class_qty, $min_quantity, $max_quantity)) {

					$final_fee += $fee;
				}
			}
		}

		return $final_fee;
	}//end get_advanced_pricing_shipping_class()


	public function get_advanced_pricing_product( $shipping_id, $package ) {

		$product_price_rules = get_post_meta($shipping_id, 'afads_product_price_rule', true);
		$final_fee = 0;
		
	
		if (!empty($product_price_rules['min_quantity'])) {

			for ($i = 0; $i <= count($product_price_rules['min_quantity'])  ; $i++) {

				$product = isset($product_price_rules['product'][ $i ]) ? $product_price_rules['product'][ $i ] : '';
				$min_quantity = isset($product_price_rules['min_quantity'][ $i ]) ? $product_price_rules['min_quantity'][ $i ] : 0.0;
				$max_quantity = isset($product_price_rules['max_quantity'][ $i ]) ? $product_price_rules['max_quantity'][ $i ] : 0.0;
				$fee = isset($product_price_rules['fee'][ $i ]) ? $product_price_rules['fee'][ $i ] : '';
				
				$product_qty = $this->get_product_quantity($product, $package);

				if (!empty($fee) && $fee >= 0.1 && $this->fall_in_range($product_qty, $min_quantity, $max_quantity)) {

					$final_fee += $fee;
				}
			}
		}
		
		return $final_fee;
	}//end get_advanced_pricing_product()


	public function get_advanced_pricing_category( $shipping_id, $package ) {

		$category_price_rules = get_post_meta($shipping_id, 'afads_category_price_rule', true);
		$final_fee = 0;

		if (!empty($category_price_rules['min_quantity'])) {

			for ($i = 0; $i <= count($category_price_rules['min_quantity']); $i++) {

				$category = isset($category_price_rules['category'][ $i ]) ? $category_price_rules['category'][ $i ] : '';
				$fee = isset($category_price_rules['fee'][ $i ]) ? $category_price_rules['fee'][ $i ] : '';

				$category_qty = $this->get_category_quantity($category, $package);

				$min_quantity = isset($category_price_rules['min_quantity'][ $i ]) ? $category_price_rules['min_quantity'][ $i ] : 0;

				$max_quantity = isset($category_price_rules['max_quantity'][ $i ]) ? $category_price_rules['max_quantity'][ $i ] : 0;

				if (!empty($fee) && $fee >= 0.1 && $this->fall_in_range($category_qty, $min_quantity, $max_quantity)) {

					$final_fee += $fee;

				}
			}
		}
		return $final_fee;
	}//end get_advanced_pricing_category()


	public function get_shipping_rules() {
		return get_posts(
			array(
				'post_type' => 'af_ship_rule',
				'post_status' => 'publish',
				'numberposts' => -1,
				'fields' => 'ids',
			)
		);
	}//end get_shipping_rules()


	public function valid_shipping_rule( $shipping_id, $package ) {

		$_types = get_post_meta($shipping_id, 'afads_condition_type', true);
		$_operators = get_post_meta($shipping_id, 'afads_condition_operator', true);
		$_values = get_post_meta($shipping_id, 'afads_condition_value', true);

		if (empty($_types)) {
			return true;
		}

		foreach ($_types as $group_id => $group_conditions) {

			foreach ($group_conditions as $condition_id => $condition_type) {

				$condition_operator = isset($_operators[ $group_id ][ $condition_id ]) ? $_operators[ $group_id ][ $condition_id ] : '=';
				$condition_value = isset($_values[ $group_id ][ $condition_id ]) ? $_values[ $group_id ][ $condition_id ] : '=';

				if (!$this->compare_shipping_condition($condition_type, $condition_value, $condition_operator, $package)) {
					continue 2;
				}
			}

			return true;
		}
	}//end valid_shipping_rule()


	public function compare_shipping_condition( $condition_type, $condition_value, $condition_operator, $package ) {

		if (in_array($condition_type, array( 'length', 'height', 'weight', 'width' ))) {
			$condition_value = $condition_value <= 0 ? 1 : (float) $condition_value;
		}

		switch ($condition_type) {
			case 'subtotal':
				return $this->compare_cart_subtotal($package, $condition_value, $condition_operator);
			case 'subtotal-excl-tax':
				return $this->compare_cart_subtotal_excl_tax($package, $condition_value, $condition_operator);
			case 'tax':
				return $this->compare_cart_tax($package, $condition_value, $condition_operator);
			case 'quantity':
				return $this->compare_cart_quantity($package, $condition_value, $condition_operator);
			case 'product':
				return $this->contains_product($package, $condition_value, $condition_operator);
			case 'category':
				return $this->contains_category($package, $condition_value, $condition_operator);
			case 'coupon':
				return $this->contains_coupon($package, $condition_value, $condition_operator);
			case 'shipping-class':
				return $this->contains_shipping_class($package, $condition_value, $condition_operator);
			case 'city':
				return $this->contains_city($package, $condition_value, $condition_operator);
			case 'country':
				return $this->contains_country($package, $condition_value, $condition_operator);
			case 'state':
				return $this->contains_state($package, $condition_value, $condition_operator);
			case 'zip-code':
				return $this->contains_zipcode($package, $condition_value, $condition_operator);
			case 'customer':
				return $this->compare_customer($package, $condition_value, $condition_operator);
			case 'user-role':
				return $this->compare_user_role($package, $condition_value, $condition_operator);
			case 'weight':
				return $this->compare_cart_weight($package, $condition_value, $condition_operator);
			case 'width':
				return $this->compare_width($package, $condition_value, $condition_operator);
			case 'height':
				return $this->compare_height($package, $condition_value, $condition_operator);
			case 'length':
				return $this->compare_length($package, $condition_value, $condition_operator);
			case 'stock':
				return $this->compare_stock($package, $condition_value, $condition_operator);
			case 'stock-status':
				return $this->compare_stock_status($package, $condition_value, $condition_operator);
			case 'products':
				return $this->contains_products($package, $condition_value, $condition_operator);
			case 'categories':
				return $this->contains_categories($package, $condition_value, $condition_operator);
			case 'tags':
				return $this->contains_tags($package, $condition_value, $condition_operator);
		}
	}//end compare_shipping_condition()


	public function compare_cart_quantity( $package, $condition_value, $condition_operator ) {

		$quantity = 0;

		foreach ($package['contents'] as $id => $item) {
			$quantity += isset($item['quantity']) ? $item['quantity'] : 0;
		}

		return $this->compare_values($quantity, $condition_value, $condition_operator, 'float');
	}//end compare_cart_quantity()


	public function compare_cart_subtotal( $package, $condition_value, $condition_operator ) {

		$subtotal = 0;

		foreach ($package['contents'] as $id => $item) {

			if ($item['line_subtotal']) {

				$subtotal += isset($item['line_subtotal']) ? abs($item['line_subtotal']) : 0;
			}
		}

		return $this->compare_values($subtotal, $condition_value, $condition_operator, 'float');
	}//end compare_cart_subtotal()


	public function compare_cart_subtotal_excl_tax( $package, $condition_value, $condition_operator ) {

		$subtotal = 0;

		foreach ($package['contents'] as $id => $item) {

			if ($item['line_subtotal'] != $item['line_total']) {

				$subtotal += isset($item['line_subtotal']) ? abs($item['line_subtotal'] - $item['line_subtotal_tax']) : 0;

			} elseif (!empty($item['line_subtotal_tax'])) {

				$subtotal += isset($item['line_subtotal']) ? $item['line_subtotal'] - $item['line_subtotal_tax'] : 0;
			} else {
				$subtotal += isset($item['line_subtotal']) ? $item['line_subtotal'] : 0;
			}
		}

		return $this->compare_values($subtotal, $condition_value, $condition_operator, 'float');
	}//end compare_cart_subtotal_excl_tax()


	public function compare_cart_tax( $package, $condition_value, $condition_operator ) {

		$tax = 0;

		foreach ($package['contents'] as $id => $item) {

			$tax += isset($item['line_subtotal_tax']) ? $item['line_subtotal_tax'] : 0;
		}

		return $this->compare_values($tax, $condition_value, $condition_operator, 'float');
	}//end compare_cart_tax()





	public function contains_product( $package, $condition_value, $condition_operator ) {

		$flag_contains_product = false;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if ('!=' == $condition_operator && $condition_value == $product_id) {

				$flag_contains_product = false;
				break;
			}

			if ($this->compare_values($product_id, $condition_value, $condition_operator)) {

				$flag_contains_product = true;
			}
		}

		return $flag_contains_product;
	}//end contains_product()


	public function contains_category( $package, $condition_value, $condition_operator ) {

		$flag_contains_category = false;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if ('=' == $condition_operator) {

				if (has_term($condition_value, 'product_cat', $product_id)) {
					$flag_contains_category = true;
				}
			} elseif ('!=' == $condition_operator) {

				if (has_term($condition_value, 'product_cat', $product_id)) {
					$flag_contains_category = false;
					break;
				}

				if (!has_term($condition_value, 'product_cat', $product_id)) {
					$flag_contains_category = true;
				}
			}
		}

		return $flag_contains_category;
	}//end contains_category()


	public function contains_shipping_class( $package, $condition_value, $condition_operator ) {

		$flag_shipping_class = false;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if ('=' == $condition_operator) {

				if (has_term($condition_value, 'product_shipping_class', $product_id)) {
					$flag_shipping_class = true;
				}
			} elseif ('!=' == $condition_operator) {

				if (has_term($condition_value, 'product_shipping_class', $product_id)) {
					$flag_shipping_class = false;
					break;
				}

				if (!has_term($condition_value, 'product_shipping_class', $product_id)) {
					$flag_shipping_class = true;
				}
			}
		}

		return $flag_shipping_class;
	}//end contains_shipping_class()


	public function contains_coupon( $package, $condition_value, $condition_operator ) {

		$flag_contains_coupon = false;

		if (isset($package['applied_coupons'])) {

			$applied_coupon = array_map('strtoupper', $package['applied_coupons']);

			if ('=' == $condition_operator && in_array(strtoupper($condition_value), (array) $applied_coupon)) {
				$flag_contains_coupon = true;

			} elseif ('!=' == $condition_operator && !in_array(strtoupper($condition_value), (array) $applied_coupon)) {

				$flag_contains_coupon = true;
			}
		}

		return $flag_contains_coupon;
	}//end contains_coupon()


	public function contains_city( $package, $condition_value, $condition_operator ) {

		$city = isset($package['destination']['city']) ? $package['destination']['city'] : '';

		return $this->compare_values($condition_value, $city, $condition_operator);
	}//end contains_city()


	public function contains_country( $package, $condition_value, $condition_operator ) {

		$country = isset($package['destination']['country']) ? $package['destination']['country'] : '';

		return $this->compare_values($condition_value, $country, $condition_operator);
	}//end contains_country()


	public function contains_state( $package, $condition_value, $condition_operator ) {

		$state = isset($package['destination']['state']) ? $package['destination']['state'] : '';

		$user = wp_get_current_user(); // The current user

		$country = $user->billing_country;

		$full_sate_name = strtoupper($state . ' ' . WC()->countries->get_states($country)[ $state ]);

		$state_flag = false;

		if ('=' == $condition_operator && str_contains($full_sate_name, strtoupper($condition_value))) {
			$state_flag = true;
		}

		if ('!=' == $condition_operator && !str_contains($full_sate_name, strtoupper($condition_value))) {
			$state_flag = true;
		}

		return $state_flag;
	}//end contains_state()


	public function contains_zipcode( $package, $condition_value, $condition_operator ) {

		$postcode = isset($package['destination']['postcode']) ? $package['destination']['postcode'] : '';

		return $this->compare_values($condition_value, $postcode, $condition_operator, 'float');
	}//end contains_zipcode()


	public function compare_customer( $package, $condition_value, $condition_operator ) {

		$user_id = isset($package['user']['ID']) ? $package['user']['ID'] : 0;

		return $this->compare_values($condition_value, $user_id, $condition_operator, 'float');
	}//end compare_customer()


	public function compare_user_role( $package, $condition_value, $condition_operator ) {

		$user_id = isset($package['user']['ID']) ? $package['user']['ID'] : '';
		$user_roles = array( 'guest' );

		if ($user_id) {
			$user = get_user_by('id', $user_id);
			$user_roles = (array) $user->roles;

		}

		$flag_user_role = false;

		if ('=' == $condition_operator && in_array($condition_value, $user_roles)) {

			$flag_user_role = true;

		} elseif ('!=' == $condition_operator) {

			if (!in_array($condition_value, $user_roles)) {
				$flag_user_role = true;
			}
		}

		return $flag_user_role;
	}//end compare_user_role()

	public function compare_cart_weight( $package, $condition_value, $condition_operator ) {

		$weight = $this->get_package_weight($package);

		return $this->compare_values($weight, $condition_value, $condition_operator, 'float');
	}//end compare_cart_weight()

	public function compare_width( $package, $condition_value, $condition_operator ) {

		$width = 0;

		foreach ($package['contents'] as $id => $item) {

			$product = isset($item['data']) ? $item['data'] : 0;

			if ($product) {
				$width += floatval($product->get_width());
			}
		}

		return $this->compare_values($condition_value, $width, $condition_operator, 'float');
	}//end compare_width()


	public function compare_height( $package, $condition_value, $condition_operator ) {

		$height = 0;

		foreach ($package['contents'] as $id => $item) {

			$product = isset($item['data']) ? $item['data'] : 0;

			if ($product) {
				$height += floatval($product->get_height());
			}
		}

		return $this->compare_values($condition_value, $height, $condition_operator, 'float');
	}//end compare_height()


	public function compare_length( $package, $condition_value, $condition_operator ) {

		$length = 0;

		foreach ($package['contents'] as $id => $item) {

			$product = isset($item['data']) ? $item['data'] : 0;

			if ($product) {
				$length += floatval($product->get_length());
			}
		}

		return $this->compare_values($condition_value, $length, $condition_operator, 'float');
	}//end compare_length()


	public function compare_stock( $package, $condition_value, $condition_operator ) {

		$stock = 0;

		foreach ($package['contents'] as $id => $item) {

			$product = isset($item['data']) ? $item['data'] : 0;

			if ($product) {
				$stock += intval($product->get_stock_quantity());
			}
		}

		return $this->compare_values($condition_value, $stock, $condition_operator, 'float');
	}//end compare_stock()


	public function compare_stock_status( $package, $condition_value, $condition_operator ) {

		$stock = 0;

		foreach ($package['contents'] as $id => $item) {

			$product = isset($item['data']) ? $item['data'] : 0;

			if ($product && $this->compare_values($condition_value, $product->get_stock_status(), $condition_operator)) {
				return true;
			}
		}

		return false;
	}//end compare_stock_status()


	public function contains_products( $package, $condition_value, $condition_operator ) {
		$flag_contains_products = false;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if ('=' == $condition_operator) {

				if (in_array($product_id, (array) $condition_value)) {
					$flag_contains_products = true;
				}
			} elseif ('!=' == $condition_operator) {

				if (in_array($product_id, (array) $condition_value)) {
					$flag_contains_products = false;
					break;
				}

				if (!in_array($product_id, (array) $condition_value)) {
					$flag_contains_products = true;
				}
			}
		}

		return $flag_contains_products;
	}//end contains_products()


	public function contains_categories( $package, $condition_value, $condition_operator ) {

		$flag_contains_cat = false;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if ('=' == $condition_operator) {

				if (has_term($condition_value, 'product_cat', $product_id)) {
					$flag_contains_cat = true;
				}
			} elseif ('!=' == $condition_operator) {
				if (has_term($condition_value, 'product_cat', $product_id)) {
					$flag_contains_cat = false;
					break;
				}
				if (!has_term($condition_value, 'product_cat', $product_id)) {
					$flag_contains_cat = true;
				}
			}
		}

		return $flag_contains_cat;
	}//end contains_categories()


	public function contains_tags( $package, $condition_value, $condition_operator ) {

		$flag_contains_tags = false;

		foreach ($package['contents'] as $id => $item) {

			$product_id = isset($item['product_id']) ? $item['product_id'] : 0;

			if ('=' == $condition_operator) {

				if (has_term($condition_value, 'product_tag', $product_id)) {
					$flag_contains_tags = true;
				}
			} elseif ('!=' == $condition_operator) {
				if (has_term($condition_value, 'product_tag', $product_id)) {
					$flag_contains_tags = false;
					break;
				}

				if (!has_term($condition_value, 'product_tag', $product_id)) {
					$flag_contains_tags = true;
				}
			}
		}

		return $flag_contains_tags;
	}//end contains_tags()



	public function compare_values( $value1, $value2, $operator, $variable_type = '' ) {
		$value1 = trim($value1);
		$value2 = trim($value2);

		if ('string' == gettype($value1)) {
			$value1 = strtoupper($value1);
		}
		if ('string' == gettype($value2)) {
			$value2 = strtoupper($value2);
		}

		if ('float' == $variable_type) {
			$value1 = (float) $value1;
			$value2 = (float) $value2;
		}

		switch ($operator) {
			case '!=':
				return $value1 != $value2;
			case '>':
				return $value1 > $value2;
			case '<':
				return $value1 < $value2;
			case '>=':
				return $value1 >= $value2;
			case '<=':
				return $value1 <= $value2;
			default:
				return $value1 == $value2;
		}
	}//end compare_values()
}//end class


new AF_A_S_Front();

