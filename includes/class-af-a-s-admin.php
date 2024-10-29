<?php

defined( 'ABSPATH' ) || exit;

class AF_A_S_Admin {

	public function __construct() {

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 100 );

		// Meta boxes
		add_action( 'add_meta_boxes', array( $this, 'add_shipping_metaboxes' ) );

		add_action( 'save_post_af_ship_rule', array( $this, 'save_shippping_rule_meta' ), 100, 1 );

		add_filter( 'manage_af_reg_form_posts_columns', array( $this, 'show_form_columns' ), 100, 1 );

		add_action( 'manage_af_reg_form_posts_custom_column', array( $this, 'show_coloumn_content' ), 100, 1 );

		add_action( 'admin_init', array( $this, 'add_shipping_hooks' ) );

		add_action( 'woocommerce_settings_save_shipping', array( $this, 'woocommerce_settings_save' ), 100 );

		add_action( 'woocommerce_shipping_zone_before_methods_table', array( $this, 'show_rules_with_shipping_methods' ) );
	}//end __construct()


	public function enqueue_scripts() {

		wp_enqueue_style( 'afads_admin', AFADS_URL . 'assets/css/admin.css', array(), '1.0.0' );

		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'afads_admin_js', AFADS_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-tiptip' ), '1.0.0', true );

		wp_enqueue_style( 'select2', plugins_url( 'assets/css/select2.css', WC_PLUGIN_FILE ), array(), '5.7.2' );
		wp_enqueue_script( 'select2', plugins_url( 'assets/js/select2/select2.min.js', WC_PLUGIN_FILE ), array( 'jquery' ), '4.0.3', true );

		$data = $this->get_localize_data_pricing();
		wp_localize_script( 'afads_admin_js', 'afads_php_vars', $data );

		$data = $this->get_localize_data_conditions();
		wp_localize_script( 'afads_admin_js', 'afads_condition_var', $data );
	}//end enqueue_scripts()


	public function add_shipping_hooks() {
		
		$nonce = isset( $_GET['afads_ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['afads_ajax_nonce'] ) ) : 0;
		if (isset( $_GET['afads_ajax_nonce'] )  && ! wp_verify_nonce( $nonce, 'afads_ajax_nonce' ) ) {
			wp_die( esc_html__( 'Security Violated!', 'advanced-shipping' ) );
		}

		$page        = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 0;
		$tab         = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 0;
		$instance_id = isset( $_GET['instance_id'] ) ? sanitize_text_field( wp_unslash( $_GET['instance_id'] ) ) : 0;
		$instance_id = (int) $instance_id;
		if ( 'wc-settings' == $page && 'shipping' == $tab && ! empty( $instance_id ) ) {

			foreach ((array) wc()->shipping->get_shipping_methods() as $shipping_id => $shipping_class ) {

				add_filter( 'woocommerce_shipping_instance_form_fields_' . $shipping_id, array( $this, 'woocommerce_shipping_instance_form_fields_' ), 100, 1 );

			}

			add_action( 'woocommerce_settings_' . $tab, array( $this, 'woocommerce_settings_' ), 100 );

		}
	}//end add_shipping_hooks()


	public function woocommerce_settings_() {
		include_once AFADS_PLUGIN_DIR . 'includes/admin/settings/shipping/zones/instance-html.php';
	}//end woocommerce_settings_()


	public function woocommerce_settings_save() {
		
		$nonce = isset( $_GET['afads_ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['afads_ajax_nonce'] ) ) : 0;
		if (isset( $_GET['afads_ajax_nonce'] )  && ! wp_verify_nonce( $nonce, 'afads_ajax_nonce' ) ) {
			wp_die( esc_html__( 'Security Violated!', 'advanced-shipping' ) );
		}
		$instance_id = isset( $_GET['instance_id'] ) ? sanitize_text_field( wp_unslash( $_GET['instance_id'] ) ) : 0;
		$instance_id = (int) $instance_id;
		$aurgs       = array(
			'post_type'   => 'af_ship_rule_alt',
			'post_status' => 'publish',
		);

		$post_id = afads_get_instance_post_id( $instance_id ) ? afads_get_instance_post_id( $instance_id ) : wp_insert_post( $aurgs );

		update_post_meta( $post_id, 'afads_instance_id', $instance_id );

		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/save-meta.php';
	}//end woocommerce_settings_save()


	public function woocommerce_shipping_instance_form_fields_( $fields ) {

		return array();
	}//end woocommerce_shipping_instance_form_fields_()


	public function get_localize_data_conditions() {

		return array(
			'all_operators_fields'   => afads_get_values_for_all_operators(),
			'custom_dropdown_fields' => afads_get_custom_dropdown_fields(),
			'conditions_dropdown'    => afads_get_conditions_dropdown(),
			'operators_dropdown'     => afads_get_operators_dropdown(),
			'input_field_text'       => afads_get_text_input(),
			'input_field_number'     => afads_get_number_input(),
		);
	}//end get_localize_data_conditions()


	public function get_localize_data_pricing() {

		$min_qunatity = 0.0;
		$max_qunatity = 0.0;
		$fee          = 0.0;

		ob_start();
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/new/product.php';
		$product_row = ob_get_clean();
		ob_start();
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/new/category.php';
		$category_row = ob_get_clean();
		ob_start();
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/new/weight.php';
		$weight_row = ob_get_clean();
		ob_start();
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing/new/shipping-class.php';
		$shipping_row = ob_get_clean();
		ob_start();
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/conditions/new/condition-rule.php';
		$condition_row = ob_get_clean();
		ob_start();
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/conditions/new/conditions-group.php';
		$condition_group = ob_get_clean();

		return array(
			'admin_url'       => admin_url( 'admin-ajax.php' ),
			'nonce'           => wp_create_nonce( 'afads-ajax-nonce' ),
			'product_row'     => $product_row,
			'category_row'    => $category_row,
			'weight_row'      => $weight_row,
			'shipping_row'    => $shipping_row,
			'condition_row'   => $condition_row,
			'condition_group' => $condition_group,
		);
	}//end get_localize_data_pricing()


	public function save_shippping_rule_meta( $post_id ) {

		// For custom post type:
		$exclude_statuses = array(
			'auto-draft',
			'trash',
		);

		$nonce = isset( $_GET['afads_ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['afads_ajax_nonce'] ) ) : 0;
		if (isset( $_GET['afads_ajax_nonce'] )  && ! wp_verify_nonce( $nonce, 'afads_ajax_nonce' ) ) {
			wp_die( esc_html__( 'Security Violated!', 'advanced-shipping' ) );
		}

		$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';

		if ( ! current_user_can( 'edit_post', $post_id ) || in_array( get_post_status( $post_id ), $exclude_statuses ) || is_ajax() || 'untrash' === $action ) {
			return;
		}

		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/save-meta.php';
	}//end save_shippping_rule_meta()


	public function add_shipping_metaboxes() {

		add_meta_box(
			'af_ship_rule_settings',
			__( 'Shipping Settings', 'advanced-shipping' ),
			array( $this, 'add_ship_rule_settings' ),
			'af_ship_rule',
			'advanced'
		);

		add_meta_box(
			'af_ship_rule_fields',
			__( 'Shipping conditions', 'advanced-shipping' ),
			array( $this, 'add_ship_rule_conditions' ),
			'af_ship_rule',
			'advanced'
		);

		add_meta_box(
			'af_ship_rule_pricing',
			__( 'Shipping Pricing', 'advanced-shipping' ),
			array( $this, 'add_ship_rule_pricing' ),
			'af_ship_rule',
			'advanced'
		);
	}//end add_shipping_metaboxes()


	public function add_ship_rule_conditions() {
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/conditions.php';
	}//end add_ship_rule_conditions()


	public function add_ship_rule_settings() {
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/settings.php';
	}//end add_ship_rule_settings()


	public function add_ship_rule_pricing() {
		include_once AFADS_PLUGIN_DIR . 'includes/admin/meta-boxes/shipping/pricing.php';
	}//end add_ship_rule_pricing()

	public function show_rules_with_shipping_methods( $zone ) {

		ob_start();

		$continents = array();
		$countries  = array();

		$zone_locations = $zone->get_zone_locations();
		foreach ((array) $zone_locations as $zone_object ) {

			if ( is_object( $zone_object ) && isset( $zone_object->type ) && 'continent' == $zone_object->type ) {
				$continents[] = $zone_object->code;
			}

			if ( is_object( $zone_object ) && isset( $zone_object->type ) && 'country' == $zone_object->type ) {
				$countries[] = $zone_object->code;
			}
		}

		$post_ids = get_posts(
			array(
				'post_type'      => 'af_ship_rule',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		foreach ( $post_ids as $current_post_id ) {

			$condition_type        = (array) get_post_meta( $current_post_id, 'afads_condition_type', true );
			$afads_condition_value = (array) get_post_meta( $current_post_id, 'afads_condition_value', true );
			foreach ( $condition_type as $parent_arr_key => $child ) {

				foreach ((array) $child as $key => $value ) {

					if ( 'country' == $value && isset( $afads_condition_value[ $parent_arr_key ][ $key ] ) ) {

						$country      = $afads_condition_value[ $parent_arr_key ][ $key ];
						$WC_Countries = new WC_Countries();
						$continent    = $WC_Countries->get_continent_code_for_country( $country );

						if ( ( count( $continents ) >= 1 && ! in_array( $continent, $continents ) ) || ( count( $continents ) >= 1 && ! in_array( $country, $countries ) ) ) {
							continue 3;
						}
					}
				}
			}

			?>

			<tr data-id="3" class="af-cs-custom-tr" data-enabled="yes">
				<td width="1%" class="wc-shipping-zone-method-sort"></td>
				<td class="wc-shipping-zone-method-title">
					<a class="" href="<?php echo esc_url( get_edit_post_link( $current_post_id ) ); ?>"> 
						<?php echo esc_attr( get_the_title( $current_post_id ) ); ?>
					</a>
					<div class="row-actions">
						<a class="" href="<?php echo esc_url( get_edit_post_link( $current_post_id ) ); ?>">Edit</a>
					</div>
				</td>
				<td width="1%" class="wc-shipping-zone-method-enabled"><a href="#"><span class="woocommerce-input-toggle woocommerce-input-toggle--enabled">Yes</span></a></td>
				<td class="wc-shipping-zone-method-description">
					<strong class="wc-shipping-zone-method-type"><?php echo esc_attr( get_the_title( $current_post_id ) ); ?></strong>

				</td>
			</tr>

			<?php

		}

		$result = ob_get_clean();
		?>
		<script type="text/javascript">
			var af_a_s_method_zone = <?php echo wp_json_encode( $result ); ?>;
			jQuery(document).ready(function($){

				setTimeout(function() {
					jQuery('tbody.wc-shipping-zone-method-rows').append(af_a_s_method_zone);

				},3000);
			});
		</script>
		<?php
	}
}//end class


new AF_A_S_Admin();
