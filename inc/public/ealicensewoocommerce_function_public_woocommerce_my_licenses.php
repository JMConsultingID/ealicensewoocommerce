<?php
/**
 * Plugin functions and definitions for My Licenses.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */

function ealicensewoocommerce_menu_items($items) {
    // Unset Address Menu 
    unset($items['edit-address']); 
    // Insert the new menu items after the "Orders" tab (or wherever you want them)
    $new_items = array_slice($items, 0, 1, true) +
                array('my-licenses' => __('My License', 'ealicensewoocommerce')) +    
                // array('video-tutorials' => __('Video Guides', 'ealicensewoocommerce')) +                             
                array_slice($items, 1, null, true);
    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'ealicensewoocommerce_menu_items');

// Add the custom endpoint for My License, Video Tutorials, and Guides
function ealicensewoocommerce_menu_items_endpoint() {
    add_rewrite_endpoint('my-licenses', EP_PAGES);
    add_rewrite_endpoint('video-tutorials', EP_PAGES);
}
add_action('init', 'ealicensewoocommerce_menu_items_endpoint');

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
        echo '<p>' . __('Error fetching licenses', 'ealicensewoocommerce') . '</p>';
        return;
    }

    // Decode the JSON response
    $body = wp_remote_retrieve_body($response);
    $licenses = json_decode($body, true);

    if (empty($licenses)) {
        echo '<p>' . __('No licenses found for this email.', 'ealicensewoocommerce') . '</p>';
        return;
    }

    // echo '<h4>' . __('My licenses', 'ealicensewoocommerce') . '</h4>';
    // Display the licenses in a table
    echo '<table class="shop_table shop_table_responsive my_account_orders">';
    echo '<thead><tr><th>' . __('Order ID', 'ealicensewoocommerce') . '</th><th>' . __('License', 'ealicensewoocommerce') . '</th><th>' . __('Account Quota', 'ealicensewoocommerce') . '</th><th>' . __('Used Quota', 'ealicensewoocommerce') . '</th><th>' . __('Expiration', 'ealicensewoocommerce') . '</th><th>' . __('Expiration Date', 'ealicensewoocommerce') . '</th><th>' . __('Status', 'ealicensewoocommerce') . '</th></tr></thead>';
    echo '<tbody>';

    foreach ($licenses as $license) {
        echo '<tr>';
        echo '<td><a href="/my-account/view-order/' . esc_html($license['order_id']) . '">#' . esc_html($license['order_id']) . '</a></td>';
        echo '<td>' . esc_html($license['license_key']) . '</td>';        
        echo '<td>' . esc_html($license['account_quota']) . '</td>';
        echo '<td>' . esc_html($license['used_quota']) . '</td>';
        echo '<td>' . esc_html($license['license_expiration']) . '</td>';
        echo '<td>' . esc_html($license['license_expiration_date'] ? date('Y-m-d', strtotime($license['license_expiration_date'])) : 'Lifetime') . '</td>';
        echo '<td>' . esc_html($license['status']) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

add_action('woocommerce_account_my-licenses_endpoint', 'ealicensewoocommerce_display_licenses_by_email');

function ealicensewoocommerce_video_tutorials_content() {
    echo '<h4>' . __('Video Guides', 'ealicensewoocommerce') . '</h4>';
    sample_video_content_yrt();
}
add_action('woocommerce_account_video-tutorials_endpoint', 'ealicensewoocommerce_video_tutorials_content');


// Flush rewrite rules on activation
function ealicensewoocommerce_flush_rewrite_rules() {
    ealicensewoocommerce_menu_items_endpoint();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ealicensewoocommerce_flush_rewrite_rules');

<?php

function sample_video_content_yrt() {
    ?>
    <div class="alert alert-info d-flex align-items-center">
        <i class="bi bi-youtube meta-icon"></i>
        <p class="mb-0 ms-3">Feeling a bit lost? <a href="#">Click here to watch a tutorial video</a></p>
    </div>

    <div class="row g-3">
        <!-- Card 1 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 1">
                <div class="card-body">
                    <h5 class="card-title">Intro to Trading</h5>
                    <p class="card-text">5 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example1" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 2">
                <div class="card-body">
                    <h5 class="card-title">Advanced Strategies</h5>
                    <p class="card-text">7 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example2" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 3">
                <div class="card-body">
                    <h5 class="card-title">Forex Trading Basics</h5>
                    <p class="card-text">8 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example3" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 4">
                <div class="card-body">
                    <h5 class="card-title">Risk Management</h5>
                    <p class="card-text">6 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example4" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 5 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 5">
                <div class="card-body">
                    <h5 class="card-title">Algo Trading Guide</h5>
                    <p class="card-text">10 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example5" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 6 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 6">
                <div class="card-body">
                    <h5 class="card-title">Using Indicators</h5>
                    <p class="card-text">4 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example6" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 7 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 7">
                <div class="card-body">
                    <h5 class="card-title">Candlestick Patterns</h5>
                    <p class="card-text">12 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example7" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 8 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 8">
                <div class="card-body">
                    <h5 class="card-title">Psychology in Trading</h5>
                    <p class="card-text">9 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example8" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>

        <!-- Card 9 -->
        <div class="col-md-4">
            <div class="card card-dashboard p-3">
                <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Video 9">
                <div class="card-body">
                    <h5 class="card-title">Market Analysis Tools</h5>
                    <p class="card-text">11 VIDEOS</p>
                    <a href="https://www.youtube.com/watch?v=example9" class="btn btn-outline-dark w-100 mb-2">Watch Course</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

