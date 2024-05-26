<?php
if (!defined('ABSPATH')) {
    die;
} // end if

/*****************************
 * Create custom tables
 *****************************/
global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

$table_brands = $wpdb->prefix . 'kasify_phone_brands';
$table_models = $wpdb->prefix . 'kasify_phone_models';

$sql = "
    CREATE TABLE $table_brands (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        brand_name varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;

    CREATE TABLE $table_models (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        brand_id mediumint(9) NOT NULL,
        model_name varchar(255) NOT NULL,
        PRIMARY KEY  (id),
        FOREIGN KEY (brand_id) REFERENCES $table_brands(id) ON DELETE CASCADE
    ) $charset_collate;
    ";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
