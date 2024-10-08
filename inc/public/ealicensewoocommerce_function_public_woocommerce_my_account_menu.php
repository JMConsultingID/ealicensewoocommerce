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
                        item.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M13.125 5C13.125 6.72589 11.7259 8.125 10 8.125C8.27414 8.125 6.87503 6.72589 6.87503 5C6.87503 3.27411 8.27414 1.875 10 1.875C11.7259 1.875 13.125 3.27411 13.125 5Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.75098 16.7652C3.80956 13.3641 6.58492 10.625 10 10.625C13.4152 10.625 16.1906 13.3642 16.2491 16.7654C14.3468 17.6383 12.2304 18.125 10.0003 18.125C7.77003 18.125 5.65344 17.6383 3.75098 16.7652Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Licenses")) {
                        item.innerHTML = '<svgxmlns="http://www.w3.org/2000/svg"width="20"height="20"viewBox="002020"fill="none"><pathd="M16.2511.875V9.6875C16.258.134214.99086.87513.43756.875H12.1875C11.66976.87511.256.4552711.255.9375V4.6875C11.253.13429.99081.8758.43751.875H6.875M8.751.875H4.6875C4.169731.8753.752.294733.752.8125V17.1875C3.7517.70534.1697318.1254.687518.125H15.3125C15.830318.12516.2517.705316.2517.1875V9.375C16.255.2328612.89211.8758.751.875Z"stroke="#09CA8C"stroke-width="1.5"stroke-linecap="round"stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Orders")) {
                        item.innerHTML = '<svgxmlns="http://www.w3.org/2000/svg"width="20"height="20"viewBox="002020"fill="none"><pathd="M6.8755.625H16.875M6.87510H16.875M6.87514.375H16.875M3.1255.625H3.13125V5.63125H3.125V5.625ZM3.43755.625C3.43755.797593.297595.93753.1255.9375C2.952415.93752.81255.797592.81255.625C2.81255.452412.952415.31253.1255.3125C3.297595.31253.43755.452413.43755.625ZM3.12510H3.13125V10.0063H3.125V10ZM3.437510C3.437510.17263.2975910.31253.12510.3125C2.9524110.31252.812510.17262.812510C2.81259.827412.952419.68753.1259.6875C3.297599.68753.43759.827413.437510ZM3.12514.375H3.13125V14.3813H3.125V14.375ZM3.437514.375C3.437514.54763.2975914.68753.12514.6875C2.9524114.68752.812514.54762.812514.375C2.812514.20242.9524114.06253.12514.0625C3.2975914.06253.437514.20243.437514.375Z"stroke="#09CA8C"stroke-width="1.5"stroke-linecap="round"stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Offers")) {
                        item.innerHTML = '<svgxmlns="http://www.w3.org/2000/svg"width="20"height="16"viewBox="002016"fill="none"><pathd="M1.0493213.5082C1.0493213.8731.1940114.22281.4516614.4809C1.709314.73912.0588414.88452.4235614.8852H17.5761C17.940814.884518.290314.739118.54814.4809C18.805614.222818.950313.87318.950313.5082V10.7074C18.355810.546217.830810.193717.45659.70444C17.08239.2151516.87958.6162616.87958.00023C16.87957.3842117.08236.7853217.45656.29603C17.83085.8067418.35585.4542818.95035.29305V2.49223C18.95032.1275118.80561.7776818.5481.51952C18.29031.2613617.94081.1159617.57611.11523H2.42356C2.058841.115961.70931.261361.451661.51952C1.194011.777681.049322.127511.049322.49223V5.28754C1.648615.445172.178845.796812.557216.28757C2.935586.778323.140797.380563.140798.00023C3.140798.619912.935589.222152.557219.7129C2.1788410.20371.6486110.55531.0493210.7129V13.5082Z"stroke="#09CA8C"stroke-width="1.377"stroke-linecap="round"stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Settings")) {
                        item.innerHTML = '<svgxmlns="http://www.w3.org/2000/svg"width="20"height="20"viewBox="002020"fill="none"><pathd="M18.1255.625C18.1257.6960716.44619.37514.3759.375C14.26729.37514.16049.3704514.05499.36153C13.15879.2857712.1689.4210111.596210.1153L5.6369317.3516C5.2336417.84134.6324818.1253.9980718.125C2.8255318.1251.87517.17451.87516.0019C1.87515.36752.158714.76642.6484214.3631L9.884718.40377C10.5797.8320110.71426.841310.63855.94509C10.62965.8395810.6255.7328210.6255.625C10.6253.5539312.30391.87514.3751.875C14.92531.87515.44791.9935315.91872.20645L13.18854.93664C13.40195.8663614.13386.5982115.06356.81164L17.79364.08149C18.00654.5522318.1255.0747818.1255.625Z"stroke="#09CA8C"stroke-width="1.5"stroke-linecap="round"stroke-linejoin="round"/><pathd="M4.0560315.9375H4.06228V15.9438H4.05603V15.9375Z"stroke="#09CA8C"stroke-width="1.5"stroke-linecap="round"stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    } else if (item.innerText.includes("Logout")) {
                        item.innerHTML = '<svgxmlns="http://www.w3.org/2000/svg"width="20"height="20"viewBox="002020"fill="none"><pathd="M13.1257.5V4.375C13.1253.3394712.28552.511.252.5L6.252.5C5.214472.54.3753.339474.3754.375L4.37515.625C4.37516.66055.2144717.56.2517.5H11.25C12.285517.513.12516.660513.12515.625V12.5M15.62512.5L18.12510M18.12510L15.6257.5M18.12510L7.510"stroke="#09CA8C"stroke-width="1.5"stroke-linecap="round"stroke-linejoin="round"/></svg> ' + item.innerHTML;
                    }
                }
            });
        });
    </script>
    <?php
}
add_action('woocommerce_before_account_navigation', 'ealicensewoocommerce_add_icons');


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

