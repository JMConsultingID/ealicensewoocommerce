<?php
/**
 * Plugin functions and definitions for Admin.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
// Function to check if YRT EA License feature is enabled
function ealicensewoocommerce_is_license_enabled() {
    $ealicensewoocommerce_enable_license = get_option('ealicensewoocommerce_enable_license');
    return !empty($ealicensewoocommerce_enable_license);
}


// Function to initialize the logger
function ealicensewoocommerce_connection_response_logger() {
    $logger = wc_get_logger();
    $context = array('source' => 'ealicense_connection_response_log');
    return array('logger' => $logger, 'context' => $context);
}

function ealicensewoocommerce_add_license_info_to_email_order($order) {
    $order_id = $order->get_id();
    $download_url = 'https://eastaging.yourrobotrader.com/wp-content/uploads/2024/08/Software_Box_Mockup_robotrader-shadow-web-e1662613546511.png';

    // Retrieve the license key from order meta
    $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
    $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
    $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);

    if ($license_key) {
        // Add the license key and download link to the email content
        add_filter('woocommerce_email_order_meta_fields', function ($fields, $sent_to_admin, $order) use ($license_key, $download_url) {
            $fields['license_key'] = array(
                'label' => __('License Key', 'ealicensewoocommerce'),
                'value' => $license_key,
            );
            $fields['account_quota'] = array(
                'label' => __('Account Limit', 'ealicensewoocommerce'),
                'value' => $account_quota,
            );
            $fields['license_key'] = array(
                'label' => __('License Expiration', 'ealicensewoocommerce'),
                'value' => $license_expiration,
            );
            $fields['download_link'] = array(
                'label' => __('Download your file here', 'ealicensewoocommerce'),
                'value' => '<a href="' . esc_url($download_url) . '" target="_blank">' . __('Download your file here', 'ealicensewoocommerce') . '</a>',
            );
            return $fields;
        }, 10, 3);
    }
}
add_action('ealicense_after_license_stored', 'ealicensewoocommerce_add_license_info_to_email_order', 10, 1);