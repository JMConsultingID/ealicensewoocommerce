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

function ealicensewoocommerce_display_licenses_by_email() {
    // Get the current user's email address
    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    // Get the API base endpoint URL, Authorization Key, and API Version from settings
    $api_base_endpoint = get_option('ealicensewoocommerce_api_base_endpoint_url');
    $api_authorization_key = get_option('ealicensewoocommerce_api_authorization_key');
    $api_version = get_option('ealicensewoocommerce_api_version', 'v1'); // Default to 'v1' if not set

    // Construct the API endpoint
    $api_endpoint = trailingslashit($api_base_endpoint) . $api_version . '/licenses/email';

    // Setup the POST request to send the email in the body
    $response = wp_remote_post($api_endpoint, array(
        'headers' => array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_authorization_key,
        ),
        'body' => json_encode(array('email' => $email)), // Send the email in the POST request body
    ));

    // Check for errors in the API response
    if (is_wp_error($response)) {
        echo '<p>' . __('Error fetching licenses', 'ealicensewoocommerce') . '</p>';
        return;
    }

    // Decode the JSON response
    $body = wp_remote_retrieve_body($response);
    $licenses = json_decode($body, true);

    if (empty($licenses)) {
        echo '<p>' . __('No licenses found for this email.', 'ealicensewoocommerce') . '</p>';
        return;
    }

    // Display the licenses in a table
    echo '<table class="shop_table shop_table_responsive my_account_orders">';
    echo '<thead><tr><th>' . __('Order ID', 'ealicensewoocommerce') . '</th><th>' . __('License', 'ealicensewoocommerce') . '</th><th>' . __('Account Quota', 'ealicensewoocommerce') . '</th><th>' . __('Used Quota', 'ealicensewoocommerce') . '</th><th>' . __('Expiration', 'ealicensewoocommerce') . '</th><th>' . __('Expiration Date', 'ealicensewoocommerce') . '</th><th>' . __('Status', 'ealicensewoocommerce') . '</th></tr></thead>';
    echo '<tbody>';

    foreach ($licenses as $license) {
        echo '<tr>';
        echo '<td>' . esc_html($license['order_id']) . '</td>';
        echo '<td>' . esc_html($license['license_key']) . '</td>';        
        echo '<td>' . esc_html($license['account_quota']) . '</td>';
        echo '<td>' . esc_html($license['used_quota']) . '</td>';
        echo '<td>' . esc_html($license['license_expiration']) . '</td>';
        echo '<td>' . esc_html($license['license_expiration_date'] ? date('Y-m-d', strtotime($license['license_expiration_date'])) : 'Lifetime') . '</td>';
        echo '<td>' . esc_html($license['status']) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}



add_action('woocommerce_account_my-licenses_endpoint', 'ealicensewoocommerce_display_licenses_by_email');

// Flush rewrite rules on activation
function ealicensewoocommerce_flush_rewrite_rules() {
    ealicensewoocommerce_add_my_license_endpoint();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ealicensewoocommerce_flush_rewrite_rules');
