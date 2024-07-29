<?php
/**
 * Admin Page class
 *
 * @package CX_Strategy11_TEST
 */

namespace CX_Strategy11_TEST;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Admin
 *
 * Handles the admin page display and functionality.
 */
class Admin_Page {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'register_admin_page' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_scripts' ] );
        add_action( 'wp_ajax_strategy11_refresh_data', [ __CLASS__, 'refresh_data_ajax' ] );
    }

    /**
     * Register the admin page
     */
    public static function register_admin_page() {
        add_menu_page(
            __( 'CX Strategy11 Data', 'strategy-11-rest-test' ),
            __( 'CX Strategy11 Data', 'strategy-11-rest-test' ),
            'manage_options',
            'cx-strategy11-data',
            [ __CLASS__, 'admin_page' ],
            'dashicons-chart-bar',
            6
        );
    }

    /**
     * Display the admin page content
     */
    public static function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Strategy11 Data', 'strategy-11-rest-test' ); ?></h1>
            <div id="cx-strategy11-admin-data-table"><?php esc_html_e( 'Loading... 🚦', 'strategy-11-rest-test' ); ?></div>
            <button id="cx-strategy11-refresh-button" class="button"><?php esc_html_e( 'Refresh Data', 'strategy-11-rest-test' ); ?></button>
        </div>
        <?php
    }

    /**
     * Enqueue admin scripts
     *
     * @param string $hook The current admin page hook.
     */
    public static function enqueue_admin_scripts( $hook ) {
        var_dump($hook);
        if ( $hook !== 'toplevel_page_cx-strategy11-data' ) {
            return;
        }

        $asset_file = Init::plugin_path() . '/assets/js/build/admin.asset.php';

        if ( ! file_exists( $asset_file ) ) {
            return;
        }
 
        $asset = include $asset_file;
		// wp_enqueue_script(
		// 	$script_handle,
		// 	plugins_url( '../../build/index.js', __FILE__ ),
		// 	$asset_file['dependencies'],
		// 	$asset_file['version'],
		// 	true
		// );

        wp_enqueue_script( 'cx_strategy11_admin_script',
            Init::plugin_url() . '/assets/js/build/admin.js',
            $asset['dependencies'],
            $asset['version'],
            true 
        );
    }

    /**
     * Handle AJAX request to refresh data
     */
    public static function refresh_data_ajax() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Unauthorized', 'strategy-11-rest-test' ), 401 );
        }

        delete_transient( 'strategy11_cached_data' );
        wp_send_json_success( __( 'Data refreshed', 'strategy-11-rest-test' ) );
    }
}
