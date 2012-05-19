=== oik-nivo-slider ===
Contributors: bobbingwide
Donate link: http://www.oik-plugins.com/oik/oik-donate/
Tags: shortcode, Nivo, slider, [nivo], oik, jQuery, slideshow, posts, pages, attachments
Requires at least: 3.3
Tested up to: 3.4
Stable tag: 1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

[nivo] shortcode for the jQuery "Nivo slider" for posts, pages, attachments and custom post types using oik

== Description ==
The [nivo] shortcode implements the jQuery Nivo slider, which is considered by some to be "The Most Awesome jQuery Image Slider". 

The [nivo] shortcode can be used to display a variety of slideshows with/without links.   
It can be used to direct the user to any post/page/custom content type.

The [nivo] shortcode uses the oik plugin's shortcode API for *lazy smart* shortcodes.

Note: oik-nivo-slider is dependent upon the oik plugin. 
You can activate it but it will not function correctly unless the pre-requisite version of oik is also activated.
Download oik from 
[oik download](http://wordpress.org/extend/plugins/oik/)

[Find out more](http://www.oik-plugins.com/oik-plugins/oik-nivo-slider/)

See also [dev 7 studios](http://nivo.dev7studios.com) for more information about the jQuery code,


== Installation ==
1. Upload the contents of the oik-nivo-slider plugin to the `/wp-content/plugins/oik-nivo-slider' directory
1. Activate the oik-nivo-slider plugin through the 'Plugins' menu in WordPress
1. Whenever you want to produce a Nivo slider use the [nivo] shortcode.

Note: oik-nivo-slider is dependent upon the oik plugin. You can activate it but it will not work unless oik is also activated.
Download oik from 
[oik download](http://wordpress.org/extend/plugins/oik/)

== Frequently Asked Questions ==

= What is the simplest syntax for the [nivo] shortcode? = 
If you simply want to show all the attached images to a page, post or custom post type then use
`[nivo]`

= I typed [nivo] and got [nivo] back =
You need to activate both the oik-nivo-slider plugin and the oik base plugin. The [nivo] shortcode only becomes functional when [oik] is loaded.

= What are the parameters to the [nivo] shortcode? =
The basic parameters that control the display of the Nivo slider are:

`[nivo
  theme="default|orman|pascal|oik - Theme for the slideshow"
  link="y|n - Link the images to the target post/page"
  caption="y|n - Display the image title as the caption"
  ribbon="y|n - Display the ribbon, if the theme supports it"
  thumbnail="full|thumbnail|medium|large|nnn|wxh - image size"
  class="|classes - CSS classes"
]`

= How do I make the images link to my content? = 
Use the post_type parameter to specify the content type and either the post_parent parameter for hierarchical content types or category for posts or other selection criteria for other content types

`[nivo
  post_type="post_type - Post type to display"
  post_parent="|ID - Parent ID to use if not current post"
]`


= What is the FULL syntax for the [nivo] shortcode = 
`[nivo
  post_type="attachment|post_type - Post type to display"
  theme="default|orman|pascal|oik - Theme for the slideshow"
  class="|classes - CSS classes"
  link="y|n - Link the images to the target post/page"
  caption="y|n - Display the image title as the caption"
  ribbon="y|n - Display the ribbon, if the theme supports it"
  thumbnail="full|thumbnail|medium|large|nnn|wxh - image size"
  numberposts="5|numeric - number to return"
  offset="0|numeric - offset from which to start"
  category="|category-slug - category slugs (comma separated)"
  customcategoryname="|category-slug - custom category slug"
  orderby="date|ID|title|parent|rand|menu_order - Sort sequence"
  order="DESC|ASC - Sort order."
  include="|id1,id2 - IDs to include"
  exclude="|id1,id2 - IDs to exclude"
  meta_key="|meta key - post metadata key"
  meta_value="|meta value - post metadata value"
  post_mime_type="|image|application|text|video|mime type - Attached media MIME type"
  post_parent="|ID - Parent ID to use if not current post"
  post_status="publish|pending|draft|auto-draft|future|private|trash|any - Post status"
]`

= Do I have to remember all those parameters? =
NO. You can enable the oik shortcode buttons and/or oik quicktags plugin. When editing a post/page with TinyMCE or the HTML editor click on the shortcode button to see a list of ALL enabled shortcodes and get syntax help, where available.


= What's OIK and why do I need it? = 
The oik Nivo slider is developed using the OIK (Often Included Key Information) API (Application Programming Interface).
If you don't have the OIK plugin installed and activated then the Nivo slider shortcode won't work.

Specifically, the code is dependent upon bw_get_posts() to obtain the list of attachments, posts, pages or custom post types
which populate the slider and bw_thumbnail() to select the image to display when it's not an attached image. 

= I can't see some of the images in my slideshow =
The default image size is thumbnail=full.
At present the bw_thumbnail() function will only return an image for a particular post id
when there is an attached image; it won't find the featured image. As a workaround either specify the thumbnail parameter as small,medium,large or your preferred size (e.g. 150x100)
OR ensure that the image you want to display is attached to the post
OR exclude the post from the list ( exclude=id1,id2 )


= Do I need to make my images the same size? =
YES. There is no auto cropping. 
Remember: You can use Add Media Edit Image after you have uploaded your files. This can be used to scale and crop images.

= Can I control the slideshow transitions? =
YES. Use the Nivo slider settings page.

= Can I provide my own themeing? =
This is planned for a future version.

= Can I put the [nivo] shortcode in my sidebar? =
YES. You can use the [nivo] shortcode in sidebars, headers and footers
as well as in posts, pages, and custom post types.
Remember you may need to set the post_parent parameter to control which posts are loaded.


= Which version of the jQuery Nivo slider code is needed? = 
The plugin includes the FREE jQuery Nivo slider v2.7.1 from http://nivo.dev7studios.com/features/
excluding the demo files.
There are some minor additions to the nivo-slider.css file to support additional function provided by the oik Nivo slider

= Which version of jQuery does it use? =
It uses the jQuery library provided by WordPress 


= What's the difference between this plugin and Nivo's WordPress plugin =
I have not tried Nivo's plugin. But you can read about it and watch a video here
http://nivo.dev7studios.com/features/#wordpress


= Can you tell me more? =
YES. See [oik-nivo-slider](http://www.oik-plugins.com/oik-plugins/oik-nivo-slider/)


== Screenshots ==
1. Nivo slider with the default theme. [nivo]
2. Nivo slider with the Orman theme [nivo theme=orman]
3. Nivo slider with the Pascal theme and NO captions [nivo theme=pascal caption=n]
4. Nivo slider with the oik theme - not hovered over [nivo theme=oik]
5. Nivo slider with the oik theme - image hovered over
6. Nivo slider with the oik theme - caption hovered over
7. oik Nivo slider options page

== Upgrade Notice ==
= 1.3 =
* Please upgrade to oik-nivo-slider v1.3 when you upgrade to oik v1.13. A change in oik's bw_jquery() function could have caused slideshow images to be hidden.

= 1.2 = 
* Please upgrade oik to at least version 1.12 before installing oik-nivo-slider v1.2
* The minimum requirement for a WordPress Multisite solution is oik version 1.12.1     

* In order to allow you to change the default slideshow behaviour I needed to add some new functions to oik
* Rather than make the functions pluggable I decided it was better to improve the plugin dependency logic
* So version 1.2 is now dependent upon oik version 1.12
* If you have oik version 1.11 or earlier then the plugin will activate and issue warning messages
* BUT it will not work!
* If you are installing into a WordPress MultiSite environment oik version 1.2.1 is needed
  
= 1.0 =
* first version works with oik version 1.11

== Changelog ==
= 1.3 = 
* Added: Link to any content from attached images 
* Fixed: calls bw_jquery() setting $windowload parameter to true... the default in oik v1.13 is false
* Fixed: can't click on captions - undefined variable oik_nc_script

= 1.2 =
* Added: Default slideshow options can be configured
* Added: post_mime_type parameter defaults to "image"
* Added: option to display "screenshot" files from installed plugins [nivo post_type="screenshot:oik-nivo-slider"]
* Changed: The example logic will display the oik-nivo-slider screenshots
* Changed: oik-nivo-slider will now produce warning messages when the pre-requisite version of the oik plugin is not satisfied
* Changed: BUT it WILL crash if you try to run it against oik version 1.11
* Fixed: Corrected the name of the oik base plugin when using Plugin Dependencies
* Fixed: minimum version of WordPress required is 3.3
* Fixed: Added a workaround to allow oik version 1.12 on single site, but needing oik v1.12.1 for MultiSite 

= 1.1.1 =
* Fixed: Correction for Fatal error due to _sc_thumbnail() not being defined... it's in the next version of OIK.

= 1.1 =
* Added: link=n parameter for when you just want a slideshow
* Added: caption=n parameter for when you want plain pictures
* Added: ribbon=n parameter to disable the ribbon image shown in some themes
* Added: oik theme - demonstrating CSS for hovering over the caption
* Changed: corrections and updates to this readme.txt file 
* Changed: Uses the the minified jQuery Nivo slider library by default.
* Fixed: Uses the metadata to find the filename for the attached image

= 1.0 =
* initial version. Works with oik version 1.11 


== Further reading ==
If you want to read more about the oik plugins then please visit the
[oik plugin](http://www.oik-plugins.com/oik) 
**"the oik plugin - for often included key-information"**

