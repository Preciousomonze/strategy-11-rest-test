<?php
/**
 * Shortcodes class
 *
 * @package CX_Strategy11_TEST
 */

namespace CX_Strategy11_TEST;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Shortcodes
 *
 * Handles the registration and display of shortcodes.
 */
class Shortcodes {

    /**
     * Initialize the class
     */
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_shortcodes' ] );
    }

    /**
     * Register shortcodes
     */
    public static function register_shortcodes() {
        add_shortcode( 'cx_strategy11_data_table', [ __CLASS__, 'data_table_shortcode' ] );
    }

    /**
     * Shortcode callback to display data table
     *
     * @return string
     */
    public static function data_table_shortcode() {
        $asset_file = Init::plugin_path() . '/assets/js/build/admin.asset.php';

        if ( ! file_exists( $asset_file ) ) {
            return;
        }
 
        $asset = include $asset_file;

        // JS.
        wp_enqueue_script( 'cx_strategy11_frontend_script',
            Init::plugin_url() . '/assets/js/build/frontend.js',
            $asset['dependencies'],
            $asset['version'],
            true 
        );

        // CSS.
        wp_enqueue_style( 'cx_strategy11_frontend_style',
            Init::plugin_url() . '/assets/css/frontend.css',
            array_filter(
                $asset['dependencies'],
                function ( $style ) {
                    return wp_style_is( $style, 'registered' );
                }
            ),
            $asset['version']
        );

        return '<div id="strategy11-data-table">' . esc_html__( 'Loading...', 'strategy-11-rest-test' ) . '</div>';
    }
}
