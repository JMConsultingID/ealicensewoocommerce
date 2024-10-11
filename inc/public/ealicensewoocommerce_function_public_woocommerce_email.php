<?php
/**
 * Plugin functions and definitions for Email User.
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
        sleep(2);
        $order_id = $order->get_id();

        // Retrieve the license key from order meta
        $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
        $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
        $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);
        $user_email = get_post_meta($order->get_id(), '_ealicensewoocommerce_email', true);

        if ($license_key) {
            if ($plain_text) {
                // Plain text email format
                echo "Email: " . $user_email . "\n";
                echo "License Key: " . $license_key . "\n";
                echo "Account Limit: " . $account_quota . " Accounts\n";
                echo "License Expiration: " . $license_expiration . "\n";
            } else {
                // HTML email format
                echo '<p><strong>' . __('User Email:', 'ealicensewoocommerce') . '</strong> ' . esc_html($user_email) . '</p>';
                echo '<p><strong>' . __('License Key:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_key) . '</p>';
                echo '<p><strong>' . __('Account Limit:', 'ealicensewoocommerce') . '</strong> ' . esc_html($account_quota) . ' Accounts</p>';
                echo '<p><strong>' . __('License Expiration:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_expiration) . '</p>';
            }
        }
    }
}
add_action('woocommerce_email_order_meta', 'ealicensewoocommerce_add_license_info_to_email', 10, 4);