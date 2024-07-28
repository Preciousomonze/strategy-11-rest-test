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
        add_shortcode( 'strategy11_data_table', [ __CLASS__, 'data_table_shortcode' ] );
    }

    /**
     * Shortcode callback to display data table
     *
     * @return string
     */
    public static function data_table_shortcode() {
        wp_enqueue_script( 'strategy11_frontend_script', plugins_url( 'assets/js/build/frontend.js', __FILE__ ), [ 'wp-element' ], null, true );
        return '<div id="strategy11-data-table">' . esc_html__( 'Loading...', 'strategy-11-rest-test' ) . '</div>';
    }
}
