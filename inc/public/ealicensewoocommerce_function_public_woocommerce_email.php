<?php
/**
 * Plugin functions and definitions for Admin.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
// Add License Key, and a download link to the order completed email
function ealicensewoocommerce_add_license_info_to_email($order, $sent_to_admin, $plain_text, $email) {
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }
    
    if ($email->id === 'customer_completed_order') {
        $order_id = $order->get_id();
        $download_url = 'https://eastaging.yourrobotrader.com/wp-content/uploads/2024/08/Software_Box_Mockup_robotrader-shadow-web-e1662613546511.png';

        // Retrieve the license key from order meta
        $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
        $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
        $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);

        // Initialize logger
        $logger_info = ealicensewoocommerce_connection_response_logger();
        $logger = $logger_info['logger'];
        $context = $logger_info['context'];

        // Log the start of the API request process
        $logger->info("==== Starting Email request Data : ". $order_id ." ====", $context);
        $logger->info('license_key ' . $license_key, $context);
        $logger->info('account_quota ' . $account_quota, $context);
        $logger->info('license_expiration ' . $license_expiration, $context);
        $logger->info("==== End Email request Data  ====", $context);


        if ($license_key) {
            if ($plain_text) {
                // Plain text email format
                echo "License Key: " . $license_key . "\n";
                echo "Account Limit: " . $account_quota . " Accounts\n";
                echo "License Expiration: " . $license_expiration . "\n";
                echo "Download your software here: " . esc_url($download_url) . "\n";
            } else {
                // HTML email format
                echo '<p><strong>' . __('License Key:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_key) . '</p>';
                echo '<p><strong>' . __('Account Limit:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_key) . ' Accounts</p>';
                echo '<p><strong>' . __('License Expiration:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_key) . '</p>';
                echo '<p><a href="' . esc_url($download_url) . '" target="_blank">' . __('Download your file here', 'ealicensewoocommerce') . '</a></p>';
            }
        }
    }
}
add_action('woocommerce_email_order_meta', 'ealicensewoocommerce_add_license_info_to_email', 20, 4);


function ealicensewoocommerce_add_license_info_to_admin_email($order, $sent_to_admin, $plain_text, $email) {
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }
    
    if ($sent_to_admin && $email->id === 'customer_completed_order') {
        $order_id = $order->get_id();
        $account_id = get_post_meta($order_id, '_yrt_license_account_number', true);
        $license_key = get_post_meta($order_id, '_yrt_license_license_key', true);
        $download_url = 'https://eastaging.yourrobotrader.com/wp-content/uploads/2024/08/Software_Box_Mockup_robotrader-shadow-web-e1662613546511.png';

        if ($plain_text) {
            echo "Account ID: " . $account_id . "\n";
            echo "License Key: " . $license_key . "\n";
            echo "Download your file here: " . $download_url . "\n";
        } else {
            echo '<h3>' . __('Your License Details') . '</h3>';
            echo '<p><strong>' . __('Account ID') . ':</strong> ' . esc_html($account_id) . '</p>';
            echo '<p><strong>' . __('License Key') . ':</strong> ' . esc_html($license_key) . '</p>';
            echo '<p><a href="' . esc_url($download_url) . '" target="_blank">' . __('Download your file here') . '</a></p>';
        }
        
    }
}
add_action('woocommerce_email_order_meta', 'ealicensewoocommerce_add_license_info_to_admin_email', 20, 4);