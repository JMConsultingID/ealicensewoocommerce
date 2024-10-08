<?php
/**
 * Plugin functions and definitions for My Licenses.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */

// Function to modify the WooCommerce My Account menu items
function ealicensewoocommerce_menu_items($items) {
    // Remove the default 'edit-address' menu item
    unset($items['edit-address']);

    // Add and rename menu items
    $new_items = array(
        'dashboard'      => __('Expert Advisor', 'ealicensewoocommerce'),  // Rename 'Dashboard' to 'Expert Advisor'
        'my-licenses'    => __('Licenses', 'ealicensewoocommerce'),        // Add a new menu item 'Licenses'
        'orders'         => __('Orders', 'ealicensewoocommerce'),          // Keep 'Orders' as is
        'offers'         => __('Offers', 'ealicensewoocommerce'),           // Add a new menu item 'Offer'
        'edit-account'   => __('Settings', 'ealicensewoocommerce'),        // Rename 'Account Details' to 'Settings'
        'customer-logout'=> __('Logout', 'ealicensewoocommerce'),          // Add 'Logout' menu item
    );

    // Return the modified menu items
    return $new_items;
}
// Hook the function to 'woocommerce_account_menu_items' to customize My Account menu
add_filter('woocommerce_account_menu_items', 'ealicensewoocommerce_menu_items');

function ealicensewoocommerce_add_icons_to_menu($items) {
    // Loop through each menu item
    foreach ( $items as $endpoint => $label ) {
        // Add the respective icons for each menu item using Font Awesome
        if ( $endpoint == 'dashboard' ) {
            $items[$endpoint] = wp_kses_post('<i class="fas fa-chart-line"></i> ' . $label); // Icon for Expert Advisor
        } elseif ( $endpoint == 'my-licenses' ) {
            $items[$endpoint] = wp_kses_post('<i class="fas fa-file-alt"></i> ' . $label); // Icon for Licenses
        } elseif ( $endpoint == 'orders' ) {
            $items[$endpoint] = wp_kses_post('<i class="fas fa-shopping-cart"></i> ' . $label); // Icon for Orders
        } elseif ( $endpoint == 'offers' ) {
            $items[$endpoint] = wp_kses_post('<i class="fas fa-tags"></i> ' . $label); // Icon for Offers
        } elseif ( $endpoint == 'edit-account' ) {
            $items[$endpoint] = wp_kses_post('<i class="fas fa-cog"></i> ' . $label); // Icon for Settings
        } elseif ( $endpoint == 'customer-logout' ) {
            $items[$endpoint] = wp_kses_post('<i class="fas fa-sign-out-alt"></i> ' . $label); // Icon for Logout
        }
    }
    
    // Return the modified menu items
    return $items;
}
add_filter('woocommerce_account_menu_items', 'ealicensewoocommerce_add_icons_to_menu');

// Add the custom endpoint for My License, Video Tutorials, and Guides
function ealicensewoocommerce_menu_items_endpoint() {
    add_rewrite_endpoint('my-licenses', EP_PAGES);
    add_rewrite_endpoint('offers', EP_PAGES);
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

function ealicensewoocommerce_offers_content() {
    echo '<h4>' . __('Offers', 'ealicensewoocommerce') . '</h4>';
}
add_action('woocommerce_account_offers_endpoint', 'ealicensewoocommerce_offers_content');


// Flush rewrite rules on activation
function ealicensewoocommerce_flush_rewrite_rules() {
    ealicensewoocommerce_menu_items_endpoint();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ealicensewoocommerce_flush_rewrite_rules');

function sample_video_content_yrt() {
    ?>

    <style>
    .card {
      height: 100%;
      border: none; /* Removes the border */
      box-shadow: none; /* Removes the shadow */
    }

    .card-body {
      padding: 1rem;
    }

    .card-img-top {
      height: 180px;
      object-fit: cover;
      border-bottom-left-radius: 0;
      border-bottom-right-radius: 0;
    }

    .card-title {
      font-size: 1.25rem;
      font-weight: 700;
    }

    .card-text {
      font-size: 0.875rem;
    }

    .view-course-btn {
      margin-top: auto;
      font-size: 0.9rem;
    }

    /* Ensure the card content aligns properly with the image */
    .card img,
    .card-body {
      padding-left: 0;
      padding-right: 0;
    }
    </style>
    
    <div class="alert alert-success d-flex align-items-center">
        <i class="bi bi-youtube meta-icon"></i>
        <p class="mb-0 ms-3">Feeling a bit lost? <a href="#">Click here to watch a tutorial video</a></p>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Moving your business online</h5>
            <p class="card-text">A free course on how to take your business online. Serve more clients and generate more revenue in this new environment.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">After Effects & Lottie in Webflow</h5>
            <p class="card-text">Create animations in After Effects, export them as Lottie JSON files, then animate them in your Webflow site.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Grid 2.0</h5>
            <p class="card-text">This course introduces you to the basic concepts of grid, including how it compares to and works alongside flexbox.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <!-- Add more card blocks here -->
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Moving your business online</h5>
            <p class="card-text">A free course on how to take your business online. Serve more clients and generate more revenue in this new environment.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">After Effects & Lottie in Webflow</h5>
            <p class="card-text">Create animations in After Effects, export them as Lottie JSON files, then animate them in your Webflow site.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Grid 2.0</h5>
            <p class="card-text">This course introduces you to the basic concepts of grid, including how it compares to and works alongside flexbox.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Moving your business online</h5>
            <p class="card-text">A free course on how to take your business online. Serve more clients and generate more revenue in this new environment.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">After Effects & Lottie in Webflow</h5>
            <p class="card-text">Create animations in After Effects, export them as Lottie JSON files, then animate them in your Webflow site.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card h-100">
          <?php $random = rand(1, 1000); ?>  <img src="https://picsum.photos/300/180?random=<?php echo $random; ?>" class="card-img-top" alt="Course Image">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">Grid 2.0</h5>
            <p class="card-text">This course introduces you to the basic concepts of grid, including how it compares to and works alongside flexbox.</p>
            <a href="#" class="view-course-btn">View Video →</a>
          </div>
        </div>
      </div>
    </div>
    <?php
}

