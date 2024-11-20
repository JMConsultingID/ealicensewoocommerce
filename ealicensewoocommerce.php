<?php
/**
 * @link              https://yourrobotrader.com
 * @since             1.1.1.0
 * @package           ealicensewoocommerce
 * GitHub Plugin URI: https://github.com/JMConsultingID/ealicensewoocommerce
 * @wordpress-plugin
 * Plugin Name:       EA License Woocommerce
 * Plugin URI:        https://yourrobotrader.com
 * Description:       A plugin to connect WooCommerce with EA License API.
 * Version:           1.1.1.0
 * Author:            YourRoboTrader Team
 * Author URI:        https://yourrobotrader.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ealicensewoocommerce
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define( 'EALICENSE_VERSION', '1.1.1.0' );

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . '/wp-admin/includes/plugin.php');
}

// Hook into WooCommerce's 'init' action to remove the default email action early enough
add_action('init', 'ealicensewoocommerce_customizations', 20);

function ealicensewoocommerce_customizations() {
    // Access WooCommerce email classes
    $email_class = WC()->mailer();

    // Remove WooCommerce's default "Completed Order" email action
    remove_action('woocommerce_order_status_completed_notification', array($email_class->emails['WC_Email_Customer_Completed_Order'], 'trigger'));

    // Add your custom hooks or logic here
    add_action('woocommerce_order_status_changed', 'ealicensewoocommerce_send_api_on_order_status_change', 10, 4);
}

// function disable_completed_order_email($enabled, $email_id, $order) {
//     if ($email_id === 'customer_completed_order' && $order->get_status() === 'completed') {
//         return false;
//     }
//     return $enabled;
// }
// add_filter('woocommerce_email_enabled_customer_completed_order', 'disable_completed_order_email', 10, 3);

require plugin_dir_path( __FILE__ ) . 'inc/ealicensewoocommerce_functions.php';
