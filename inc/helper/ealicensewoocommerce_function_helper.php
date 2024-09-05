<?php
/**
 * Plugin functions and definitions for Admin.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
// Function to check if YRT EA License feature is enabled
function ealicensewoocommerce_is_license_enabled() {
    $ealicensewoocommerce_enable_license = get_option('ealicensewoocommerce_enable_license');
    return !empty($ealicensewoocommerce_enable_license);
}


// Function to initialize the logger
function ealicensewoocommerce_connection_response_logger() {
    $logger = wc_get_logger();
    $context = array('source' => 'ealicense_connection_response_log');
    return array('logger' => $logger, 'context' => $context);
}