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

function ealicensewoocommerce_add_icons() {
    ?>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            // Query all navigation links in My Account page
            let menuItems = document.querySelectorAll('.woocommerce-MyAccount-navigation-link a');

            // Loop through each menu item and prepend the correct icon if not already present
            menuItems.forEach(function(item) {
                // Check if the item already contains an icon, if not, add it
                if (!item.querySelector('i') && !item.querySelector('svg')) {
                    if (item.innerText.includes("Expert Advisor")) {
                        item.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M13.125 5C13.125 6.72589 11.7259 8.125 10 8.125C8.27414 8.125 6.87503 6.72589 6.87503 5C6.87503 3.27411 8.27414 1.875 10 1.875C11.7259 1.875 13.125 3.27411 13.125 5Z" stroke="#09CA8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.75098 16.7652C3.80956 13.3641 6.58492 10.625 10 10.625C13.4152 10.625 16.1906 13.3642 16.2491 16.7654C14.3468 17.6383 12.2304 18.125 10.0003 18.125C7.77003 18.125 5.65344 17.6383 3.75098 16.7652Z" stroke="#09CA8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Licenses")) {
                        item.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="heroicons-outline/document"><path id="Vector" d="M16.25 11.875V9.6875C16.25 8.1342 14.9908 6.875 13.4375 6.875H12.1875C11.6697 6.875 11.25 6.45527 11.25 5.9375V4.6875C11.25 3.1342 9.9908 1.875 8.4375 1.875H6.875M8.75 1.875H4.6875C4.16973 1.875 3.75 2.29473 3.75 2.8125V17.1875C3.75 17.7053 4.16973 18.125 4.6875 18.125H15.3125C15.8303 18.125 16.25 17.7053 16.25 17.1875V9.375C16.25 5.23286 12.8921 1.875 8.75 1.875Z" stroke="#09CA8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></g></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Orders")) {
                        item.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M6.875 5.625H16.875M6.875 10H16.875M6.875 14.375H16.875M3.125 5.625H3.13125V5.63125H3.125V5.625ZM3.4375 5.625C3.4375 5.79759 3.29759 5.9375 3.125 5.9375C2.95241 5.9375 2.8125 5.79759 2.8125 5.625C2.8125 5.45241 2.95241 5.3125 3.125 5.3125C3.29759 5.3125 3.4375 5.45241 3.4375 5.625ZM3.125 10H3.13125V10.0063H3.125V10ZM3.4375 10C3.4375 10.1726 3.29759 10.3125 3.125 10.3125C2.95241 10.3125 2.8125 10.1726 2.8125 10C2.8125 9.82741 2.95241 9.6875 3.125 9.6875C3.29759 9.6875 3.4375 9.82741 3.4375 10ZM3.125 14.375H3.13125V14.3813H3.125V14.375ZM3.4375 14.375C3.4375 14.5476 3.29759 14.6875 3.125 14.6875C2.95241 14.6875 2.8125 14.5476 2.8125 14.375C2.8125 14.2024 2.95241 14.0625 3.125 14.0625C3.29759 14.0625 3.4375 14.2024 3.4375 14.375Z" stroke="#09CA8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Offers")) {
                        item.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 20 16" fill="none"><path d="M1.04932 13.5082C1.04932 13.873 1.19401 14.2228 1.45166 14.4809C1.7093 14.7391 2.05884 14.8845 2.42356 14.8852H17.5761C17.9408 14.8845 18.2903 14.7391 18.548 14.4809C18.8056 14.2228 18.9503 13.873 18.9503 13.5082V10.7074C18.3558 10.5462 17.8308 10.1937 17.4565 9.70444C17.0823 9.21515 16.8795 8.61626 16.8795 8.00023C16.8795 7.38421 17.0823 6.78532 17.4565 6.29603C17.8308 5.80674 18.3558 5.45428 18.9503 5.29305V2.49223C18.9503 2.12751 18.8056 1.77768 18.548 1.51952C18.2903 1.26136 17.9408 1.11596 17.5761 1.11523H2.42356C2.05884 1.11596 1.7093 1.26136 1.45166 1.51952C1.19401 1.77768 1.04932 2.12751 1.04932 2.49223V5.28754C1.64861 5.44517 2.17884 5.79681 2.55721 6.28757C2.93558 6.77832 3.14079 7.38056 3.14079 8.00023C3.14079 8.61991 2.93558 9.22215 2.55721 9.7129C2.17884 10.2037 1.64861 10.5553 1.04932 10.7129V13.5082Z" stroke="#09CA8C" stroke-width="1.377" stroke-linecap="round" stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Settings")) {
                        item.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M18.125 5.625C18.125 7.69607 16.4461 9.375 14.375 9.375C14.2672 9.375 14.1604 9.37045 14.0549 9.36153C13.1587 9.28577 12.168 9.42101 11.5962 10.1153L5.63693 17.3516C5.23364 17.8413 4.63248 18.125 3.99807 18.125C2.82553 18.125 1.875 17.1745 1.875 16.0019C1.875 15.3675 2.1587 14.7664 2.64842 14.3631L9.88471 8.40377C10.579 7.83201 10.7142 6.8413 10.6385 5.94509C10.6296 5.83958 10.625 5.73282 10.625 5.625C10.625 3.55393 12.3039 1.875 14.375 1.875C14.9253 1.875 15.4479 1.99353 15.9187 2.20645L13.1885 4.93664C13.4019 5.86636 14.1338 6.59821 15.0635 6.81164L17.7936 4.08149C18.0065 4.55223 18.125 5.07478 18.125 5.625Z" stroke="#09CA8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.05603 15.9375H4.06228V15.9438H4.05603V15.9375Z" stroke="#09CA8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Logout")) {
                        item.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M13.125 7.5V4.375C13.125 3.33947 12.2855 2.5 11.25 2.5L6.25 2.5C5.21447 2.5 4.375 3.33947 4.375 4.375L4.375 15.625C4.375 16.6605 5.21447 17.5 6.25 17.5H11.25C12.2855 17.5 13.125 16.6605 13.125 15.625V12.5M15.625 12.5L18.125 10M18.125 10L15.625 7.5M18.125 10L7.5 10" stroke="#09CA8C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    }
                }
            });
        });
    </script>
    <?php
}
// add_action('woocommerce_before_account_navigation', 'ealicensewoocommerce_add_icons');

function ealicensewoocommerce_add_icons_to_menu($items) {
    // Loop through each menu item
    foreach ( $items as $endpoint => $label ) {
        // Add the respective icons for each menu item using Font Awesome (or inline SVG)
        if ( $endpoint == 'dashboard' ) {
            $items[$endpoint] = '<i class="fas fa-chart-line"></i> ' . $label; // Icon for Expert Advisor
        } elseif ( $endpoint == 'my-licenses' ) {
            $items[$endpoint] = '<i class="fas fa-file-alt"></i> ' . $label; // Icon for Licenses
        } elseif ( $endpoint == 'orders' ) {
            $items[$endpoint] = '<i class="fas fa-shopping-cart"></i> ' . $label; // Icon for Orders
        } elseif ( $endpoint == 'offers' ) {
            $items[$endpoint] = '<i class="fas fa-tags"></i> ' . $label; // Icon for Offers
        } elseif ( $endpoint == 'edit-account' ) {
            $items[$endpoint] = '<i class="fas fa-cog"></i> ' . $label; // Icon for Settings
        } elseif ( $endpoint == 'customer-logout' ) {
            $items[$endpoint] = '<i class="fas fa-sign-out-alt"></i> ' . $label; // Icon for Logout
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

