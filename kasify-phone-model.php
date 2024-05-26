<?php
/**
* Plugin Name: Kasify Phone Model For Woocommerce
* Description: Add phone model for different phone back cover
* Version: 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {die;} // end if

// Check if WooCommerce is activated
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

	/*
	* Plugin Activation and Deactivation hooks
	*/
	register_activation_hook( __FILE__ , 'kpm_plugin_activation' );
	register_deactivation_hook( __FILE__ , 'kpm_plugin_deactivation' );


	// Initialize Everything
	if ( file_exists( plugin_dir_path( __FILE__ ) . 'core-init.php' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'core-init.php' );
	}

} else {
    // WooCommerce is not activated, display a notice
    add_action('admin_notices', 'kpm_gift_activation_notice');

    function kpm_gift_activation_notice() {
        ?>
        <div class="error">
            <p><?php _e('Please install and activate WooCommerce to use the Kasify Phone Model plugin.', 'kpm'); ?></p>
        </div>
        <?php
    }
}
?>