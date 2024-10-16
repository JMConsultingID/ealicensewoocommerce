<?php
/**
 * Plugin functions and definitions for Admin.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */

function ealicensewoocommerce_add_admin_menu() {
    add_menu_page(
        __('EA License', 'ealicensewoocommerce'),
        __('EA License', 'ealicensewoocommerce'),
        'manage_options',
        'ealicensewoocommerce',
        'ealicensewoocommerce_manage_license_page',
        'dashicons-marker',
        6
    );

    add_submenu_page(
        'ealicensewoocommerce',
        __('Manage License', 'ealicensewoocommerce'),
        __('Manage License', 'ealicensewoocommerce'),
        'manage_options',
        'ealicensewoocommerce',
        'ealicensewoocommerce_manage_license_page'
    );

    add_submenu_page(
        'ealicensewoocommerce',
        __('Validation Logs', 'ealicensewoocommerce'),
        __('Validation Logs', 'ealicensewoocommerce'),
        'manage_options',
        'ealicensewoocommerce-logs',
        'ealicensewoocommerce_logs_page'
    );

    add_submenu_page(
        'ealicensewoocommerce',
        __('Settings', 'ealicensewoocommerce'),
        __('Settings', 'ealicensewoocommerce'),
        'manage_options',
        'ealicensewoocommerce-settings',
        'ealicensewoocommerce_settings_page'
    );
}
add_action('admin_menu', 'ealicensewoocommerce_add_admin_menu');

