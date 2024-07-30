<?php
/**
 * WP CLI Command class
 *
 * @package CX_Strategy11_TEST
 */

namespace CX_Strategy11_TEST;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class WP_CLI_Commands
 *
 * Handles the WP CLI command for the plugin.
 */
class WP_CLI_Commands {

    /**
     * Initialize the class
     */
    public static function init() {
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            \WP_CLI::add_command( 'cx_strategy11 refresh_data', [ __CLASS__, 'refresh_data' ] );
        }
    }

    /**
     * Refresh data command
     */
    public static function refresh_data() {
        delete_transient( 'cx_strategy11_cached_data' );
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            \WP_CLI::success( __( 'Data cache successfully cleared. Data will be refreshed on the next request.', 'strategy-11-rest-test' ) );
        }
    }
}
