<?php
/**
 * Plugin functions and definitions for Global.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * @package ealicensewoocommerce
 */
// Include admin functions
require dirname(__FILE__) . '/admin/ealicensewoocommerce_function_admin_menu.php';

// Include helper functions
require dirname(__FILE__) . '/helper/ealicensewoocommerce_function_helper.php';

// Include public functions
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce.php';
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce_email.php';
require dirname(__FILE__) . '/public/ealicensewoocommerce_function_public_woocommerce_api.php';