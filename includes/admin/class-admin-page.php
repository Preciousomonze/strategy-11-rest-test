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
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_scripts' ] );
        add_action( 'wp_ajax_cx_strategy11_refresh_data', [ __CLASS__, 'refresh_data_ajax' ] );
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
            'dashicons-smiley',
            6
        );
    }

    /**
     * Display the admin page content
     */
    public static function admin_page() {
        /**
         * Fires before the admin page content is rendered.
         *
         * @since 1.0.0
         */
        do_action( 'cx_strategy11_before_admin_page_content' );
        ?>
        <div class="cx-strategy11-wrap">
            <div class="cx-s-init-cascade-animation">
            <div id="cx-s-top-bar" class="cx-s-flex-box">
                <a href="#" class="cx-s-header-logo">
		        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 599.68 601.37" width="35" height="35">
			        <path fill="#f05a24" d="M289.6 384h140v76h-140z"></path>
			        <path fill="#4d4d4d" d="M400.2 147h-200c-17 0-30.6 12.2-30.6 29.3V218h260v-71zM397.9 264H169.6v196h75V340H398a32.2 32.2 0 0 0 30.1-21.4 24.3 24.3 0 0 0 1.7-8.7V264zM299.8 601.4A300.3 300.3 0 0 1 0 300.7a299.8 299.8 0 1 1 511.9 212.6 297.4 297.4 0 0 1-212 88zm0-563A262 262 0 0 0 38.3 300.7a261.6 261.6 0 1 0 446.5-185.5 259.5 259.5 0 0 0-185-76.8z"></path>
		        </svg>
                 <span class="screen-reader-text"><?php esc_html_e( 'CX Strategy 11 Data', 'strategy-11-rest-test' ); ?></span>
	            </a>
                <div class="cx-s-title">
                <h2><?php 
                    /**
                     * Filter the admin page title.
                     *
                     * @param string $page_heading The default page title.
                     *
                     * @since 1.0.0
                     */
                    $page_heading = apply_filters( 'cx_strategy11_admin_page_title', __( 'CodeXplorer Strategy 11 Data', 'strategy-11-rest-test' ) );

                    echo $page_heading; 
                    ?>
                </h2>
                </div>
            </div>

            <div class="cx-s-dashboard-container">
            <div class="cx-s-card-item cx-s-counter-card cx-s-dashboard-widget" style="transition-delay: 0.06s; width: 130px;">
				<h2><?php esc_html_e( 'Total Entries', 'strategy-11-rest-test' ); ?></h2>
				<b><span class="counter"><?php esc_html_e( 'Loading... 🚦', 'strategy-11-rest-test' ); ?></span></b>
			</div>

            <div id="cx-strategy11-admin-data-table" class="cx-s-dashboard-widget cx-s-card-item cx-s-px-0 cx-s-init-cascade-animation" style="transition-delay: 0.15s;">
                <?php esc_html_e( 'Loading... 🚦', 'strategy-11-rest-test' ); ?>
            </div>
        </div>
        </div>
        </div>
        <?php
        /**
         * Fires after the admin page content is rendered.
         *
         * @since 1.0.0
         */
        do_action( 'cx_strategy11_after_admin_page_content' );
    }

    /**
     * Enqueue admin scripts
     *
     * @param string $hook The current admin page hook.
     */
    public static function admin_scripts( $hook ) {
        if ( $hook !== 'toplevel_page_cx-strategy11-data' ) {
            return;
        }

        $asset_file = Init::plugin_path() . '/assets/js/build/admin.asset.php';

        if ( ! file_exists( $asset_file ) ) {
            return;
        }
 
        $asset = include $asset_file;

        // JS.
        wp_enqueue_script( 'cx_strategy11_admin_script',
            Init::plugin_url() . '/assets/js/build/admin.js',
            $asset['dependencies'],
            $asset['version'],
            true 
        );

        // CSS.
        wp_enqueue_style( 'cx_strategy11_admin_style',
            Init::plugin_url() . '/assets/css/admin.css',
            array_filter(
                $asset['dependencies'],
                function ( $style ) {
                    return wp_style_is( $style, 'registered' );
                }
            ),
            $asset['version']
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