// Function to display settings page
function ealicensewoocommerce_settings_page() {
    ?>
    <div class="wrap ealicensewoocommerce">
        <form method="post" action="options.php">
            <?php
            settings_fields('ealicensewoocommerce_settings_group');
            do_settings_sections('ealicensewoocommerce_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function ealicensewoocommerce_register_settings() {
    register_setting('ealicensewoocommerce_settings_group', 'ealicensewoocommerce_enable_license');
    register_setting('ealicensewoocommerce_settings_group', 'ealicensewoocommerce_api_base_endpoint_url');
    register_setting('ealicensewoocommerce_settings_group', 'ealicensewoocommerce_api_authorization_key');
    register_setting('ealicensewoocommerce_settings_group', 'ealicensewoocommerce_api_version');

    register_setting('ealicensewoocommerce_settings_group', 'ealicensewoocommerce_template_dashboard_id');
    register_setting('ealicensewoocommerce_settings_group', 'ealicensewoocommerce_template_offers_id');

    add_settings_section('ealicensewoocommerce_section', __('EA License Main Settings', 'ealicensewoocommerce'), null, 'ealicensewoocommerce_settings');

    add_settings_field('ealicensewoocommerce_enable_license', __('Enable EA License', 'ealicensewoocommerce'), 'ealicensewoocommerce_enable_license_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');
    add_settings_field('ealicensewoocommerce_api_base_endpoint_url', __('API Base Endpoint URL', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_api_base_endpoint_url_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');
    add_settings_field('ealicensewoocommerce_api_authorization_key', __('API Authorization Key', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_api_authorization_key_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');
    add_settings_field('ealicensewoocommerce_api_version', __('API Version', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_api_version_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');

    add_settings_section(
        'hello_woocommerce_settings_section',
        'Hello Theme WooCommerce Settings',
        'hello_woocommerce_settings_section_callback',
        'hello-woocommerce-settings'
    );

    add_settings_section('ealicensewoocommerce_elementor_section', __('Elementor Settings Page', 'ealicensewoocommerce'), null, 'ealicensewoocommerce_settings');
    add_settings_field('ealicensewoocommerce_template_dashboard_id', __('Dashboard Template ID', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_dashboard_template_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_elementor_section');
    add_settings_field('ealicensewoocommerce_template_offers_id', __('Offers Template ID', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_guides_template_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_elementor_section');
}
add_action('admin_init', 'ealicensewoocommerce_register_settings');

// Callbacks for settings fields
function ealicensewoocommerce_enable_license_callback() {
    $checked = get_option('ealicensewoocommerce_enable_license') ? 'checked' : '';
    echo '<input type="checkbox" id="ealicensewoocommerce_enable_license" name="ealicensewoocommerce_enable_license" value="1" ' . $checked . ' />';
}

function ealicensewoocommerce_license_api_base_endpoint_url_callback() {
    $value = esc_attr(get_option('ealicensewoocommerce_api_base_endpoint_url'));
    echo '<input type="text" id="ealicensewoocommerce_api_base_endpoint_url" name="ealicensewoocommerce_api_base_endpoint_url" value="' . $value . '" class="regular-text" />';
}

function ealicensewoocommerce_license_api_authorization_key_callback() {
    $value = esc_attr(get_option('ealicensewoocommerce_api_authorization_key'));
    echo '<input type="text" id="ealicensewoocommerce_api_authorization_key" name="ealicensewoocommerce_api_authorization_key" value="' . $value . '" class="regular-text" />';
}

// Callback for ealicensewoocommerce API Version setting field
function ealicensewoocommerce_license_api_version_callback() {
    // Get the current option value, defaulting to 'v1'
    $selected_version = get_option('ealicensewoocommerce_api_version', 'v1');

    // Define the select options
    $options = array(
        'v1' => __('Version 1', 'ealicensewoocommerce'),
        'v2' => __('Version 2', 'ealicensewoocommerce')
    );

    // Start the select dropdown
    echo '<select id="ealicensewoocommerce_api_version" name="ealicensewoocommerce_api_version">';

    // Loop through options and set the selected attribute
    foreach ($options as $value => $label) {
        $selected = ($selected_version === $value) ? 'selected="selected"' : '';
        echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
    }

    // Close the select dropdown
    echo '</select>';
}

function ealicensewoocommerce_license_dashboard_template_callback() {
    $value = esc_attr(get_option('ealicensewoocommerce_template_dashboard_id'));
    echo '<input type="text" id="ealicensewoocommerce_template_dashboard_id" name="ealicensewoocommerce_template_dashboard_id" value="' . $value . '" class="regular-text" />';
}

function ealicensewoocommerce_license_license_template_callback() {
    $value = esc_attr(get_option('ealicensewoocommerce_template_license_id'));
    echo '<input type="text" id="ealicensewoocommerce_template_license_id" name="ealicensewoocommerce_template_license_id" value="' . $value . '" class="regular-text" />';
}

function ealicensewoocommerce_license_guides_template_callback() {
    $value = esc_attr(get_option('ealicensewoocommerce_template_offers_id'));
    echo '<input type="text" id="ealicensewoocommerce_template_offers_id" name="ealicensewoocommerce_template_offers_id" value="' . $value . '" class="regular-text" />';
}


// Function to fetch data from REST API and display it in a table with pagination and search
function ealicensewoocommerce_manage_license_page() {
    // Get the API base endpoint URL, API Version, and Authorization Key from settings
    $api_base_endpoint = get_option('ealicensewoocommerce_api_base_endpoint_url');
    $api_version = get_option('ealicensewoocommerce_api_version', 'v1'); // Default to 'v1' if not set
    $api_authorization_key = get_option('ealicensewoocommerce_api_authorization_key');

    // Construct the full API endpoint URL based on the base URL and version
    $api_endpoint = trailingslashit($api_base_endpoint) . $api_version . '/licenses';

    // Handle search and pagination parameters
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $items_per_page = 10; // Set the number of items per page

    // Set up headers for the API request
    $headers = array(
        'Accept' => 'application/json',
        'Content-Type'  => 'application/json',
        'Authorization' => 'Bearer ' . $api_authorization_key
    );

    // Set up query parameters for pagination and search
    $query_args = array(
        'page' => $current_page,
        'limit' => $items_per_page,
        'search' => $search_query
    );

    // Build the full API URL with query parameters
    $api_url = add_query_arg($query_args, $api_endpoint);

    ?>
    <div class="wrap">
        <h1><?php _e('Manage License', 'ealicensewoocommerce'); ?></h1>

        <!-- Search Form -->
        <form method="get" action="">
            <input type="hidden" name="page" value="ealicensewoocommerce">
            <input type="text" name="s" value="<?php echo esc_attr($search_query); ?>" placeholder="<?php _e('Search licenses...', 'ealicensewoocommerce'); ?>">
            <input type="submit" class="button" value="<?php _e('Search', 'ealicensewoocommerce'); ?>">
            <a href="<?php echo esc_url(admin_url('admin.php?page=ealicensewoocommerce')); ?>" class="button"><?php _e('Clear Search', 'ealicensewoocommerce'); ?></a>
        </form>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th width="150px"><?php _e('License Key', 'ealicensewoocommerce'); ?></th>
                    <th width="150px"><?php _e('Email', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('OrderID', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('EA Program', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Account Quota', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Used Quota', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('License Expiration', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('License Status', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Creation Date', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Account Details', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Actions', 'ealicensewoocommerce'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from REST API with Authorization header
                $response = wp_remote_get($api_url, array('headers' => $headers));

                if (is_wp_error($response)) {
                    echo '<tr><td colspan="10">' . __('Error fetching licenses', 'ealicensewoocommerce') . '</td></tr>';
                } else {
                    $response_body = wp_remote_retrieve_body($response);
                    $data = json_decode($response_body, true);

                    // Ensure licenses data exists and is an array
                    if (isset($data['data']) && is_array($data['data'])) {
                        $licenses = $data['data'];
                        foreach ($licenses as $license) {
                            // Extract source information from the license data
                            $source = isset($license['source']) ? json_decode($license['source'], true) : array();
                            $source_domain = isset($source['domain']) ? $source['domain'] : __('N/A', 'ealicensewoocommerce');

                            echo '<tr>';
                            echo '<td>' . esc_html($license['license_key']) . '</td>';
                            echo '<td>' . esc_html($license['email']) . '</td>';
                            echo '<td><a href="' . esc_url(admin_url('post.php?post=' . (int) $license['order_id'] . '&action=edit')) . '" target="_blank">' . esc_html((int) $license['order_id']) . '</a></td>';
                            echo '<td>' . esc_html($license['program_sn']) . '</td>';
                            echo '<td>' . esc_html($license['account_quota']) . '</td>';
                            echo '<td>' . esc_html($license['used_quota']) . '</td>';
                            echo '<td>' . esc_html($license['license_expiration']) . '</td>';
                            echo '<td>' . esc_html($license['status']) . '</td>';
                            echo '<td>' . esc_html(date('Y-m-d', strtotime($license['license_creation_date']))) . '</td>';
                            echo '<td><a href="#" class="dashicons dashicons-visibility" onclick="fetchMqlAccountDetails(' . esc_js($license['id']) . ')" title="View Details"></a></td>';
                             echo '<td>';
                                if ($license['status'] === 'active') {
                                    // Show the "dashicons-no" for active licenses and set up a click event to deactivate
                                    echo '<a href="#" class="dashicons dashicons-dismiss" onclick="toggleLicenseStatus(' . esc_js($license['id']) . ', \'inactive\'); return false;" title="Deactivate License"></a>';
                                } elseif ($license['status'] === 'inactive') {
                                    // Show the "dashicons-yes" for inactive licenses and set up a click event to activate
                                    echo '<a href="#" class="dashicons dashicons-yes-alt" onclick="toggleLicenseStatus(' . esc_js($license['id']) . ', \'active\'); return false;" title="Activate License"></a>';
                                }
                                
                                echo '</td>';

                        }
                    } else {
                        echo '<tr><td colspan="10">' . __('No licenses found', 'ealicensewoocommerce') . '</td></tr>';
                    }
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php
        if (isset($data['total'])) {
            $total_items = $data['total'];
            $per_page = $data['per_page'];
            $current_page = $data['current_page'];
            $total_pages = $data['last_page'];

            $pagination_args = array(
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text' => __('&laquo; Previous', 'ealicensewoocommerce'),
                'next_text' => __('Next &raquo;', 'ealicensewoocommerce'),
            );

            echo '<div class="tablenav"><div class="tablenav-pages">';
            echo paginate_links($pagination_args);
            echo '</div></div>';
        }
        ?>
    </div>

    <!-- Modal to show MQL account details -->
    <div id="mqlAccountModal" class="mql-modal" style="display:none;">
        <div class="mql-modal-content">
            <span class="mql-close">&times;</span>
            <h2><?php _e('MQL Account Details', 'ealicensewoocommerce'); ?></h2>
            <div id="mql-account-details"></div>
        </div>
    </div>
    <?php
}


// Function to fetch data Validate Logs from REST API and display it in a table with pagination and search
function ealicensewoocommerce_logs_page() {
    // Get the API base endpoint URL, API Version, and Authorization Key from settings
    $api_base_endpoint = get_option('ealicensewoocommerce_api_base_endpoint_url');
    $api_version = get_option('ealicensewoocommerce_api_version', 'v1'); // Default to 'v1' if not set
    $api_authorization_key = get_option('ealicensewoocommerce_api_authorization_key');

    // Construct the full API endpoint URL based on the base URL and version
    $api_endpoint = trailingslashit($api_base_endpoint) . $api_version . '/validate-license-logs';

    // Handle pagination parameters
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $items_per_page = 10; // Set the number of items per page

    // Set up headers for the API request
    $headers = array(
        'Accept' => 'application/json',
        'Content-Type'  => 'application/json',
        'Authorization' => 'Bearer ' . $api_authorization_key
    );

    // Set up query parameters for pagination
    $query_args = array(
        'page' => $current_page,
        'limit' => $items_per_page
    );

    // Build the full API URL with query parameters
    $api_url = add_query_arg($query_args, $api_endpoint);

    // Fetch data from REST API
    $response = wp_remote_get($api_url, array('headers' => $headers));

    if (is_wp_error($response)) {
        echo '<div class="notice notice-error"><p>' . __('Error fetching logs', 'ealicensewoocommerce') . '</p></div>';
        return;
    }

    $body = wp_remote_retrieve_body($response);
    $logs_data = json_decode($body, true);

    if (empty($logs_data['data'])) {
        echo '<p>' . __('No validation logs found.', 'ealicensewoocommerce') . '</p>';
        return;
    }

    // Display logs in a table
    echo '<div class="wrap">';
    echo '<h1>' . __('Validate License Logs', 'ealicensewoocommerce') . '</h1>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead>
            <tr>
                <th>' . __('No', 'ealicensewoocommerce') . '</th>
                <th>' . __('Program SN', 'ealicensewoocommerce') . '</th>
                <th>' . __('Account MQL', 'ealicensewoocommerce') . '</th>
                <th>' . __('License Key', 'ealicensewoocommerce') . '</th>
                <th>' . __('Validation', 'ealicensewoocommerce') . '</th>
                <th>' . __('Message', 'ealicensewoocommerce') . '</th>
                <th>' . __('Date', 'ealicensewoocommerce') . '</th>
            </tr>
          </thead>';
    echo '<tbody>';

    // Initialize counter for '$number'
    $number = ($current_page - 1) * $items_per_page + 1;

    foreach ($logs_data['data'] as $log) {
        // Determine if the validation status is invalid
        $validation_status = esc_html($log['validation_status']);
        $validation_class = ($validation_status === 'invalid') ? 'style="color:red;"' : '';
        
        // Ensure fields have values, otherwise use 'N/A'
        $program_sn = !empty($log['program_sn']) ? esc_html($log['program_sn']) : 'N/A';
        $account_mql = !empty($log['account_mql']) ? esc_html($log['account_mql']) : 'N/A';
        $license_key = !empty($log['license_key']) ? esc_html($log['license_key']) : 'N/A';
        $message_validation = !empty($log['message_validation']) ? esc_html($log['message_validation']) : 'N/A';
        $date = !empty($log['date']) ? date('Y-m-d H:i:s', strtotime($log['date'])) : 'N/A'; // Date with time

        echo '<tr ' . $validation_class . '>';
        echo '<td>' . esc_html($number++) . '</td>';  // Increment the counter
        echo '<td>' . $program_sn . '</td>';
        echo '<td>' . $account_mql . '</td>';
        echo '<td>' . $license_key . '</td>';
        echo '<td>' . $validation_status . '</td>'; // Apply red color if invalid
        echo '<td>' . $message_validation . '</td>';
        echo '<td>' . esc_html($date) . '</td>';
        echo '</tr>';
    }


    echo '</tbody>';
    echo '</table>';

    // Handle pagination
    $total_pages = isset($logs_data['last_page']) ? $logs_data['last_page'] : 1;

    if ($total_pages > 1) {
        $pagination_args = array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text' => __('&laquo; Previous', 'ealicensewoocommerce'),
            'next_text' => __('Next &raquo;', 'ealicensewoocommerce'),
        );

        echo '<div class="tablenav"><div class="tablenav-pages">';
        echo paginate_links($pagination_args);
        echo '</div></div>';
    }

    echo '</div>';
}