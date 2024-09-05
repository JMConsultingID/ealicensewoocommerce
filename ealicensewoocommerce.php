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

require plugin_dir_path( __FILE__ ) . 'inc/ealicensewoocommerce_functions.php';
remove_action('woocommerce_order_status_completed', array('WC_Emails', 'send_transactional_email'), 10, 1);
