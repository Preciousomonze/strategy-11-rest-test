<?php
/**
 * REST API class
 *
 * @package CX_Strategy11_TEST
 */

namespace CX_Strategy11_TEST;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Rest_API
 *
 * Handles the REST API endpoints.
 */
class Rest_API {

    /**
     * Namespace for the custom REST API routes.
     *
     * @var string
     */
    private static $namespace = 'strategy11/v1';

    /**
     * Remote URL.
     *
     * @var string
     */
    private static $remote_url = 'http://api.strategy11.com/wp-json/challenge/v1/1';

    /**
     * Initialize the class
     */
    public static function init() {
        add_action( 'rest_api_init', [ __CLASS__, 'register_endpoints' ] );
    }

    /**
     * Register the REST API endpoints
     */
    public static function register_endpoints() {
        register_rest_route( self::$namespace, '/data', [
            'methods' => 'GET',
            'callback' => [ __CLASS__, 'get_data' ],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Get data from external API and cache it
     *
     * @return \WP_REST_Response|WP_Error
     */
    public static function get_data() {
        $transient_key = 'strategy11_cached_data';
        $cached_data = get_transient( $transient_key );

        if ( $cached_data === false ) {
            $response = wp_remote_get( self::$remote_url );

            if ( is_wp_error( $response ) ) {
                return new \WP_Error( 'api_error', __( 'Unable to retrieve data', 'strategy-11-rest-test' ), [ 'status' => 500 ] );
            }

            $data = wp_remote_retrieve_body( $response );
            $data = sanitize_text_field( $data ); // Sanitize the data.

            set_transient( $transient_key, $data, HOUR_IN_SECONDS );
        } else {
            $data = $cached_data;
        }

        return rest_ensure_response( json_decode( $data, true ) );
    }
}
