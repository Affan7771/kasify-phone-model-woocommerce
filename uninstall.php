<?php 

// If this file is called directly, abort. //
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit();
global $wpdb;

/****************************
* Delete options
*****************************/


/****************************
* Delete custom created table
*****************************/
$table_brands = $wpdb->prefix . 'kasify_phone_brands';
$table_models = $wpdb->prefix . 'kasify_phone_models';

$wpdb->query( "DROP TABLE IF EXISTS $table_brands" );
$wpdb->query( "DROP TABLE IF EXISTS $table_models" );

?>