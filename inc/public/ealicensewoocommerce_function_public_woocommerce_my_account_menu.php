<?php
/**
 * Plugin functions and definitions for My Licenses.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */

// Function to modify menu items
function ealicensewoocommerce_menu_items($items) {
    // Remove some default WooCommerce menu items
    unset($items['edit-address']);
    unset($items['downloads']);

    // Add and modify menu items
    $new_items = array(
        'dashboard'        => __('Expert Advisor', 'ealicensewoocommerce'),
        'my-licenses'      => __('Licenses', 'ealicensewoocommerce'),
        'videos'           => __('Videos', 'ealicensewoocommerce'),
        'orders'           => __('Orders', 'ealicensewoocommerce'),
        'offers'           => __('Offers', 'ealicensewoocommerce'),
        'edit-account'     => __('Settings', 'ealicensewoocommerce'),
        'customer-logout'  => __('Logout', 'ealicensewoocommerce'),
    );

    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'ealicensewoocommerce_menu_items');

// Remove default My Account navigation
remove_action('woocommerce_account_navigation', 'woocommerce_account_navigation');

// Add custom My Account navigation with icons
add_action('woocommerce_account_navigation', 'ealicensewoocommerce_account_navigation');
function ealicensewoocommerce_account_navigation() {
    $menu_items = wc_get_account_menu_items();

    // Define icon classes for each endpoint
    $icon_classes = array(
        'dashboard'        => 'fas fa-tachometer-alt',
        'my-licenses'      => 'fas fa-key',
        'videos'           => 'fas fa-graduation-cap',
        'orders'           => 'fas fa-shopping-cart',
        'offers'           => 'fas fa-tag',
        'edit-account'     => 'fas fa-cog',
        'customer-logout'  => 'fas fa-sign-out-alt',
    );
    ?>
    <nav class="woocommerce-MyAccount-navigation">
        <ul>
            <?php foreach ($menu_items as $endpoint => $label) : ?>
                <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                        <?php
                        // Display the icon if it's set for the endpoint
                        if (isset($icon_classes[$endpoint])) {
                            echo '<i class="' . esc_attr($icon_classes[$endpoint]) . '"></i> ';
                        }
                        echo esc_html($label);
                        ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php
}

// Add the custom endpoint for My License, Video Tutorials, and Guides
function ealicensewoocommerce_menu_items_endpoint() {
    add_rewrite_endpoint('my-licenses', EP_PAGES);
    add_rewrite_endpoint('videos', EP_PAGES);
    add_rewrite_endpoint('offers', EP_PAGES);
}
add_action('init', 'ealicensewoocommerce_menu_items_endpoint');

// Add new query vars
function ealicensewoocommerce_query_vars( $vars ) {
    $vars[] = 'my-licenses';
    $vars[] = 'videos';
    $vars[] = 'offers';
    return $vars;
}
add_filter( 'query_vars', 'ealicensewoocommerce_query_vars', 0 );

// Function to get the current page title based on the endpoint
function ealicensewoocommerce_get_current_title() {
    global $wp;

    // Get the current URL
    $current_url = home_url( add_query_arg( array(), $wp->request ) );

    // Get the menu items
    $menu_items = wc_get_account_menu_items();

    // Iterate over the menu items to find the current one
    foreach ( $menu_items as $endpoint => $label ) {
        $endpoint_url = wc_get_account_endpoint_url( $endpoint );
        if ( untrailingslashit( $endpoint_url ) === untrailingslashit( $current_url ) ) {
            return $label;
        }
    }

    // Default title
    return __( 'Order Details', 'ealicensewoocommerce' );
}


