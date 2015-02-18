=== WP-Ajaxify-Comments ===
Contributors: janjonas
Donate link: http://janjonas.net/donate
Tags: AJAX, comments, comment, themes, theme
Requires at least: 3.1.3
Tested up to: 3.6
Stable tag: 0.17.2
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP-Ajaxify-Comments hooks into your comment form and adds AJAX functionality - no page reloads required when validating, posting or updating comments

== Description ==

When submitting the comment form, WordPress by default reloads the complete page. In the case of an error (e.g. an invalid e-mail address or an empty comment field) the error message is shown on top of a new (blank) screen and the user has to use the browser's back button to correct the comment form and post the comment again. The WP-Ajaxify-Comments WordPress plugin hooks into any WordPress theme and adds AJAX functionality to the comment form: When the comment form is submitted, the plugin sends the data to the WordPress backend without reloading the entire page. In the case of an error, the plugin shows a popup overlay containing the error message and the user can correct the comment form without navigating back. If the comment was posted successfully, the plugin adds the (new) comment to the list of existing comments without leaving the page and shows an information overlay popup. 
Moreover this plugin includes an option to automatically refresh the comments on the current page (if the user is "idle") without a page reload. 

**Live demo:** You can try out a live demo in <a target="_blank" href="http://blog.janjonas.net/2012-06-08/wordpress-ajax-comment-wp-ajaxify-comments-plugin">the blog post I've written for the initial release of the plugin</a>. 

Since the plugin hooks (on client-side) into the theme to intercept the comment form submit process, and to add new comments without reloading the page, the plugin needs to access the DOM nodes using (jQuery) selectors. The plugin comes with default values for these selectors that were successfully tested with WordPress' default themes "Twenty Ten", "Twenty Eleven", "Twenty Twelve". 

Summarized, the WP-Ajaxify-Comments plugin hooks into your theme and improves the usability of the comment form by validating and adding comments without the need of complete page reloads.

**Important:** If the plugin does not work out of the box with your theme, custom selectors could be defined in the plugin's admin frontend. If you don't succeed in configuring the proper selectors please don't hesitate to ask a question in the <a href="http://wordpress.org/support/plugin/wp-ajaxify-comments">plugin's support forum</a> or <a href="http://blog.janjonas.net/contact" target="_blank">send me a private message</a>. The plugin is highly customizable and *I'm aware of only a few conflicts with any themes or other plugins that cannot be resolved* (see "Known incompatibilities" in the FAQ section). I would kindly ask you make a <a href="http://blog.janjonas.net/donate" target="_blank">small (PayPal) donation</a> when I'm able to find a working configuration for your customized WordPress page. All donations will secure future development and support of the plugin. Thanks in advance! You can find more troubleshooting information on the <a href="http://wordpress.org/extend/plugins/wp-ajaxify-comments/faq/">FAQ page</a>.

Some features of the plugin:

* Actively developed and supported
* Validating and adding comments without page reloads
* Seamless integration in almost every theme (default options should work with most themes)
* i18n support (included localizations for ar, ca, da-DK, de-DE, es-ES, fa-IR, fr-FR, he-IL, hu-HU, nl-NL, pl-PL, pt-BR, ru-RU, sk-SK, tr-TR, uk, vi-VN, zh-CN)
* Support for customizing (default) WordPress messages
* Support for threaded comments
* Support for comments that await moderation
* Compatibility with comment spam protection plugins and other plugins that extend/manipulate the comment form
* Admin frontend to customize the look and feel
* (Automatic) fallback mode uses complete page reloads if the plugin is not configured properly or any incompatibility is detected
* Client-side JavaScript API (see FAQ for more details)
* Auto updating comments if user is "idle"
* Option to load comments asynchronously with secondary AJAX request if page contains more than a specified number of comments
* Debug mode to support troubleshooting

== Screenshots ==

1. Info popup overlay after the comment has successfully been posted
2. Error popup overlay with error message when posting a comment failed
3. Admin frontend (to customize the plugin)

== Installation ==

1. Upload wp-ajaxify-comments.zip to your WordPress plugins directory, usually `wp-content/plugins/` and unzip the file. It will create a `wp-content/plugins/wp-ajaxify-comments/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Enable the plugin on the plugin's settings page (Settings > WP-Ajaxify-Comments)

== Frequently Asked Questions ==

= The plugin is not working, what can I do? =

It is recommended to use the plugin's debug mode that can be enabled on the plugin's settings page ("Settings > WP-Ajaxify-Comments"). After enabling the debug mode use a browser that supports console.log(&hellip;) e.g. Firefox with the Firebug extension or Google Chrome and open a page that contains a comment form. If the plugin is not working you will most likely find an error message in the console saying that one of the selectors does not match any element.
If your theme does not use the default IDs for the comment form (`#commentform`), the comment container (`#comments`) or the respond container (`#respond`) you need to go to the plugins settings page and provide the proper selectors.

