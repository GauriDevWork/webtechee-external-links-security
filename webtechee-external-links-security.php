<?php
/*
Plugin Name: Webtechee External Links Security
Text Domain: webtechee-external-links-security
Description: Automatically adds secure attributes to external links in post content.
Version: 1.0.0
Author: Gauri Kaushik
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SELM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SELM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once SELM_PLUGIN_PATH . 'includes/helpers.php';
require_once SELM_PLUGIN_PATH . 'includes/class-settings.php';
require_once SELM_PLUGIN_PATH . 'includes/class-link-processor.php';

add_action( 'plugins_loaded', function () {
    new SELM_Link_Processor();
});


add_action( 'admin_init', [ 'SELM_Settings', 'register' ] );
