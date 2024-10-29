<?php

defined( 'ABSPATH' ) || exit;

class AF_A_S_Conditional_Data {

	public function get_conditional_data( $data_key ) {

		$data = array();

		switch ( $data_key ) {
			case 'shipping-classes':
				return $this->get_shipping_classes();
			case 'state':
				return $this->get_states();
			case 'country':
				return $this->get_countries();
			case 'user-roles':
				return $this->get_user_roles();
			case 'stock-status':
				return $this->get_stock_statuses();
			default:
				return $data;
		}
	}//end get_conditional_data()


	public function get_shipping_classes() {

		$data = array();

		$shipping_classes = get_terms(
			array(
				'taxonomy'   => 'product_shipping_class',
				'hide_empty' => false,
			)
		);

		foreach ( $shipping_classes as $shipping_class ) {
			$data[ $shipping_class->slug ] = $shipping_class->term_name;
		}

		return $data;
	}//end get_shipping_classes()


	public function get_states() {
		foreach ( $this->get_countries() as $key => $val ) {
			$states = WC()->countries->get_states( $key );
			if ( empty( $states ) ) {
				continue;
			} else {
				foreach ( $states as $key1 => $value ) {

					$state_val   = $key . ':' . $key1;
					$state_label = $value . '(' . $val . ')';

					$data[ $state_val ] = $state_label;
				}
			}
		}
	}//end get_states()


	public function get_countries() {
		return WC()->countries->get_countries();
	}//end get_countries()


	public function get_user_roles() {
		global $wp_roles;
		$roles_names          = $wp_roles->get_names();
		$roles_names['guest'] = __( 'Guest', 'advanced-shipping' );
		return $roles_names;
	}//end get_user_roles()


	public function get_stock_statuses() {
		return array(
			'in-stock'     => __( 'In Stock', 'advanced-shipping' ),
			'out-of-stock' => __( 'Out of Stock', 'advanced-shipping' ),
			'backorder'    => __( 'Backorder', 'advanced-shipping' ),
		);
	}//end get_stock_statuses()
}//end class
