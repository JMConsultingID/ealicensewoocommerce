<?php
/**
 * Plugin functions and definitions for Product Woocommerce.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */

add_action('woocommerce_product_options_pricing', 'ealicensewoocommerce_add_additional_fields');

function ealicensewoocommerce_add_additional_fields() {
    // Adding the "Exclude This Product From License Manager" checkbox field
    woocommerce_wp_checkbox(array(
        'id' => '_ealicensewoocommerce_exclude_from_license_manager',
        'label' => __('Exclude This Product From License Manager', 'ealicensewoocommerce'),
        'description' => __('Check this box to exclude this product from being managed by the License Manager.', 'ealicensewoocommerce'),
    ));

    // Adding the custom fields
    woocommerce_wp_select(array(
        'id' => '_ealicensewoocommerce_account_quota',
        'label' => __('EA Account Quota', 'ealicensewoocommerce'),
        'options' => array(
            '' => 'Select Account Quota',
            '2' => '2',
            '5' => '5',
            '10' => '10',
            '15' => '15',
            '20' => '20',
            '25' => '25',
        ),
    ));

    woocommerce_wp_select(array(
        'id' => '_ealicensewoocommerce_license_expiration',
        'label' => __('EA License Expiration', 'ealicensewoocommerce'),
        'options' => array(
            '' => 'Select License Expiration',
            '1 month' => '1 month',
            '3 months' => '3 months',
            '6 months' => '6 months',
            '1 year' => '1 year',
            '2 years' => '2 years',
            '3 years' => '3 years',
            'lifetime' => 'lifetime',
        ),
    ));

    // Adding EA Program SN (Serial Number) text input
    woocommerce_wp_text_input(array(
        'id' => '_ealicensewoocommerce_program_sn',
        'label' => __('EA Program SN', 'ealicensewoocommerce'),
        'desc_tip' => true,
        'description' => __('Enter the EA Program Serial Number', 'ealicensewoocommerce'),
    ));
}

add_action('woocommerce_process_product_meta', 'ealicensewoocommerce_save_additional_fields');

function ealicensewoocommerce_save_additional_fields($post_id) {
    // Save exclude from license manager
    $exclude_from_license_manager = isset($_POST['_ealicensewoocommerce_exclude_from_license_manager']) ? 'yes' : 'no';
    if (!empty($exclude_from_license_manager)) {
        update_post_meta($post_id, '_ealicensewoocommerce_exclude_from_license_manager', $exclude_from_license_manager);
    }

    $account_quota = $_POST['_ealicensewoocommerce_account_quota'];
    if (!empty($account_quota)) {
        update_post_meta($post_id, '_ealicensewoocommerce_account_quota', esc_attr($account_quota));
    }

    $license_expiration = $_POST['_ealicensewoocommerce_license_expiration'];
    if (!empty($license_expiration)) {
        update_post_meta($post_id, '_ealicensewoocommerce_license_expiration', esc_attr($license_expiration));
    }

    // Save EA Program SN (Serial Number)
    $program_sn = $_POST['_ealicensewoocommerce_program_sn'];
    if (!empty($program_sn)) {
        update_post_meta($post_id, '_ealicensewoocommerce_program_sn', esc_attr($program_sn));
    }
}


function ealicensewoocommerce_add_program_sn_column_to_admin_products($columns) {
    $new_columns = array();

    foreach ($columns as $key => $name) {
        $new_columns[$key] = $name;

        if ('sku' === $key) {
            $new_columns['ealicensewoocommerce_program_sn'] = __('EA Program', 'yourpropfirm');
        }
    }

    return $new_columns;
}
add_filter('manage_edit-product_columns', 'ealicensewoocommerce_add_program_sn_column_to_admin_products', 20);

function ealicensewoocommerce_display_program_sn_in_admin_products($column, $post_id) {
    if ('ealicensewoocommerce_program_sn' === $column) {
        $program_sn = get_post_meta($post_id, '_ealicensewoocommerce_program_sn', true);
        if ($program_sn) {
            echo '<span id="_ealicensewoocommerce_program_sn-' . $post_id . '">' . esc_html($program_sn) . '</span>'; 
        } else {
            echo '—';
        }
    }
}
add_action('manage_product_posts_custom_column', 'ealicensewoocommerce_display_program_sn_in_admin_products', 10, 2);


// Add script to disable fields when checkbox is checked
add_action('admin_footer', 'ealicensewoocommerce_disable_fields_script');
function ealicensewoocommerce_disable_fields_script() {
    global $post;
    if ($post->post_type === 'product') {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                function toggleFields() {
                    if ($('#_ealicensewoocommerce_exclude_from_license_manager').is(':checked')) {
                        $('#_ealicensewoocommerce_account_quota').prop('disabled', true);
                        $('#_ealicensewoocommerce_license_expiration').prop('disabled', true);
                        $('#_ealicensewoocommerce_program_sn').prop('disabled', true);
                    } else {
                        $('#_ealicensewoocommerce_account_quota').prop('disabled', false);
                        $('#_ealicensewoocommerce_license_expiration').prop('disabled', false);
                        $('#_ealicensewoocommerce_program_sn').prop('disabled', false);
                    }
                }

                toggleFields();
                $('#_ealicensewoocommerce_exclude_from_license_manager').change(toggleFields);
            });
        </script>
        <?php
    }
}