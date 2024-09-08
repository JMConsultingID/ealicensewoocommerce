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

    add_settings_section('ealicensewoocommerce_section', __('EA License Main Settings', 'ealicensewoocommerce'), null, 'ealicensewoocommerce_settings');

    add_settings_field('ealicensewoocommerce_enable_license', __('Enable EA License', 'ealicensewoocommerce'), 'ealicensewoocommerce_enable_license_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');
    add_settings_field('ealicensewoocommerce_api_base_endpoint_url', __('API Base Endpoint URL', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_api_base_endpoint_url_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');
    add_settings_field('ealicensewoocommerce_api_authorization_key', __('API Authorization Key', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_api_authorization_key_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');
    add_settings_field('ealicensewoocommerce_api_version', __('API Version', 'ealicensewoocommerce'), 'ealicensewoocommerce_license_api_version_callback', 'ealicensewoocommerce_settings', 'ealicensewoocommerce_section');
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
                    <th><?php _e('License Key', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Email', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('OrderID', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Account Quota', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Used Quota', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('License Expiration', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('License Status', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Source Domain', 'ealicensewoocommerce'); ?></th>
                    <th><?php _e('Creation Date', 'ealicensewoocommerce'); ?></th>
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
                            echo '<td>' . esc_html((int) $license['order_id']) . '</td>'; // Cast order_id to integer
                            echo '<td>' . esc_html($license['account_quota']) . '</td>';
                            echo '<td>' . esc_html($license['used_quota']) . '</td>';
                            echo '<td>' . esc_html($license['license_expiration']) . '</td>';
                            echo '<td>' . esc_html($license['status']) . '</td>';
                            echo '<td>' . esc_html($source_domain) . '</td>';
                            echo '<td>' . esc_html(date('Y-m-d', strtotime($license['license_creation_date']))) . '</td>';
                            echo '<td><a href="' . esc_url(admin_url('admin.php?page=ealicensewoocommerce&edit_id=' . $license['id'])) . '" class="dashicons dashicons-visibility" title="' . __('View Details', 'ealicensewoocommerce') . '"></a></td>';

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
    <?php
}