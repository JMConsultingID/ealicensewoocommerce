<?php
/**
 * Plugin functions and definitions for Admin.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */

// Send data to API when order status changes to 'completed'
function ealicensewoocommerce_send_api_on_order_status_change($order_id, $old_status, $new_status, $order) {
    // Check if feature is enabled
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if feature is not enabled
    }

    // Get API settings
    $api_base_endpoint = get_option('ealicensewoocommerce_api_base_endpoint_url');
    $api_authorization_key = get_option('ealicensewoocommerce_api_authorization_key');
    $api_version = get_option('ealicensewoocommerce_api_version', 'v1');

    // Construct API endpoint
    $api_endpoint = trailingslashit($api_base_endpoint) . $api_version . '/order-completed/';

    if ($new_status == 'completed' && !empty($api_base_endpoint) && !empty($api_authorization_key)) {
        // Initialize logger
        $logger_info = ealicensewoocommerce_connection_response_logger();
        $logger = $logger_info['logger'];
        $context = $logger_info['context'];

        // Log API endpoint and authorization
        $logger->info('API Endpoint: ' . $api_endpoint, $context);
        $logger->info('Authorization Header: Bearer ' . $api_authorization_key, $context);

        // Collect 'source' info
        $ip_user = $_SERVER['REMOTE_ADDR'];
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $domain = $_SERVER['HTTP_HOST'];

        // Prepare source array and log it
        $source = array('ip_user' => $ip_user, 'browser' => $browser, 'domain' => $domain);
        $logger->info('Source Information: ' . json_encode($source), $context);

        // Prepare API request data and log it
        $data = array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'total_purchase' => $total_purchase,
                'currency' => strtolower($currency),
                'account_quota' => $account_quota,
                'license_expiration' => $license_expiration,
                'language' => $language,
                'source' => json_encode($source),
                'billing' => array(
                    'email' => $order->get_billing_email(),
                    'first_name' => $order->get_billing_first_name(),
                    'last_name' => $order->get_billing_last_name(),
                    'country' => $order->get_billing_country(),
                    'state' => $order->get_billing_state(),
                    'city' => $order->get_billing_city(),
                    'address' => $order->get_billing_address_1(),
                    'postcode' => $order->get_billing_postcode(),
                    'phone' => $order->get_billing_phone(),
                )
            );
        $logger->info('Data to send: ' . json_encode($data), $context);

        // Send API request and log request
        $response = wp_remote_post($api_endpoint, array(
            'method'    => 'POST',
            'body'      => json_encode($data),
            'headers'   => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_authorization_key
            ),
        ));
        $logger->info('API Request sent to ' . $api_endpoint, $context);

        // Handle API response and log response or errors
        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            $logger->error('API Error: ' . $error_message, $context);
        } else {
            $response_body = wp_remote_retrieve_body($response);
            $logger->info('API Response: ' . $response_body, $context);
        }
    }
}
add_action('woocommerce_order_status_changed', 'ealicensewoocommerce_send_api_on_order_status_change', 10, 4);
