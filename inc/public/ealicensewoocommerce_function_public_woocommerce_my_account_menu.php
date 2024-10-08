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
    );

    // Return the modified menu items
    return $new_items;
}
// Hook the function to 'woocommerce_account_menu_items' to customize My Account menu
add_filter('woocommerce_account_menu_items', 'ealicensewoocommerce_menu_items');

// Function to add Heroicon SVG icons to WooCommerce My Account menu items
function add_heroicons_to_menu_items($item_output, $item, $args) {
    // Define SVG icons for each menu item based on the endpoint
    if ($item->endpoint === 'dashboard') {
        // Icon for 'Expert Advisor' (Dashboard renamed)
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.105 0-2-.672-2-1.5S10.895 5 12 5s2 .672 2 1.5S13.105 8 12 8zm0 0v9m-4 4h8m-5-8l5-3m0 0l5 3M3 8l5 3"/></svg> ';
    } elseif ($item->endpoint === 'my-licenses') {
        // Icon for 'Licenses'
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 11c.104 0 .207.024.305.072l1.365.683 1.36-.68a.753.753 0 0 1 .67 0l1.36.68 1.365-.683A.753.753 0 0 1 19 11v4.36a.752.752 0 0 1-.305.608l-2.055 1.287a.751.751 0 0 1-.67 0l-2.05-1.282a.751.751 0 0 1-.67 0l-2.05 1.282a.751.751 0 0 1-.67 0l-2.05-1.282A.752.752 0 0 1 5 15.36V11c0-.414.336-.75.75-.75zM9.75 7a.75.75 0 0 1 0-1.5h4.5a.75.75 0 0 1 0 1.5h-4.5zM5 4.5h14c.414 0 .75.336.75.75v10.755c0 .414-.336.75-.75.75H5.75a.75.75 0 0 1-.75-.75V5.25c0-.414.336-.75.75-.75z"/></svg> ';
    } elseif ($item->endpoint === 'orders') {
        // Icon for 'Orders'
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 4a2 2 0 00-2 2v2H9a2 2 0 00-2 2v6m0 0h11a2 2 0 001-3.732M7 16v5m0-5a2 2 0 00-2 2v3a2 2 0 002 2m7-5v5m0-5a2 2 0 00-2 2v3a2 2 0 002 2M5 16v5m5-5h11a2 2 0 001-3.732M15 11l5-3"/></svg> ';
    } elseif ($item->endpoint === 'offers') {
        // Icon for 'Offers'
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.75a.75.75 0 00-.75.75v7.19a2.25 2.25 0 100 4.62v7.19c0 .414.336.75.75.75s.75-.336.75-.75V15.31a2.25 2.25 0 100-4.62V3.5a.75.75 0 00-.75-.75zM2.75 8a.75.75 0 01.75-.75H9a.75.75 0 010 1.5H3.5a.75.75 0 01-.75-.75zM15 7.25h5.25a.75.75 0 010 1.5H15a.75.75 0 010-1.5zM2.75 17.25h5.25a.75.75 0 000-1.5H2.75a.75.75 0 000 1.5z"/></svg> ';
    } elseif ($item->endpoint === 'edit-account') {
        // Icon for 'Settings'
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 4a2 2 0 011 3.732V17m0-3h-6a2 2 0 011-3.732M7 11v4a2 2 0 11-1-3.732M19 11v4a2 2 0 001-3.732"/></svg> ';
    } elseif ($item->endpoint === 'customer-logout') {
        // Icon for 'Logout'
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m-6 4h3m-3 0a2 2 0 011-3.732M3 16v5h5m0-4a2 2 0 10-1-3.732m0 0v-3m6 4a2 2 0 001-3.732"/></svg> ';
    } else {
        $icon = '';  // No icon for other items
    }

    // Combine the SVG icon with the menu item label and return the result
    return $icon . $item_output;
}
// Hook the function to 'woocommerce_nav_menu_items' to display Heroicon SVGs with My Account menu items
add_filter('woocommerce_nav_menu_items', 'add_heroicons_to_menu_items', 10, 3);



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