**Please note:** If you don't succeed in configuring the proper selectors, please don't hesitate to ask a question the <a target="_blank" href="http://wordpress.org/support/plugin/wp-ajaxify-comments">plugin's support forum</a> or <a href="http://blog.janjonas.net/contact" target="_blank">send me a private message</a>. The plugin is highly customizable and *I'm not aware of conflicts to any themes or other plugins that cannot be resolved*. I would kindly ask you make a <a href="http://blog.janjonas.net/donate" target="_blank">small (PayPal) donation</a> when I'm able to find a working configuration for your customized WordPress site. All donations save future development and support of the plugin. Thanks in advance!

= Can I suggest new features? =

If you feel something is missing, or if you have any other suggestions, please <a href="http://blog.janjonas.net/contact" target="_blank" >contact me</a> or use the <a href="http://wordpress.org/support/plugin/wp-ajaxify-comments">support forum</a>.

= Are there any known problems? =

There are problems when using an old jQuery version. The plugin was successfully tested with jQuery 1.4.4 and above.

The debugging mode does not work in Internet Explorer 8 (and older versions); please use Firebug, Google Chrome or Internet Explorer 9 or above for debugging WP-Ajaxify-Comments.

Please see also the "Known incompatibilities" section.

= Does this plugin work with every WordPress theme? =

Since the plugin hooks into the DOM that is generated by the theme, there is no guarantee that the plugin will work with every theme.
Basically, the plugin uses (jQuery) selectors to find the elements like the comment form and the list of comments. Please go to the plugin's settings page to customize these selectors if the default selectors don't match the elements in your theme.

There is no guarantee, but (as written above) the plugin is highly customizable and I'm only aware of a few conflicts to any themes or other plugins that cannot be resolved (see section "Known incompatibilities" below).

= Can I add or update translations? =

If you would like to support the plugin by adding or updating translations please contact me. After installing the plugin, you can find more information about translations in the file `wp-content\plugins\wp-ajaxify-comments\languages\readme.txt`.

= Does the plugin work with older WordPress versions than 3.1.3? =

Most likely yes, but it has not been tested yet. Please leave me a message if you have trouble using the plugin with older Worpress versions and I will try to update the plugin to add compatibility.

= Are there any future plans? =

Yes, there are some features I would like to add in future versions:

* Client-side validation
* Option to enable vertical alignment of popup overlays
* Option to customize popup overlays with user-defined CSS
* File upload support

= How to enable the debug mode? =

The debug mode can be enabled on the plugin's settings page (Settings > WP-Ajaxify-Comments).

= Which callback options are supported and how do I use them? =

The plugin provides some JavaScript callback options that can be used to add custom JavaScript code that is executed on client-side when certain (wp-ajaxify-comments related) events occure. Please note that these callbacks are client-side callbacks, i.e. you cannot execute any PHP code using this callback options.

In detail the following callbacks are supported:

* OnBeforeSelectElements: Called before the plugin selects any DOM elements. The DOM tree the plugin is currently working on is passed as parameter `dom`.
* OnBeforeSubmitComment: Called before a (new) comment is submitted.
* OnBeforeUpdateComments: Called before the plugin replaces the comments.
* OnAfterUpdateComments: Called after the plugin has replaced the comments.

= How do I use the client-side API? =

In addition to the callback options the plugin provides a JavaScript client-side API that supports the following functions:

* `WPAC.RefreshComments(options)`: Refreshes the comments on the current page
* `WPAC.LoadComments(url, options)`: Loads the comments from another url/page

The options defined as key/value pairs and the following keys are supported:

* `scrollToAnchor`: Whether or not to scroll to the anchor of the target url/page (default: true)
* `showLoadingInfo`: Whether or not to show a "loading" overlay popup (default: true)
* `updateUrl`: Whether or not to update the browser url (default: false if option "Disable URL updating" is enabled, true otherwise)
* `success`: A function to be called when the comments have been updated/replaced

= Does the plugin use any external libraries? =

Yes, the plugin uses the following libraries:

