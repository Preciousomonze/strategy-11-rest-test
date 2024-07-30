<?php
/**
 * Plugin Name: Strategy11 Challenge
 * Description: A plugin for the Strategy11 interview test. I tried simplifying it as much as I could, while also trying to give a large plugin kind of vibe.
 * Version: 1.0.0
 * Author: Precious Omonzejele
 * Text Domain: strategy-11-rest-test
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'CX_STRATEGY11_PLUGIN_FILE', __FILE__ );
define( 'CX_STRATEGY11_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CX_STRATEGY11_ABSPATH', dirname( CX_STRATEGY11_PLUGIN_FILE ) . '/' );
define( 'CX_STRATEGY11_ASSETS_PATH', plugins_url( 'assets/', __FILE__ ) );
define( 'CX_STRATEGY11_PLUGIN_VERSION', '1.0.0' );

// Load text domain for translations.
add_action( 'plugins_loaded', function() {
    load_plugin_textdomain( 'strategy-11-rest-test', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
});

/**
 * Load plugin class.
 *
 * @since 1.0.0
 */
function cx_strategy11_init() {

    // Include the init class file.
    require_once CX_STRATEGY11_PLUGIN_DIR . 'includes/class-init.php';

    // Initialize the plugin.
    CX_Strategy11_TEST\Init::register_services();
}
add_action( 'plugins_loaded', 'cx_strategy11_init' );
