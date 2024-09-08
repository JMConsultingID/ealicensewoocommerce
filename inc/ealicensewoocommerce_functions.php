<?php
/**
 * Plugin functions and definitions for Global.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
// Include admin functions
require dirname(__FILE__) . '/admin/ealicensewoocommerce_function_admin_menu.php';
require dirname(__FILE__) . '/admin/ealicensewoocommerce_function_admin_woocommerce_products.php';

// Include helper functions
require dirname(__FILE__) . '/helper/ealicensewoocommerce_function_helper.php';

// Include public functions
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce.php';
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce_api.php';
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce_email.php';

function ealicensewoocommerce_enqueue_admin_assets($hook_suffix) {
    // Register the CSS file
    wp_register_style(
        'ealicensewoocommerce-admin-style', 
        plugin_dir_url(__FILE__) . '../assets/admin/ealicensewoocommerce_function_admin_menu.css', 
        array(), 
        '1.0.0', 
        'all'
    );

    // Enqueue the registered styles and scripts
    wp_enqueue_style('ealicensewoocommerce-admin-style');

    wp_enqueue_script(
        'ealicensewoocommerce_function_admin_menu',
        plugins_url('../assets/admin/ealicensewoocommerce_function_admin_menu.js', __FILE__),
        array('jquery'), // Dependencies
        '1.0',           // Version
        true             // In the footer
    );

    // Pass necessary variables to JavaScript file
    wp_localize_script('ealicensewoocommerce_function_admin_menu', 'ealicensewoocommerce_params', array(
        'api_base_endpoint' => get_option('ealicensewoocommerce_api_base_endpoint_url'),
        'api_authorization_key' => get_option('ealicensewoocommerce_api_authorization_key')
    ));

}

// Hook into the 'admin_enqueue_scripts' action
add_action('admin_enqueue_scripts', 'ealicensewoocommerce_enqueue_admin_assets');
