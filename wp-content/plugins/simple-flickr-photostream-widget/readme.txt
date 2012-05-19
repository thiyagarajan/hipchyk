=== Plugin Name ===
Contributors: Benoit Gilloz
Tags: flickr, photostream, flickr photostream, flickr widget
Requires at least: 2.8
Tested up to: 3.3.1
Stable tag: trunk

Simple Flickr Photostream widget allow you display pictures from Flickr in a widgetized area of you choice. Based on the WP 2.7 widget model

== Description ==

Simple Flickr Photostream widget is another Flickr photo display. I exists because no other plugins were doing what the author needed.

The plugin is essentially a widget that will show pictures from a chosen Flickr source, be it your own photostream, someone else's, one of your sets, a group, your favorite, etc...

The code is based on [FlickrRss](http://eightface.com/wordpress/flickrrss/) plugin made by Dave Kellam and Stefano Verna and improves by placing the controls in the widget itself rather than an admin page. This new approach, combined with the way WP 2.7 handles widgets makes it multiwidgets enabled with different options for each widgets.


== Installation ==

This section describes how to install the plugin and get it working.

If you are upgrading from previous version, do not forget to backup!

1. Upload `simple-flickr-photostream-widget.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go the widget page and drag the "Simple Flickr Photostream" widget in your sidebar area.


== Changelog ==

= 1.3.7 =
* Fixed missing "T" in the curl timeout option added in 1.3.6. Thanks Chris.

= 1.3.6 =
* Added author_url and author_name tags for widget template
* Added curl timeout when retreiving the data to not lock the page if Flickr is down.
* Added "default template" button to go back to the default template if you've messed things up.

= 1.3.5 =
* Fixing bug introduced in 1.3.4 where the widget would always display 1 random picture. Noob error sorry...

= 1.3.4 =
* Added possibility to have 1 random picture out the 20 returned by flickr. In the dropdown for the number of pictures select "Random (1)". You will probably also want to disable caching with this option.

= 1.3.3 =
* Implementation change: now using cURL instead of deprecated magpie RSS parser. Falling back to file_get_contents if no cURL found.
* Bug fix: cache problem that was showing always the same pics. 

= 1.3.2 =
* Bug Fix: when multiple sizes where used in the template, only the first occurrence was taken into account

= 1.3.1 =
* wrong cache duration, set to 1 hour now

= 1.3 =
* Improved caching system. The cached pictures should be deleted automatically when the cache expires. You will need to manually clean up the old cache files if you are upgrading from previous version and where using the cahcing system. Backup before upgrade!
* minified javascript in admin head
* code change to make it a bit more OOP

= 1.1 =
* Fix a bug in source dropdown. When a user was selecting a source for the first time, before saving the widget, some input fields where not hiding correctly. 

= 1.0 =
* First release
