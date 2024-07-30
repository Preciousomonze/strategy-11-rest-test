=== CX Strategy 11 Plugin ===
Contributors: Precious Omonzejele
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==
CX Strategy 11 Plugin fetches and displays data from an external API endpoint. This plugin provides:

- A REST API endpoint accessible whether logged in or out
- Data caching mechanism to limit requests to once per hour
- A shortcode `[cx_strategy11_data_table]` to display data in a table on the frontend
- A WP CLI command to refresh the cached data
- An admin page styled like Formidable Forms with sorting functionality and a data refresh button

== Features ==
- Fetch data from `http://api.strategy11.com/wp-json/challenge/v1/1`
- REST API endpoint for data retrieval
- Shortcode for displaying data in a table on the frontend
- WP CLI command for data refresh: `cx_strategy11 refresh_data`
- Admin page with sorting and manual data refresh options

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/cx-strategy11` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.

== Usage ==

=== Shortcode ===
To display the data on any page or post, use the shortcode:
[cx_strategy11_data_table]

=== WP CLI Command ===
To refresh the data manually via WP CLI, use:
`wp cx_strategy11 refresh_data`

=== Admin Page ===
Navigate to the "CX Strategy 11" menu in the WordPress admin to view and sort the data. Click the "Refresh Data" button to update the data manually.

== Screenshots ==
1. Admin Page with Data Table and Refresh Button
   ![Admin Page](https://github.com/Preciousomonze/strategy-11-rest-test/blob/main/screenshots/screenshot1.png)

2. Frontend Table Display
   ![Frontend Table](https://github.com/Preciousomonze/strategy-11-rest-test/blob/main/screenshots/screenshot2.png)

== Frequently Asked Questions ==

=== How often is the data updated? ===
The data is cached and updated once per hour to minimize requests to the external API.

=== Can I manually refresh the data? ===
Yes, you can refresh the data manually using the WP CLI command or the "Refresh Data" button on the admin page.
