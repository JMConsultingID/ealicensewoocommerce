<?php
/**
 * Plugin functions and definitions for Admin.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
add_filter( 'woocommerce_add_to_cart_redirect', 'ealicensewoocommerce_add_to_cart_redirect');
add_filter( 'wc_add_to_cart_message_html', '__return_false' );
add_filter( 'woocommerce_add_cart_item_data', '_empty_cart' );
add_filter( 'woocommerce_adjust_non_base_location_prices', '__return_false' );
add_filter('woocommerce_enable_order_notes_field', '__return_false');
add_filter( 'woocommerce_checkout_fields' , 'ealicensewoocommerce_modify_woocommerce_billing_fields' );

function ealicensewoocommerce_modify_woocommerce_billing_fields( $fields ) {
    $fields['billing']['billing_email']['priority'] = 5;
    return $fields;
}

function _empty_cart( $cart_item_data ) {
    WC()->cart->empty_cart();
    return $cart_item_data;
}

function ealicensewoocommerce_add_to_cart_redirect() {
    return wc_get_checkout_url();
}

// Display custom fields in the WooCommerce admin order details
function ealicensewoocommerce_display_admin_order_meta($order) {
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }
    
    echo '<p><strong>' . __('Account ID') . ':</strong> ' . get_post_meta($order->get_id(), '_yrt_license_account_number', true) . '</p>';
    echo '<p><strong>' . __('License Key') . ':</strong> ' . get_post_meta($order->get_id(), '_yrt_license_license_key', true) . '</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address', 'ealicensewoocommerce_display_admin_order_meta', 10, 1);


// Display Account ID and License Key on the order received (thank you) page only if the order is completed
function ealicensewoocommerce_display_license_info_on_thank_you_page($order_id) {
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }

    // Get the order object
    $order = wc_get_order($order_id);
    
    // Check if the order exists and if the status is 'completed'
    if ($order && $order->get_status() === 'completed') {
        $account_id = get_post_meta($order_id, '_yrt_license_account_number', true);
        $license_key = get_post_meta($order_id, '_yrt_license_license_key', true);

        if ($account_id && $license_key) {
            echo '<h2>' . __('Your License Details', 'ealicensewoocommerce') . '</h2>';
            echo '<p><strong>' . __('Account ID', 'ealicensewoocommerce') . ':</strong> ' . esc_html($account_id) . '</p>';
            echo '<p><strong>' . __('License Key', 'ealicensewoocommerce') . ':</strong> ' . esc_html($license_key) . '</p>';
        }
    }
}
add_action('woocommerce_thankyou', 'ealicensewoocommerce_display_license_info_on_thank_you_page');