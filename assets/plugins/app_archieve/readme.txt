=== Archives Calendar Widget ===
Contributors: alekart
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4K6STJNLKBTMU
Tags: archives, calendar, widget, sidebar, view, plugin
Requires at least: 3.3
Tested up to: 3.6
Stable tag: 0.3.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Archives widget that makes your monthly archives look like a calendar on the sidebar.

== Description ==

Archives widget that make your monthly archives look like a calendar on the sidebar. If you have a lot of archives that takes a lot of place on your sidebar this widget is for you. Display your archives as a compact calendar, entirely customizable with CSS.

= Features =

* Displays monthly archives as a compact year calendar
* Show/hide monthly post count
* Entirely customizable with CSS
* 3 themes included (with .less files)
* Shortcode support
* jQuery animated with possibility to use your own JS code.

**Not just a widget**, if your theme does not support widgets, you can use this calendar by calling its **function**:

`archive_calendar();`

you can also configure it:
`$args= array(
	'next_text' => '>',
	'prev_text' => '<',
	'post_count' => true,
);
archive_calendar($args);`

**next_text:** text showing on the next year button, can be empty or HTML to use with Font Awesome for example.

**prev_text:** just like `next_text` but for previous year button.

**post_count:** `true` to show the number of posts for each month, `false` to hide it. If you hide post count with CSS, set to false to avoid counting posts uselessly.

**Also a SHORTCODE**
Use the shortcode to show Archives Calendar in the text widget: `[arcalendar next_text=">" prev_text="<" post_count="true"]`
*In some cases the support of shortcodes in the text widget has to be activated in the plugin settings*


= Notes =

By default the plugin will include jQuery library and it's default css file into your theme. **If your theme already uses jQuery please disable it the plugin's Settings.**

Note that **if you modify the default CSS** file to skin the calendar, you will lose all your changes on the next update, I recommend you to copy css style into you default CSS file.

= Links =
[Project's page](http://labs.alek.be/projects/archives-calendar-widget/)
[Other projects](http://labs.alek.be/projects/)
[Portfolio](http://alek.be)

== Installation ==

1. Upload `archives-calendar-widget` folder in `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Configure the plugin through "Settings > Archives Calendar" menu in WordPress.
4. Activate the widget in "Appearance > Widgets" menu in WordPress

== Frequently asked questions ==



== Screenshots ==

1. Calendar view with posts count
2. Widget settings
3. Plugin settings

== Changelog ==

= 0.3.0 =
* [new] select archive year from a list menu in year navigation
* [new] choose appearance from 3 themes
* [new] shortcode [arcalendar]
* [new] the current archives' year is shown in the widget instead of the actual year
* [fix] if there's no posts in actual year, the widget does not disapear any more
* [*] **HTML and CSS structure changes** in year navigation
* [*] Total rewrite of year navigation jQuery script
* [*] .less files are included for easier themes customization

= 0.2.4 =
* Fixed bad css style declaration for 3.6

= 0.2.3 =
* Fixed missing function that checks if MultiSite is activated.

= 0.2.2 =
* Initial release

== Upgrade notice ==

* The changes in the CSS and HTML code can cause some display problems for users that have copied the CSS code in the theme's style.css file
* All changes made ​​in the archives_calendar.css file will be lost. Make a backup before updating.
------
* Des changement dans le code CSS et HTML pourront causer un souci d'affichage pour les utilisateurs qui ont copié le code CSS dans le fichier style.css du thème
* Tout les changement faits dans le fichier archives_calendar.css seront perdus. Faite une copie avant de mettre à jour.


== Notes ==

By default the plugin will include jQuery library and it's default css file into your theme. If your theme already uses jQuery please disable it the plugin's Settings.

Note that if you modify the default CSS file to skin the calendar, you will lose all your changes on the next update, I recommend you to copy css style into you default CSS file.