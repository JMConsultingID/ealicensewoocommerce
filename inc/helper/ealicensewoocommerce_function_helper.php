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

// Add License Key, and a download link to the order completed email
function ealicensewoocommerce_send_license_order_email($order, $license_key, $account_quota, $license_expiration) {
    $email_heading = 'Your License Key is Ready';
    $email_content = '<p>Thank you for your order!</p>';
    $email_content .= '<p><strong>License Key:</strong> ' . esc_html($license_key) . '</p>';
    $email_content .= '<p><strong>Account Limit:</strong> ' . esc_html($account_quota) . ' Accounts</p>';
    $email_content .= '<p><strong>License Expiration:</strong> ' . esc_html($license_expiration) . '</p>';
    $email_content .= '<p><a href="https://eastaging.yourrobotrader.com/wp-content/uploads/2024/08/Software_Box_Mockup_robotrader-shadow-web-e1662613546511.png" target="_blank">Download your file here</a></p>';

    // Send the email using WooCommerce email functions
    $mailer = WC()->mailer();
    $email = new WC_Email();

    $email->send($order->get_billing_email(), $email_heading, $email_content, $mailer->get_headers(), $mailer->get_attachments());
}
