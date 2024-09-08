<?php
/**
 * Plugin functions and definitions for My Licenses.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */

// Add "My License" to the My Account menu
function ealicensewoocommerce_add_my_license_menu_item($items) {
    // Insert the new menu item after the "Orders" tab (or wherever you want it)
    $new_items = array_slice($items, 0, 1, true) +
                 array('my-licenses' => __('My License', 'ealicensewoocommerce')) +
                 array_slice($items, 1, null, true);
    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'ealicensewoocommerce_add_my_license_menu_item');

// Add the custom endpoint for My License
function ealicensewoocommerce_add_my_license_endpoint() {
    add_rewrite_endpoint('my-licenses', EP_PAGES);
}
add_action('init', 'ealicensewoocommerce_add_my_license_endpoint');

// Display the content for the My License menu
function ealicensewoocommerce_my_license_content() {
    $user_id = get_current_user_id();
    $licenses = get_user_licenses($user_id); // Custom function to fetch licenses associated with user

    if (!empty($licenses)) {
        echo '<h2>' . __('My Licenses', 'ealicensewoocommerce') . '</h2>';
        echo '<table class="shop_table shop_table_responsive my_account_orders">';
        echo '<thead><tr><th>' . __('License Key', 'ealicensewoocommerce') . '</th><th>' . __('Status', 'ealicensewoocommerce') . '</th><th>' . __('Expiration Date', 'ealicensewoocommerce') . '</th></tr></thead>';
        echo '<tbody>';

        foreach ($licenses as $license) {
            echo '<tr>';
            echo '<td>' . esc_html($license['license_key']) . '</td>';
            echo '<td>' . esc_html($license['status']) . '</td>';
            echo '<td>' . esc_html($license['expiration_date']) . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>' . __('You have no licenses.', 'ealicensewoocommerce') . '</p>';
    }
}
add_action('woocommerce_account_my-licenses_endpoint', 'ealicensewoocommerce_my_license_content');

// Flush rewrite rules on activation
function ealicensewoocommerce_flush_rewrite_rules() {
    ealicensewoocommerce_add_my_license_endpoint();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ealicensewoocommerce_flush_rewrite_rules');
