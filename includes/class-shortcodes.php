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
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'frontend_scripts' ) );
		add_action( 'init', array( __CLASS__, 'register_shortcodes' ) );
	}

	/**
	 * Register shortcodes
	 */
	public static function register_shortcodes() {
		add_shortcode( 'cx_strategy11_data_table', array( __CLASS__, 'data_table_shortcode' ) );
	}

	/**
	 * Shortcode callback to display data table
	 *
	 * @return string
	 */
	public static function data_table_shortcode() {
		// Add script.
		wp_enqueue_script( 'cx_strategy11_frontend_script' );

		// Enqueue CSS only on shortcode page. Not the best way though is it, just for test :), or we could load it all through from enqueue_scripts 🤔.
		wp_enqueue_style( 'cx_strategy11_frontend_style' );

		return '<div id="cx-strategy11-data-table" class="cx-strategy11-data-table">' . esc_html__( 'Loading... 🚦', 'strategy-11-rest-test' ) . '</div>';
	}


	/**
	 * Enqueue frontend scripts
	 */
	public static function frontend_scripts() {

		$asset_file = Init::plugin_path() . '/assets/js/build/frontend.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		// Register JS, enqueue later.
		wp_register_script(
			'cx_strategy11_frontend_script',
			Init::plugin_url() . '/assets/js/build/frontend.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		// Register CSS, enqueue later.
		wp_register_style(
			'cx_strategy11_frontend_style',
			Init::plugin_url() . '/assets/css/frontend.css',
			array_filter(
				$asset['dependencies'],
				function ( $style ) {
					return wp_style_is( $style, 'registered' );
				}
			),
			$asset['version']
		);
	}
}
