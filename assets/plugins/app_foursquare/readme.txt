=== Foursquare Venue ===
Contributors: sutherlandboswell
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=sutherland%2eboswell%40gmail%2ecom&lc=US&item_name=Sutherland%20Boswell&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: foursquare, venue, widget, social networking, location
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 2.2.1

Foursquare Venue is a simple but powerful plugin for displaying any venue's Foursquare stats.

== Description ==

Foursquare Venue gives you the ability to display any venue's latest stats on your WordPress site. Using either the widget or shortcode, you will be able to display:

*   People here now
*   Total check-ins
*   Current mayor (along with their picture and number of check-ins)

I'm open to any feedback and suggestions.

== Installation ==

1. Upload `/foursquare-venue/` directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Once activated, there are two ways to display venue stats:

* On the 'Widgets' page listed under 'Appearance,' drag the Foursquare Venue widget to your desired widget area and set the venue ID.
* On any post or page, add the shortcode `[venue id=3945]`, replacing the '3945' with the venue's ID.

The venue's ID can be found as a number at the end of the venue's URL on Foursquare.

== Frequently Asked Questions ==

= The stats are wrong or I'm getting an error, what did I do wrong? =

The most likely problem is that you haven't set a proper venue ID. This is the number from the end of the venue's URL (ex: `3945`).

== Screenshots ==

1. The full set of options
2. Shortcode in use with custom settings applied

== Changelog ==

= 2.2.1 =
* Improved error messages
* Fixed possible bug due to SSL

= 2.2 =
* Data is now cached for 15 minutes to improve performance and lower the risk of exceeding the API's rate limit

= 2.1.1 =
* Fixed a bug that broke venue links

= 2.1 =
* Optimized code to make future updates easier
* Added an option to show the category icon with the title
* Now using the built-in WordPress function `wp_remote_get()` and removed the cURL test

= 2.0 =
* Updated to take advantage of Foursquare's v2 API
* Added a test for cURL on activation

= 1.1 =
* Displays an error message if venue cannot be found
* Added additional options such as showing a title above stats using the shortcode or displaying the venue's name and link

= 1.0.1 =
* Fixed a bug that placed all shortcodes at the beginning of the post

= 1.0 =
* Added a shortcode (ex: `[venue id=http://foursquare.com/venue/3945]`) to display stats for venues inside your posts and pages
* Added a settings page with options to show or hide different stats, customize the text, and more

= 0.1 =
* Initial release

== Upgrade Notice ==

= 2.0 =
* This version requires that your register for a free API key from Foursquare for it to work. Foursquare will be shutting off the old API soon.