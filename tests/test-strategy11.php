<?php

/**
 * @coversDefaultClass  \BrianHenryIE\WP_Plugin_Updater\Plugin_Updater
 */
class Strategy11Test extends WP_UnitTestCase {

    /**
     * Test data is cached
     */
    public function test_data_is_cached() {
        $transient_key = 'cx_strategy11_cached_data';
        $api_url       = 'http://your-site/wp-json/strategy11/v1/data';

        // Clear any existing transient.
        delete_transient( $transient_key );

        // First request.
        $response_1 = wp_remote_get( $api_url );
        $data_1     = wp_remote_retrieve_body( $response_1 );

        // Second request within the same hour.
        $response_2 = wp_remote_get( $api_url );
        $data_2     = wp_remote_retrieve_body( $response_2 );

        // Assert the data is the same.
        $this->assertEquals( $data_1, $data_2 );

        // Force refresh and check if the data changes.
        delete_transient( $transient_key );
        $response_3 = wp_remote_get( $api_url );
        $data_3     = wp_remote_retrieve_body( $response_3 );

        $this->assertNotEquals( $data_2, $data_3 );
    }

    /**
     * Test table display
     */
    public function test_table_display() {
        // Mock the data.
        $mock_data = array(
            array( 'id' => 1, 'name' => 'Test Name', 'value' => 'Test Value' )
        );

        set_transient( 'cx_strategy11_cached_data', wp_json_encode( $mock_data ), HOUR_IN_SECONDS );

        // Capture shortcode output.
        ob_start();
        echo do_shortcode( '[cx_strategy11_data_table]' );
        $output = ob_get_clean();

        // Check if the table contains the expected data.
        $this->assertStringContainsString( 'Test Name', $output );
        $this->assertStringContainsString( 'Test Value', $output );
    }
}
