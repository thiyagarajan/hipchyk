=== Plugin Name ===
Contributors: c3mdigital
Tags: coda slider, featured content, featured content slider, jquery, coda slider, js, no conflict, shortcode, coda, panic slider
Donate link: http://c3mdigital.com/donations/
Requires at least: 3.0
Tested up to: 3.4
Stable tag: 0.3.3.2

Add a jQuery Coda Slider to any WordPress post or page configured with custom metabox options or shortcodes

== Description ==

WP Coda Slider is a plugin that adds Niall Doherty's jQuery Coda-Slider v2.0 plugin to WordPress using <del>shortcodes</del> a custom metabox on the post edit screen with all options to insert a slider.


= Demo: =

The WP Coda Slider Demos can be found at http://c3mdigital.com/wordpress/wp-coda-slider/demo

= Metabox Options =

All options to configure individual sliders are available through custom meta boxes on all WordPress posts and pages

= Shortcode: =

The short code accepts the following arguments: id, cat, show, args.
id= a unique name for each slider that will be assigned as the div id
cat= the category containing the posts to display in the slider
show= the number of posts to show in the slider
args= the settings for the slider which can be found at http://www.ndoherty.biz/forums/viewtopic.php?f=4&t=2
*Please Note:* the args in the shortcode must be wrapped with double quotation marks to work.

= Example: =

`[wpcodaslider id=myslider cat=4 show=6 args="autoSlide:true, dynamicTabs:false, autoSlide:true"]`

This would add a slider with the `<div id="myslider">` showing 6 posts from the category id of 6 with dynamic tabs set to false, auto slide set to true with an autoslide interval of 8000 milliseconds.

= Template Tag =

Add `<?php if ( function_exists('c3m_wpcodaslider') ){
               c3m_wpcodaslider($id, $cat, $show, $args);} ?>`

to any of your themes templates.

you must supply the variables when you add the function to your template.

`<?php if ( function_exists('c3m_wpcodaslider') ) {
               c3m_wpcodaslider('myslider', '81', '4', 'dynamicArrows:false');} ?>`

this would add a slider with the id of myslider and show 4 posts from category 81 with dynamic arrows set to false.
all the variables must be present and in the same order.


== Installation ==

1. Upload the `wp-coda-slider` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add shortcode to any post or page you want to display your slider on. Make sure to specify the category id, slider id(each slider requires you to give it a unique id) and the number of posts to show.
ie: `[wpcodaslider id=myslidername cat=4 show=3 args="autoSlide: true"]` This would display a slider containing the first 3 posts in a category with the id of 4 with autoslide set to true.

Shortcode & Template tag args:

the shortcode args except any of the coda slider jQuery settings shown below.  Please note: the args must be wrapped in quotation marks to work ie:  args=" ".

For a full list of slider settings see http://www.ndoherty.biz/forums/viewtopic.php?f=4&t=2


== Frequently Asked Questions ==

= Whats new in version 0.3? =
0.3 is a complete code rewrite. You will now find a metabox on your post and page screens that give you complete control of all the slider options.  Once configured the slider will be automatically added to the page.
CSS options:  You can control the css using the options and can even include a block of custom css.

shortcodes are deprecated but will still work as before

existing template tag function has been deprecated and replaced with a new one.  The new one takes the arguments as an array.

=  What does the id argument do? =

The id argument is the div id that will be assigned to the slider.  Make sure each slider has a unique id.

= What is the cat argument? =
This is the WordPress post category the slider will pull from and display in your slider.

= Where can I use the shortcode? =
The short code will work on posts, pages, and Wordpress 3.0 custom post types.

= How can I change the style of the slider or modify the width? =
Open the coda-slider-2.0.css located in the css folder of the plugin and make any changes you wish.  The CSS file is well documented on where changes can be made.

= Where can I find all the values available for the shortcode args? =
The full description of the shortcode arguments can be found on the orginal jQuery plugin page at: http://www.ndoherty.biz/forums/viewtopic.php?f=4&t=2

= Where can I get support or help? =
Support will be provided on the WordPress.org support forums and may also ask for support at http://wp-performance.com/wp-coda-slider/

== Screenshots ==
See demos at:   http://wp-performance.com/wp-coda-slider/

1. The slider and css options for each slider
2. The coda slider options

== Changelog ==

= 0.3.3.2 =
* Fixed javascript bug
* Added additional options

= 0.3.2 =
* Complete code rewrite.  All slider options are now available through a custom metabox added to each WordPress post and pages.
* jQuery easing plugin enqueued separately to avoid conflict with other plugins that use jQuery easing
* shortcodes have been deprecated.  They will still work as before but will no longer be supported
* template tag function has been deprecated.  It will still work as before but will no longer be supported.  Next version will include a new template tag function

= 0.2.5 =
* Fixes the shortcode inside a shortcode bug
* Props:  Morten Ydefeldt

= 0.2.4 =
* Updated query strings to arrays.  This won't change anything for most users.

= 0.2.3 =
* Bug fixes:
* Fixed documentation error for template tag function call Props:Bira
* Fixed path to ajax-loader.gif Props:shootingstar.co.uk
* Added direction:ltr; to css for compatability with rtl languages Props:Bira

= 0.2.2.1 =
* Add a template tag method to call the slider to use when calling posts that contain other shortcodes.

= 0.2.1 =
* Added the description for the arguments in the shortcode

= 0.2 =
* Fixed the readme file to display full description


== Upgrade Notice ==

= 0.3.3.2 =
* Fixes javascript errors.  Please upgrade if you are on 0.3.3 or above!

= 0.2.2.1 =
* Upgrade to have option to use template tags or shortcodes.  Using template tags allows posts containing other shortcodes to work.

= 0.2.1 =
* Please upgrade and check the readme.txt file for a full description on using the plugin shortcode arguments

= 0.1 =
* Hey this is the first version.  No need to upgrade until a new version comes out