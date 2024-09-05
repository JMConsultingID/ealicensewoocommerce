<?php
/**
 * Plugin functions and definitions for Global.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
function disable_completed_order_email($enabled, $email_id, $order) {
    if ($email_id === 'customer_completed_order' && $order->get_status() === 'completed') {
        return false; // Disable the email
    }
    return $enabled;
}
add_filter('woocommerce_email_enabled_customer_completed_order', 'disable_completed_order_email', 10, 3);

// Include admin functions
require dirname(__FILE__) . '/admin/ealicensewoocommerce_function_admin_menu.php';
require dirname(__FILE__) . '/admin/ealicensewoocommerce_function_admin_woocommerce_products.php';

// Include helper functions
require dirname(__FILE__) . '/helper/ealicensewoocommerce_function_helper.php';

// Include public functions
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce.php';
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce_api.php';
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce_email.php';