* jQuery blockUI plugin (http://malsup.com/jquery/block/) to block the UI while the comment is sent to the server and to show popup overlays containing the error and info messages 
* jsuri (http://code.google.com/p/jsuri/) for query string manipulation
* jQuery Idle Timer plugin (https://github.com/mikesherov/jquery-idletimer) to detect when the user is "idle"

= Known incompatibilities =

There are known incompatibilities to the following plugins:

* WP-reCAPTCHA (tested with WP-reCAPTCHA 3.1.6)

== Changelog ==

= 0.17.2 =
* Fixed compatibility to wpMandrill (thanks to paddywagon)

= 0.17.1 =
* 'OnAfterUpdateComments' callback is now called after form data has been reset

= 0.17.0 =
* Added options to customize (default) WordPress messages
* Disabled (auto) scrolling when comments are updated by "Auto update idle time"
* Fixed compatibility to jQuery "no conflict mode"

= 0.16.1 =
* Bugfix for cross-domain scripting detection

= 0.16.0 =
* Added option "Auto update idle time" to automatically update comments if user is "idle"
* Updated jQuery blockUI to 2.64

= 0.15.0 =
* Added option to disable URL updating

= 0.14.3 =
* Fixed some PHP strict warnings

= 0.14.2 =
* Fixed compatibility to PHP < 5.4.0

= 0.14.1 =
* Fixed compatibility to jQuery "no conflict mode"

= 0.14.0 =
* Added options to customize texts
* WPAC.RefreshComments() and WPAC.LoadComments() now accept option object (and added option "showLoadingInfo" to suppress loading popup overlay)
* Updated jQuery blockUI to 2.61
* Added jsuri 1.1.1 to avoid query strings with duplicated WPAC fallback parameters

= 0.13.1 =
* Comment paging now updates browser URL
* Added localization for da-DK (thanks to Bjarne Maschoreck)
* Bugfix for themes where comment form is not nested in comment container
* Bugfix for clearing all settings (thanks to HarroH) 

= 0.13.0 =
* Ajaxified comment paging
* Improved debug support for cross-domain scripting problems

= 0.12.1 =
* Hotfix for environments where PHP is not installed as an Apache module

= 0.12.0 =
* Bug-fix: Options are no longer saved if validation fails
* Refactored and extended client-side JavaScript API
* Updated localization for de-DE
* Added option to load comments asynchronously with secondary AJAX request

= 0.11.0 =
* Added localization for hu-HU (thanks to Patrik Bagi)
* Added option to customize the popup overlay's width 
* Added option to customize the popup overlay's padding

= 0.10.0 =
* Added localization for he-LI (thanks to Siman-Tov Yechiel (<a href="http://www.wpstore.co.il" target="_blank">www.wpstore.co.il</a>))
* Added JavaScript callback ("Before submit comment")
* Updated jQuery blockUI to 2.57

= 0.9.0 =
* Added JavaScript method wpac_init() to enable manual client side initialization
* Optimized SQL queries (thanks to Geleosan)
* Added validation for "scrollSpeed" option
* Fixed debug alert message in IE 9 
* Added localization for sk-SK (thanks to Branco, Slovak translation (<a href="http://webhostinggeeks.com/user-reviews/" target="_blank">WebHostingGeeks.com</a>))

= 0.8.0 =
* Added option to customize the font size
* Added i18n support for admin frontend

= 0.7.0 =
* Added JavaScript callback ("Before select elements")

= 0.6.3 =
* Added localization for ar (thanks to sha3ira)

= 0.6.2 =
* Fixed some PHP warnings (thanks to petersb)
* Fixed HTTPS check for ISAPI under IIS
* Added support for non-standard HTTP port
* Fixed handling of unexpected/unsupported server responses

= 0.6.1 =
* Added localization for ru-RU and uk (thanks to Валерий Сиволап)

= 0.6.0 =
* Added JavaScript callbacks ("Before update comments" and "After update comments")

= 0.5.4 =
* jQuery 1.7+ compatibility: Use on() or delegate() if available instead of deprecated live() (thanks to tzdk)

= 0.5.3 =
* Added localization for tr-TR (thanks to Erdinç Aladağ)
* Added localization for pt-BR (thanks to Leandro Martins Guimarães)

= 0.5.2 =
* Added localization for fa-IR (thanks to rezach4)

= 0.5.1 =
* Updated localization for zh-CN (thanks to Liberty Pi)
* Updated jQuery blockUI to 2.42 (thanks to Mexalim)

= 0.5.0 =
* Success popup overlay now supports comments that are awaiting moderation
* Add "?" when commentUrl has no query string to reload page in case of partial page update fails
* More detailed debug messages and debug support for Internet Explorer 9
* Added localization for ca (thanks to guzmanfg)

= 0.4.1 =
* Added localization for nl-NL (thanks to Daniël Tulp)

= 0.4.0 =
* Success and error popup overlays now show default cursor instead of loading cursor
* Fixed problems for translations containing double quotes
* Cancel AJAX request if cross-domain scripting is detected
* Added options to customize the look and feel
* Added localization for vi-VN (thanks to Nguyễn Hà Duy Phương)
* Added localization for es-ES (thanks to guzmanfg)
* Updated localization for de-DE

= 0.3.4 =
* Added localization for pl-PL (thanks to Jacek Tomaszewski)

= 0.3.3 =
* Bugfix for Internet Explorer

= 0.3.2 =
* Added localization for fr-FR (thanks to saymonz)

= 0.3.1 =
* Added localization for zh-CN (thanks to Liberty Pi)

= 0.3.0 =
* Added i18n support
* Added localization for de-DE

= 0.2.1 =
* Fallback mode reloads page with comment anchor
* Bug-fix for themes where comment form is nested in comments container (thanks to saymonz)

= 0.2.0 =
* Added Option "Error Container Selector" to customize the error message extraction
* Added compatibility with comment spam protection plugins like "NoSpamNX" (thanks to Liberty Pi)
* Removed timeout for loading popup overlay (thanks to saymonz)

= 0.1.2 =
* Fixed compatibility with setting pages of other plugins (thanks to saymonz)
* Reactivated warning and info notices on admin page "Plugins"

= 0.1.1 =
* Fixed updating of browser address bar

= 0.1.0 =
* Support for themes with threaded comments where form tag is not nested in comment container
* (Smooth) scrolling to new comment after new comment has been posted
* Update browser address bar to show comment URL after new comment has been posted
* Abort plugin initialization on pages and posts where comments are not enabled
* Info popup overlay when complete page reload is performed in fallback mode

= 0.0.2 =
* Fixed error with warning and info notices on admin page "Plugins"

= 0.0.1 =
* Initial release

== Upgrade Notice ==

= 0.17.2 =
* Fixed compatibility to wpMandrill

= 0.17.1 =
'OnAfterUpdateComments' callback is now called after form data has been reset

= 0.17.0 =
Options to customize (default) WordPress messages, Disabled (auto) scrolling when comments are updated by "Auto update idle time", Fixed compatibility to jQuery "no conflict mode"

= 0.16.1 =
Bugfix for cross-domain scripting detection

= 0.16.0 =
Added option to automatically update comments if user is "idle", Updated jQuery blockUI to 2.64

= 0.15.0 =
Added option to disable URL updating

= 0.14.3 =
Fixed some PHP strict warnings

= 0.14.2 =
Fixed compatibility to PHP < 5.4.0

= 0.14.1 =
Fixed compatibility to jQuery "no conflict mode"

= 0.14.0 =
Added options to customize texts, Updated jQuery blockUI to 2.61, Improved client-side API, Added jsuri 1.1.1 to optimize query string sin fallback URLs

= 0.13.1 =
Bug-fixes, improved URL updating, added localization for da-DK

= 0.13.0 =
Ajaxified comment paging, added localization for da-DK

= 0.12.1 =
Hotfix for environments where PHP is not installed as an Apache module

= 0.12.0 =
Bug-fixes, refactored and extended client-side JavaScript API

= 0.11.0 =
Added localization for hu-HU, added more options to customize the popup overlays 

= 0.10.0 =
Added localization for he-LI, added JavaScript callback ("Before submit comment"), updated jQuery blockUI to 2.57

= 0.9.0 =
Added JavaScript method wpac_init(), optimzed SQL queries, fixed debug alert in IE 9, added localization for sk-SK

= 0.8.0 =
Added option to customize the font size, i18n support for admin frontend

= 0.7.0 =
Added JavaScript callback ("Before select elements")

= 0.6.3 =
Added localization for ar

= 0.6.2 =
Some bug-fixes

= 0.6.1 =
Added localization for ru-RU and uk

= 0.6.0 =
Added JavaScript callbacks

= 0.5.4 =
jQuery 1.7+ compatibility

= 0.5.3 =
Added localization for tr-TR and pt-BR

= 0.5.2 =
Added localization for fa-IR

= 0.5.1 =
Updated localization for zh-CN, Updated jQuery blockUI to 2.42

= 0.5.0 =
Bug-fix, support for comments that are awaiting moderation, more detailed debug messages & debug support for IE 9, added localization for ca

= 0.4.1 =
Added localization for nl-NL

= 0.4.0 =
Bug-fix, added options to customize the look and feel, added localizations (vi-VN and en-ES), updated localization for de-DE

= 0.3.4 =
Added localization for pl-PL

= 0.3.3 =
Bug-fix

= 0.3.2 =
Added localization for fr-FR

= 0.3.1 =
Added localization for zh-CN

= 0.3.0 =
Added i18n support

= 0.2.1 =
Bug-fix & minor improvements

= 0.2.0 =
Added compatibility with comment spam protection plugins

= 0.1.2 =
Bug-fix

= 0.1.1 =
Bug-fix

= 0.1.0 =
Better theme support (for threaded comments) and new features

= 0.0.2 =
Bug-fix