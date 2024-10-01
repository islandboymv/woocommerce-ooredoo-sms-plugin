<?php
/*
Plugin Name: WooCommerce Ooredoo SMS Plugin
Description: Sends an SMS when a WooCommerce order status changes using the Ooredoo SMS Gateway API.
Version: 3.1.4
Author: Mifzaal Abdul Bari (IslandBoyMv)
Author URI: https://islandboy.mv
License: MIT
License URI: https://opensource.org/licenses/MIT
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin path
define( 'WC_OOREDOO_SMS_PATH', plugin_dir_path( __FILE__ ) );

// Load classes
require_once WC_OOREDOO_SMS_PATH . 'includes/woo-ooredoo-sms.php';
require_once WC_OOREDOO_SMS_PATH . 'includes/woo-ooredoo-sms-admin.php';
require_once WC_OOREDOO_SMS_PATH . 'includes/woo-ooredoo-sms-api.php';

// Initialize the main plugin class
function woocommerce_ooredoo_sms_init() {
    $plugin = new WooOoredooSMS();
    $plugin->initialize();
}
add_action( 'plugins_loaded', 'woocommerce_ooredoo_sms_init' );
