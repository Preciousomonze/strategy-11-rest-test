# strategy-11-rest-test
Req
Requirements

Using the GET accessible endpoint http://api.strategy11.com/wp-json/challenge/v1/1 (there are no parameters to/from required), create a REST API endpoint in WordPress that:

    Can be used when logged out or in;
    Calls the above endpoint to get the data to return;
    Which when called always returns the data, but regardless of when/how many times it is called should not request the data from our server more than 1 time per hour.
    Create a shortcode for the frontend, that when loaded uses Javascript to contact your REST endpoint and display the data returned formatted into a table-like display;
    Create a WP CLI command that can be used to force the refresh of this data;
    Create a WordPress admin page which displays this data in the style of the admin page of the WordPress plugin Formidable Forms that includes the Formidable logo and header. Include a button to refresh the data.
    Create unit tests that check the following:
        Is the request run multiple times an hour?
        Is the table showing the expected results?

Ensure to properly escape, sanitize and validate data in each step as appropriate using built in PHP and WordPress functions.


