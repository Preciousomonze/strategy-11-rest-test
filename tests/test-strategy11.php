<?php

/**
 * Test
 */
class Strategy11Test extends WP_UnitTestCase {

    /**
     * Test data is cached
     */
    public function test_data_is_cached() {
        $transient_key = 'cx_strategy11_cached_data';
        $api_url       = get_rest_url( 'strategy11/v1/data' );

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
            "title" => "This amazing table",
            "data" => array(
                "headers" => array("ID", "First Name", "Last Name", "Email", "Date"),
                "rows" => array(
                    "1" => array("id" => 66, "fname" => "Chris", "lname" => "Test", "email" => "chris@test.com", "date" => 1722021341),
                    "2" => array("id" => 12, "fname" => "Bob", "lname" => "Test", "email" => "bob@test.com", "date" => 1721934941),
                    "3" => array("id" => 33, "fname" => "Bill", "lname" => "Test", "email" => "bill@test.com", "date" => 1722776741),
                    "4" => array("id" => 54, "fname" => "Jack", "lname" => "Test", "email" => "jack@test.com", "date" => 1723230941),
                    "5" => array("id" => 92, "fname" => "Joe", "lname" => "Test", "email" => "joe@test.com", "date" => 1722366941)
                )
            )
        );

        set_transient( 'cx_strategy11_cached_data', wp_json_encode( $mock_data ), HOUR_IN_SECONDS );

        // Capture shortcode output.
        ob_start();
        echo do_shortcode( '[cx_strategy11_data_table]' );
        $output = ob_get_clean();

        // Check if the table contains the expected data.
        $this->assertStringContainsString( 'Chris', $output );
        $this->assertStringContainsString( 'Bob', $output );
        $this->assertStringContainsString( 'Bill', $output );
        $this->assertStringContainsString( 'Jack', $output );
        $this->assertStringContainsString( 'Joe', $output );
        $this->assertStringContainsString( 'chris@test.com', $output );
        $this->assertStringContainsString( 'bob@test.com', $output );
        $this->assertStringContainsString( 'bill@test.com', $output );
        $this->assertStringContainsString( 'jack@test.com', $output );
        $this->assertStringContainsString( 'joe@test.com', $output );
    }
}
