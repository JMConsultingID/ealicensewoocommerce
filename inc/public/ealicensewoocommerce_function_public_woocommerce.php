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

    // Retrieve the license key from order meta
    $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
    $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
    $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);
    
    echo '<p><strong>' . __('License Key') . ':</strong> ' . esc_html($license_key) . '</p>';
    echo '<p><strong>' . __('Account Limit') . ':</strong> ' . esc_html($account_quota) . ' Accounts</p>';
    echo '<p><strong>' . __('License Expiration') . ':</strong> ' . esc_html($license_expiration) . '</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address', 'ealicensewoocommerce_display_admin_order_meta', 10, 1);

add_action('woocommerce_thankyou', 'ealicensewoocommerce_display_license_info_on_thank_you_page');
// Display Account ID and License Key on the order received (thank you) page only if the order is completed
function ealicensewoocommerce_display_license_info_on_thank_you_page($order_id) {
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }

    // Get the order object
    $order = wc_get_order($order_id);
    
    // Check if the order exists and if the status is 'completed'
    if ($order && $order->get_status() === 'completed') {
        // Retrieve the license key from order meta
        $user_email = get_post_meta($order->get_id(), '_ealicensewoocommerce_email', true);
        $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
        $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
        $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);

        if ($license_key) {
            echo '<h2>' . __('Your License Details', 'ealicensewoocommerce') . '</h2>';
            echo '<p><strong>' . __('User Email:', 'ealicensewoocommerce') . '</strong> ' . esc_html($user_email) . '</p>';
            echo '<p><strong>' . __('License Key', 'ealicensewoocommerce') . ':</strong> ' . esc_html($license_key) . '</p>';
            echo '<p><strong>' . __('Account Limit', 'ealicensewoocommerce') . ':</strong> ' . esc_html($account_quota) . ' Accounts</p>';
            echo '<p><strong>' . __('License Expiration', 'ealicensewoocommerce') . ':</strong> ' . esc_html($license_expiration) . '</p>';
        }
    }
}

add_action('woocommerce_thankyou', 'ealicensewoocommerce_create_user_account_after_payment', 10, 1);

function ealicensewoocommerce_create_user_account_after_payment( $order_id ) {
    // If user is logged in, do nothing because they already have an account
    if( is_user_logged_in() ) return;

    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }

    // Get the newly created order
    $order = wc_get_order( $order_id );

    // Get the billing email address
    $order_email = $order->billing_email;

    // Check if there are any users with the billing email as user or email
    $email = email_exists( $order_email );
    $user = username_exists( $order_email );

    // Get the order status (see if the customer has paid)
    $order_status = $order->get_status();

    // Check if the user exists and if the order status is processing or completed (paid)
    if( $user == false && $email == false && $order->has_status( 'processing' ) || $user == false && $email == false && $order->has_status( 'completed' ) ) {
        // Check on category ( multiple categories can be entered, separated by a comma )

            // Random password with 12 chars
            $random_password = wp_generate_password();

            // Firstname
            $first_name = $order->get_billing_first_name();

            // Lastname
            $last_name = $order->get_billing_last_name();

            // Role
            $role = 'customer';

            // Create new user with email as username, newly created password and user role
            $user_id = wp_insert_user(
                array(
                    'user_email' => $order_email,
                    'user_login' => $order_email,
                    'user_pass'  => $random_password,
                    'first_name' => $first_name,
                    'last_name'  => $last_name,
                    'role'       => $role,
                )
            );

            // (Optional) WC guest customer identification
            update_user_meta( $user_id, 'guest', 'yes' );

            // User's billing data
            update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
            update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
            update_user_meta( $user_id, 'billing_city', $order->billing_city );
            update_user_meta( $user_id, 'billing_company', $order->billing_company );
            update_user_meta( $user_id, 'billing_country', $order->billing_country );
            update_user_meta( $user_id, 'billing_state', $order->billing_state );
            update_user_meta( $user_id, 'billing_email', $order->billing_email );
            update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
            update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
            update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
            update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );

            // User's shipping data
            update_user_meta( $user_id, 'shipping_address_1', $order->billing_address_1);
            update_user_meta( $user_id, 'shipping_address_2', $order->billing_address_2 );
            update_user_meta( $user_id, 'shipping_city', $order->billing_city );
            update_user_meta( $user_id, 'shipping_company', $order->billing_company );
            update_user_meta( $user_id, 'shipping_state', $order->billing_state );
            update_user_meta( $user_id, 'shipping_country', $order->billing_country);
            update_user_meta( $user_id, 'shipping_first_name', $order->billing_first_name  );
            update_user_meta( $user_id, 'shipping_last_name', $order->billing_last_name );
            update_user_meta( $user_id, 'shipping_postcode', $order->billing_postcode );

            // Link past orders to this newly created customer
            wc_update_new_customer_past_orders( $user_id );

    }
}