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
        'ea-license',
        'ea_license_manage_license_page',
        'dashicons-admin-tools',
        6
    );

    add_submenu_page(
        'ea-license',
        __('Manage License', 'ealicensewoocommerce'),
        __('Manage License', 'ealicensewoocommerce'),
        'manage_options',
        'ea-license',
        'ea_license_manage_license_page'
    );

    add_submenu_page(
        'ea-license',
        __('Settings', 'ealicensewoocommerce'),
        __('Settings', 'ealicensewoocommerce'),
        'manage_options',
        'ea-license-settings',
        'ea_license_settings_page'
    );
}
add_action('admin_menu', 'ealicensewoocommerce_add_admin_menu');

// Function to display settings page
function ealicensewoocommerce_settings_page() {
    ?>
    <div class="wrap ealicensewoocommerce">
        <h1><?php _e('EA License Settings', 'ealicensewoocommerce'); ?></h1>
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
