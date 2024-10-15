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
    // Adding the custom fields
    woocommerce_wp_select(array(
        'id' => '_ealicensewoocommerce_account_quota',
        'label' => __('EA Account Quota', 'ealicensewoocommerce'),
        'options' => array(
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
            $new_columns['_ealicensewoocommerce_program_sn'] = __('EA Program', 'yourpropfirm');
        }
    }

    return $new_columns;
}
add_filter('manage_edit-product_columns', 'ealicensewoocommerce_add_program_sn_column_to_admin_products', 20);