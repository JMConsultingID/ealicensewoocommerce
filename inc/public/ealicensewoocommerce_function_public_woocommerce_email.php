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
    if ($email->id === 'customer_completed_order') {
        $order_id = $order->get_id();
        $download_url = 'https://eastaging.yourrobotrader.com/wp-content/uploads/2024/08/Software_Box_Mockup_robotrader-shadow-web-e1662613546511.png';

        // Retrieve the license key from order meta
        $license_key = get_post_meta($order_id, '_ealicensewoocommerce_license_key', true);
        $account_quota = get_post_meta($order_id, '_ealicensewoocommerce_account_quota', true);
        $license_expiration = get_post_meta($order_id, '_ealicensewoocommerce_license_expiration', true);

        if ($license_key) {
            if ($plain_text) {
                // Plain text email format
                echo "License Key: " . $license_key . "\n";
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
add_action('woocommerce_email_order_meta', 'ealicensewoocommerce_add_license_info_to_email', 10, 4);

echo '<p><strong>' . __('License Key') . ':</strong> ' . $license_key . '</p>';
    echo '<p><strong>' . __('Account Limit') . ':</strong> ' . $account_quota . '</p>';
    echo '<p><strong>' . __('License Expiration') . ':</strong> ' . $license_expiration . '</p>';