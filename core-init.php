<?php
if ( ! defined( 'ABSPATH' ) ) {die;} // end if

// Define Constants
define( 'WPKPM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPKPM_PLUGIN_URL', plugins_url('/', __FILE__) );
define( 'WPKPM_VERSION', time() );

/****************************
* Plugin activation function
*****************************/
function kpm_plugin_activation(){
	include( WPKPM_PLUGIN_PATH . 'inc/activation.php');
}

/*****************************
* Plugin deactivation function
*****************************/
function kpm_plugin_deactivation(){
	include( WPKPM_PLUGIN_PATH . 'inc/deactivation.php');
}

/*****************************
* Plugin init function
*****************************/
function wpkpm_init(){
	include( WPKPM_PLUGIN_PATH . 'inc/functions.php');
	include( WPKPM_PLUGIN_PATH . 'admin/admin.php');
	include( WPKPM_PLUGIN_PATH . 'public/public.php');
}
add_action('init','wpkpm_init');

?>