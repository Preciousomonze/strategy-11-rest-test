<?php
/**
 * Init class
 *
 * @package CX_Strategy11_TEST
 */

namespace CX_Strategy11_TEST;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Init
 *
 * Initialize all services.
 */
class Init {

    /**
     * Register all services
     */
    public static function register_services() {
    	self::loader();
    	self::register_hooks();
    }

    /**
     * Load required files
     */
    private static function loader() {
        include_once self::plugin_path() . '/includes/class-rest-api.php';
        include_once self::plugin_path() . '/includes/class-shortcodes.php';
        include_once self::plugin_path() . '/includes/admin/class-admin-page.php';
        include_once self::plugin_path() . '/includes/class-wp-cli-commands.php';
    }

    /**
     * Register all hooks
     */
    private static function register_hooks() {
        Rest_API::init();
        Shortcodes::init();
        Admin_Page::init();
        WP_CLI_Commands::init();
    }

	/**
	 * ---------------------------------------------------------------------------------
	 * Helper Functions
	 * ---------------------------------------------------------------------------------
	 */

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function plugin_url() {
		return untrailingslashit( plugins_url( '/', CX_STRATEGY11_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	public static function plugin_path() {
		return untrailingslashit( plugin_dir_path( CX_STRATEGY11_PLUGIN_FILE ) );
	}

	/**
	 * Get the plugin base path name.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public static function plugin_basename() {
		return plugin_basename( CX_STRATEGY11_PLUGIN_FILE );
	}

}
