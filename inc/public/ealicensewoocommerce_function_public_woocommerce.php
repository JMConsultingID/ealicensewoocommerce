<?php
/**
 * Plugin functions and definitions for Cart Woocommerce.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
// Display custom fields in the WooCommerce admin order details
function ealicensewoocommerce_display_admin_order_meta($order) {
    if (!ealicensewoocommerce_is_license_enabled()) {
        return; // Exit if the feature is not enabled
    }

    // Retrieve the license key from order meta
    $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
    $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
    $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);
    $program_sn = get_post_meta($order->get_id(), '_ealicensewoocommerce_program_sn', true);
    
    echo '<p><strong>' . __('License Key') . ':</strong> ' . esc_html($license_key) . '</p>';
    echo '<p><strong>' . __('Account Limit') . ':</strong> ' . esc_html($account_quota) . ' Accounts</p>';
    echo '<p><strong>' . __('License Expiration') . ':</strong> ' . esc_html($license_expiration) . '</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address', 'ealicensewoocommerce_display_admin_order_meta', 10, 1);

// Hook to WooCommerce 'Thank You' page
// add_action('woocommerce_thankyou', 'ealicensewoocommerce_display_license_details_after_order');
// function ealicensewoocommerce_display_license_details_after_order($order_id) {
//     $order = wc_get_order($order_id);
//     if ($order && $order->get_status() === 'completed') {
//         // Retrieve license-related data from the order meta
//         $user_email = get_post_meta($order->get_id(), '_ealicensewoocommerce_email', true);
//         $license_key = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_key', true);
//         $account_quota = get_post_meta($order->get_id(), '_ealicensewoocommerce_account_quota', true);
//         $license_expiration = get_post_meta($order->get_id(), '_ealicensewoocommerce_license_expiration', true);

//         if ($license_key) {
//             echo '<h2>' . __('Your License Details', 'ealicensewoocommerce') . '</h2>';
//             echo '<p><strong>' . __('User Email:', 'ealicensewoocommerce') . '</strong> ' . esc_html($user_email) . '</p>';
//             echo '<p><strong>' . __('License Key:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_key) . '</p>';
//             echo '<p><strong>' . __('Account Limit:', 'ealicensewoocommerce') . '</strong> ' . esc_html($account_quota) . ' ' . __('Accounts', 'ealicensewoocommerce') . '</p>';
//             echo '<p><strong>' . __('License Expiration:', 'ealicensewoocommerce') . '</strong> ' . esc_html($license_expiration) . '</p>';
//         }
//     }
// }

add_filter('woocommerce_checkout_fields', 'make_email_readonly_for_logged_in_users');
function make_email_readonly_for_logged_in_users($fields) {
    if (is_user_logged_in()) {
        $fields['billing']['billing_email']['custom_attributes'] = array('readonly' => 'readonly');
    }
    return $fields;
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
            $random_password = wp_generate_password(); // Save the generated password
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

            // Custom email notification to send password
            $mailer = WC()->mailer();
            $email_data = array(
                'user_id' => $user_id,
                'user_email' => $email,
                'user_pass' => $random_password, // Pass the generated password to the email
                'user_login' => $email, // Set the username as the email, assuming it's the same (or set it to the actual username if different)
                'email_heading' => __('Account Details'),
            );
            $mailer->send($email, __('Your New Account on EA YourRoboTrader'), wc_get_template_html('emails/customer-new-account.php', $email_data));

            // Link the order to the new user account
            $order->set_customer_id($user_id);
            $order->save();

            // Automatically log the user in after account creation
            wc_set_customer_auth_cookie($user_id);
        }
    }
}

add_filter('wp_nav_menu_items', 'custom_menu_items_for_woocommerce_account', 10, 2);
function custom_menu_items_for_woocommerce_account($items, $args) {
        if (is_user_logged_in()) {
            $items .= '<li class="fa-solid fa-right-to-bracket menu-item menu-item-type-custom menu-item-object-custom menu-item-cus-11374"><a class="elementor-item" href="' . get_permalink(get_option('woocommerce_myaccount_page_id')) . '">My Account</a></li>';
        } else {
            $items .= '<li class="fa-solid fa-right-to-bracket menu-item menu-item-type-custom menu-item-object-custom menu-item-cus-11374"><a class="elementor-item" href="' . get_permalink(get_option('woocommerce_myaccount_page_id')) . '">Login</a></li>';
        }
    return $items;
}