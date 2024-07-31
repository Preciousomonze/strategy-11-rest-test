<?php
/**
 *  Uninstall
 *
 * @since 1.0.0
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Nothing to really clean up, i guess, the except the transient.
if ( function_exists( 'delete_transient' ) ) {
    delete_transient( 'cx_strategy11_cached_data' );
}
