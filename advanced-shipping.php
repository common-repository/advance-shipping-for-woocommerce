<?php
/**
 * Plugin Name: Advanced Shipping
 * Plugin URI:        https://cloudtechnologies.store/Invoice-payment-method-and-invoice-pdf
 * Description:       Allow your customers to select invoice payment method during checkout.
 * Version:           1.0.5
 * Author:            cloud Technologies
 * Developed By:      cloud Technologies
 * Author URI:        https://cloudtechnologies.store/
 * Support:           https://cloudtechnologies.store/
 * Domain Path:       /languages
 * TextDomain : advanced-shipping
 * WC requires at least: 3.0.9
 * WC tested up to: 8.*.*
 * Woo: 8518169:0d361fc1719d9e4fea2389399f7f6b5c
 *
*/

defined( 'ABSPATH' ) || exit;

// Check the installation of WooCommerce module if it is not a multi site.
if ( ! is_multisite() && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

	function afads_admin_notice() {

			// Deactivate the plugin.
		deactivate_plugins( __FILE__ );

		?>
		<div id="message" class="error">
			<p>
				<strong>
					<?php esc_html_e( 'Advanced Shipping for WooCommerce plugin is inactive. WooCommerce plugin must be active in order to activate it.', 'advanced-shipping' ); ?>
				</strong>
			</p>
		</div>
		<?php
	}//end afads_admin_notice()


	add_action( 'admin_notices', 'afads_admin_notice' );

} else {


	if ( ! defined( 'AFADS_URL' ) ) {
		define( 'AFADS_URL', plugin_dir_url( __FILE__ ) );
	}

	if ( ! defined( 'AFADS_PLUGIN_DIR' ) ) {
		define( 'AFADS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	// Include the main WooCommerce ultimate membership class.
	if ( ! class_exists( 'AF_A_S_Main', false ) ) {
		include_once AFADS_PLUGIN_DIR . 'includes/class-af-a-s-main.php';
	}
}
