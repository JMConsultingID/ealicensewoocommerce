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
    // Use the reusable function to check if the feature is enabled
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }

    // Get the API base endpoint URL, Authorization Key, and API Version from settings
    $api_base_endpoint = get_option('ealicensewoocommerce_api_base_endpoint_url');
    $api_authorization_key = get_option('ealicensewoocommerce_api_authorization_key');
    $api_version = get_option('ealicensewoocommerce_api_version', 'v1'); // Default to 'v1' if not set

    // Construct the full API endpoint URL based on the base URL and version
    $api_endpoint = trailingslashit($api_base_endpoint) . $api_version . '/order-completed/';

    if ($new_status == 'completed' && !empty($api_base_endpoint) && !empty($api_authorization_key)) {

        // Initialize logger
        $logger_info = ealicensewoocommerce_connection_response_logger();
        $logger = $logger_info['logger'];
        $context = $logger_info['context'];

        // Log the start of the API request process
        $logger->info('Starting API request process for order ID: ' . $order_id, $context);
        $logger->info('API Endpoint: ' . $api_endpoint, $context);

        // Collect additional information for 'source'
        $ip_user = $_SERVER['REMOTE_ADDR'];
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $domain = $_SERVER['HTTP_HOST'];

        // Create an array with the additional source information
        $source = array(
            'ip_user' => $ip_user,
            'browser' => $browser,
            'domain' => $domain
        );

        // Log the collected source information
        $logger->info('Collected source information: ' . json_encode($source), $context);

        // Get order details
        $order = wc_get_order($order_id);
        $items = $order->get_items();
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $product_name = $item->get_name();
            $total_purchase = $order->get_total();
            $currency = $order->get_currency();
            $account_quota = get_post_meta($product_id, '_ealicensewoocommerce_account_quota', true);
            $license_expiration = get_post_meta($product_id, '_ealicensewoocommerce_license_expiration', true);
            $language = substr(get_locale(), 0, 2); // Example: 'en' for English

            // Prepare data to send to API
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

            // Log the data that will be sent to the API
            $logger->info('Prepared data to send to API: ' . json_encode($data), $context);

            // Send data to the API
            $response = wp_remote_post($api_endpoint, array(
                'method'    => 'POST',
                'body'      => json_encode($data),
                'headers'   => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $api_authorization_key, // Use the saved Authorization Key
                ),
            ));

            // Log the entire API request details including headers and body
            $logger->info('Sent API request to ' . $api_endpoint . ' with data: ' . json_encode($data), $context);

            // Handle API response
            if (is_wp_error($response)) {
                // Log error
                $error_message = $response->get_error_message();
                $logger->error('EA License API error: ' . $error_message, $context);
            } else {
                // Log success response
                $response_body = wp_remote_retrieve_body($response);
                $logger->info('EA License API response: ' . $response_body, $context);
            }
        }
    }
}
add_action('woocommerce_order_status_changed', 'ealicensewoocommerce_send_api_on_order_status_change', 10, 4);
