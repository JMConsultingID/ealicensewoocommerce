<?php
/**
 * Plugin functions and definitions for Cart Woocommerce.
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

// Hook to WooCommerce 'Thank You' page
add_action('woocommerce_thankyou', 'ealicensewoocommerce_display_license_details_after_order');

function ealicensewoocommerce_display_license_details_after_order($order_id) {
    // Get the order object
    $order = wc_get_order($order_id);

    // Check if the order exists and the order status is 'completed'
    if ($order && $order->get_status() === 'completed') {
        // Retrieve license-related data from the order meta
        $user_email = get_post_meta($order->get_id(), '_ealicensewoocommerce_email', true);
        $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
        $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
        $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);

        // Display license details only if a license key exists
        if ($license_key) {
            echo '<h2>' . __('Your License Details', 'ealicensewoocommerce') . '</h2>';
            echo '<p><strong>' . __('User Email:', 'ealicensewoocommerce') . '</strong> ' . esc_html($user_email) . '</p>';
            echo '<p><strong>' . __('License Key:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_key) . '</p>';
            echo '<p><strong>' . __('Account Limit:', 'ealicensewoocommerce') . '</strong> ' . esc_html($account_quota) . ' ' . __('Accounts', 'ealicensewoocommerce') . '</p>';
            echo '<p><strong>' . __('License Expiration:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_expiration) . '</p>';
        }
    }
}


// Function to auto-register a user and log them in after the order is completed
add_action('woocommerce_thankyou', 'ealicensewoocommerce_auto_register_user_after_checkout');

// Function to auto-register a user and log them in after the order is completed
function ealicensewoocommerce_auto_register_user_after_checkout($order_id) {
    // Get the order object
    $order = wc_get_order($order_id);

    // Check if the user is not already registered (guest checkout)
    if ($order->get_user_id() == 0) {
        // Get the billing details from the order
        $email = $order->get_billing_email();
        $first_name = $order->get_billing_first_name();
        $last_name = $order->get_billing_last_name();

        // Check if the email already exists in the system
        if ($user = get_user_by('email', $email)) {
            // If the user exists, log them in and link the order to their account
            wc_set_customer_auth_cookie($user->ID);

            // Assign the existing user to the order
            $order->set_customer_id($user->ID);
            $order->save();
        } else {
            // If the email doesn't exist, create a new user
            $random_password = wp_generate_password();
            $user_id = wp_create_user($email, $random_password, $email);

            // Assign the customer role to the new user
            $user = new WP_User($user_id);
            $user->set_role('customer');

            // Update the user profile with first name and last name
            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
            ));

            // Save billing details to user meta
            update_user_meta($user_id, 'billing_address_1', $order->get_billing_address_1());
            update_user_meta($user_id, 'billing_address_2', $order->get_billing_address_2());
            update_user_meta($user_id, 'billing_city', $order->get_billing_city());
            update_user_meta($user_id, 'billing_company', $order->get_billing_company());
            update_user_meta($user_id, 'billing_country', $order->get_billing_country());
            update_user_meta($user_id, 'billing_state', $order->get_billing_state());
            update_user_meta($user_id, 'billing_email', $order->get_billing_email());
            update_user_meta($user_id, 'billing_first_name', $order->get_billing_first_name());
            update_user_meta($user_id, 'billing_last_name', $order->get_billing_last_name());
            update_user_meta($user_id, 'billing_phone', $order->get_billing_phone());
            update_user_meta($user_id, 'billing_postcode', $order->get_billing_postcode());

            // Save shipping details to user meta
            update_user_meta($user_id, 'shipping_address_1', $order->get_shipping_address_1());
            update_user_meta($user_id, 'shipping_address_2', $order->get_shipping_address_2());
            update_user_meta($user_id, 'shipping_city', $order->get_shipping_city());
            update_user_meta($user_id, 'shipping_company', $order->get_shipping_company());
            update_user_meta($user_id, 'shipping_country', $order->get_shipping_country());
            update_user_meta($user_id, 'shipping_state', $order->get_shipping_state());
            update_user_meta($user_id, 'shipping_first_name', $order->get_shipping_first_name());
            update_user_meta($user_id, 'shipping_last_name', $order->get_shipping_last_name());
            update_user_meta($user_id, 'shipping_postcode', $order->get_shipping_postcode());

            // Trigger the "new account" email to the customer
            WC()->mailer()->customer_new_account($user_id);

            // Link the order to the new user account
            $order->set_customer_id($user_id);
            $order->save();

            // Automatically log the user in after account creation
            wc_set_customer_auth_cookie($user_id);
        }
    }
}