function ealicensewoocommerce_display_licenses_by_email() {
    // Get the current user's email address
    $current_user = wp_get_current_user();
    $email = $current_user->user_email;

    // Get the API base endpoint URL, Authorization Key, and API Version from settings
    $api_base_endpoint = get_option('ealicensewoocommerce_api_base_endpoint_url');
    $api_authorization_key = get_option('ealicensewoocommerce_api_authorization_key');
    $api_version = get_option('ealicensewoocommerce_api_version', 'v1'); // Default to 'v1' if not set

    // Construct the API endpoint
    $api_endpoint = trailingslashit($api_base_endpoint) . $api_version . '/licenses/email';

    // Setup the POST request to send the email in the body
    $response = wp_remote_post($api_endpoint, array(
        'headers' => array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_authorization_key,
        ),
        'body' => json_encode(array('email' => $email)), // Send the email in the POST request body
    ));

    // Check for errors in the API response
    if (is_wp_error($response)) {
        echo '<div class="alert alert-warning d-flex align-items-center">' . __('Error fetching licenses', 'ealicensewoocommerce') . '</div>';
        return;
    }

    // Decode the JSON response
    $body = wp_remote_retrieve_body($response);
    $licenses = json_decode($body, true);

    if (empty($licenses)) {
        echo '<div class="alert alert-warning d-flex align-items-center">' . __('No licenses found for this email.', 'ealicensewoocommerce') . '</div>';
        return;
    }

    // echo '<h4>' . __('My licenses', 'ealicensewoocommerce') . '</h4>';
    // Display the licenses in a table
    echo '<table class="shop_table shop_table_responsive my_account_orders">';
    echo '<thead><tr><th>' . __('Order ID', 'ealicensewoocommerce') . '</th><th>' . __('Product Name', 'ealicensewoocommerce') . '</th><th>' . __('License', 'ealicensewoocommerce') . '</th><th>' . __('Account Quota', 'ealicensewoocommerce') . '</th><th>' . __('Used Quota', 'ealicensewoocommerce') . '</th><th>' . __('Expiration', 'ealicensewoocommerce') . '</th><th>' . __('Expiration Date', 'ealicensewoocommerce') . '</th><th>' . __('Status', 'ealicensewoocommerce') . '</th></tr></thead>';
    echo '<tbody>';

    foreach ($licenses as $license) {
        echo '<tr>';
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-id" data-title="Order ID"><a href="/my-account/view-order/' . esc_html($license['order_id']) . '">#' . esc_html($license['order_id']) . '</a></td>';
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-product-name" data-title="Product Name">' . esc_html($license['product_name']) . '</td>';
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-ea-license" data-title="License">' . esc_html($license['license_key']) . '</td>';        
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-ea-account-quota" data-title="Account Quota">' . esc_html($license['account_quota']) . '</td>';
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-ea-used-quota" data-title="Used Quota">' . esc_html($license['used_quota']) . '</td>';
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-ea-expiration" data-title="Expiration">' . esc_html($license['license_expiration']) . '</td>';
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-ea-expiration-date" data-title="Expiration Date">' . esc_html($license['license_expiration_date'] ? date('Y-m-d', strtotime($license['license_expiration_date'])) : 'Lifetime') . '</td>';
        echo '<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-ea-status" data-title="Status">' . esc_html($license['status']) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

add_action('woocommerce_account_my-licenses_endpoint', 'ealicensewoocommerce_display_licenses_by_email');

function ealicensewoocommerce_videos_content() {
    $videos_elementor_page_id = esc_attr(get_option('ealicensewoocommerce_template_videos_id'));

    if (empty($videos_elementor_page_id)) {
        echo '<div class="alert alert-warning d-flex align-items-center">Please set the Videos Elementor Template ID in the settings.</div>';
    } else {
        echo do_shortcode('[elementor-template id="' . $videos_elementor_page_id . '"]');
    }

}
add_action('woocommerce_account_videos_endpoint', 'ealicensewoocommerce_videos_content');

function ealicensewoocommerce_offers_content() {
    $offers_elementor_page_id = esc_attr(get_option('ealicensewoocommerce_template_offers_id'));

    if (empty($offers_elementor_page_id)) {
        echo '<div class="alert alert-warning d-flex align-items-center">Please set the Offers Elementor Template ID in the settings.</div>';
    } else {
        echo do_shortcode('[elementor-template id="' . $offers_elementor_page_id . '"]');
    }

}
add_action('woocommerce_account_offers_endpoint', 'ealicensewoocommerce_offers_content');


// Flush rewrite rules on activation
function ealicensewoocommerce_flush_rewrite_rules() {
    ealicensewoocommerce_menu_items_endpoint();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ealicensewoocommerce_flush_rewrite_rules');