=== Promotion Slider ===

Contributors: woodent
Donate link: http://www.orderofbusiness.net/payments/
Tags: slideshow, promotions, slider, javascript slider, jquery slider, carousel, featured content, news, gallery, banners, image rotation, images, rotate, auto, autoplay, shortcode, slide, ad, ad gallery, advertisement, ads, media, pictures, custom post types, thumbnails
Requires at least: 3.0
Tested up to: 3.1
Stable Tag: 3.3.1

Promotion Slider is a jQuery slideshow populated by the promotions you enter into the WordPress admin area.


== Description ==

Promotion slider is a jQuery slider that makes it easy to insert a simple slideshow, or implement multiple rotating ad zones, on a webpage.  Because it is highly-customizable, you are in complete control of what shows on the slider, what shows on your promotion pages and how it all works.  A simple options page and straight-forward shortcodes provide great flexibility to the average user, while power users can take advantage of special actions and filters built into the plugin to add their own customizations.

[youtube http://www.youtube.com/watch?v=z5Zz0GK-9G0]

You can easily create promotions in the WordPress admin area; complete with a title, content and image.  The image displays in the slider, and when a user clicks on the image, they are taken to the full promotion page.  Designed with SEO in mind, this slider can be integrated anywhere on your blog or website.

[Install this plugin now!](http://coveredwebservices.com/wp-plugin-install/?plugin=promotion-slider)

This plugin features: 

* Easy creation and management of promotions within the WordPress admin.
* Automatic inclusion of featured images attached to promotions.
* Creation of unique pages with a unique URL for each promotion.
* SEO friendly jQuery animation that can be viewed on most mobile devices.
* Slider navigation that makes it easy for users to find and click on the promotion of their choice.
* A selection of different default styles to choose from.
* Supports the option to link to external URLs instead of promotion pages from the slider.
* Optionally display third party ad code in a slider panel.
* Ability to display only a certain post type or limit to posts from a certain category.
* Customizable time delay and the ability to disable automatic slide advancement.
* Built-in support for displaying a title and/or excerpt for each post or promotion.
* The ability to display multiple sliders on a single page without conflicts.
* Works with any custom post type.
* Plenty of hooks available to advanced WordPress users.

This plugin is provided as-is, but [paid support for this plugin is available](http://www.orderofbusiness.net/request-quote/).

If you have feature requests for this plugin, [please let us know](http://www.orderofbusiness.net/contact-us/plugin-feedback/)!


== Installation ==

1. Upload the `promotion-slider` folder to the `/wp-content/plugins/` directory.
2. Go to the 'Plugins' page of your WordPress administration area and activate the plugin.
3. Use the shortcode `[promoslider]` in the content area of a page or post where you want the image slider to appear.
4. Create your promotions by clicking on 'Promotions' in your administration menu and selecting 'Add New'.  *The slider uses ONLY the featured image attached to any given promotion.*
5. Visit the 'Permalinks' page under 'Settings' in your administration menu.  No need to do anything, just visit the page.  This will ensure that you can visit the promotion pages when you click on an image in the slider. *If you skip this step, your promotion pages will return a 'Page not found' error!*

== Frequently Asked Questions ==

= How do I insert the Promotion Slider? =

You can insert the Promotion Slider by pasting the shortcode `[promoslider]` into the content area of a page or post.  **Be sure to use the HTML editor when inserting shortcodes!**  Also, be aware that if you don't have any published promotions, the slider will not appear.  To customize your slider, there are optional attributes that can be used with the shortcode.

= What are the optional attributes that can be used with the shortcode? = 

There are several attributes that are supported by the `[promoslider]` shortcode:

1. **id** - You can assign a promotion slider its own HTML id attribute so you can easily customize the CSS for a particular instance of the slider.  Example: `[promoslider id="my_id"]`
2. **width** - Set the width of the carousel to fit your needs. By default, the width of the slider will automatically fit the space it is given. You can define the width using pixels or a percentage.  Example: `[promoslider width="600px"]` OR `[promoslider width="90%"]`
3. **height** - Set the height of the carousel to fit your needs.  By default the height of the slider is 235px.  It is best to define the height of the slider using pixels.  Example: `[promoslider height="300px"]`
4. **post_type** - You can display any post type in the slider, including custom post types.  Most users will probably just use the built-in 'promotion' post type and the default WordPress 'post' post type.  The 'promotion' post type is default, so you would only need to specify this if you want to display your standard WordPress blog posts.  Example: `[promoslider post_type="post"]`
5. **category** - You can choose to display only posts from a particular category, regardless of which post type you are pulling from.  Please note that if a category doesn't exist, all posts will show in the slider.  If there are no posts in an existing category, the slider will not show at all when using this attribute.  Example: `[promoslider category="my_category"]`
6. **numberposts** - The numberposts attribute allows you to set the number of posts to display in the slider.  The default is -1 and will show all available posts. Example: `[promoslider numberposts="3"]`
7. **start_on** - This attribute allows you to set which slide the slider starts on when the page loads.  This attribute accepts the values 'random' and 'first'. Example: `[promoslider start_on="first"]`
8. **auto_advance** - The auto advance attribute allows you to override the site-wide settings and either allow or disallow the automatic advancement of slides.  This attribute accepts two values: auto_advance and no_auto_advance. All sliders will auto advance by default. Example: `[promoslider auto_advance="false"]`
9. **time_delay** - You can set the time delay from the options page, but this shortcode attribute allows you to override any site-wide settings and set the time delay for an individual slider.  If the time delay is less than 3 seconds or more than 15 seconds, the time delay will default to 6 seconds.  Be sure that you only use an integer when setting this value.  Example: `[promoslider time_delay="10"]`
10. **display_nav** - You can choose whether or not to display the slider navigation and, if so, what it should look like.  The shortcode value overrides any settings on the options page.  Accepted values include: none, default, fancy, links and thumb.  Example: `[promoslider display_nav="links"]`
11. **display_title** - You can choose whether or not to display the title and, if so, what it should look like.  The shortcode value overrides any settings on the options page.  Accepted values include: none, default, fancy.  Example: `[promoslider display_title="default"]`
12. **display_excerpt** - You can choose whether or not to display the excerpt. The shortcode value overrides any settings on the options page.  Accepted values include: none, excerpt. Example: `[promoslider display_excerpt="excerpt"]`
13. **pause_on_hover** - This attribute allows you to pause the slider when the mouse is over it.  Accepted values include: pause, no_pause.  Example `[promoslider pause_on_hover="pause"]`

You can combine all of these attributes together as needed.  Example: `[promoslider id="my_id" post_type="post" category="my_category" width="600px" height="300px" time_delay="8" numberposts="3" display_nav="thumb" pause_on_hover="pause"]`

= How do I create promotions? =

Promotions can be created in the WordPress administration area by clicking on 'Promotions' in the navigation and then selecting 'Add New Promotion'.  

You will be able to provide a title, content, category, and optonal URL, as well as a featured image.  **The featured image will be used in the slider**, and the title and content that you create will display ONLY on the promotion page when a user clicks through from the slider image.  You can enable the display of other content on the slider if desired. For detailed instructions, see 'How can I enable the display of additional content in the slider?'. 

Images can be placed within the content area of the promotion without affecting the slider, and featured images will not display on the promotion pages. If you set the optional URL, the slider will link to that URL rather than the promotion page.

You can optionally create categories for your promotions by clicking on 'Promotions' in the navigation area and then selecting 'Categories'.  You can create sliders that will only display certain categories using the shortcode attributes.

= How can I enable the display of additional content in the slider? =

You can enable the title and/or excerpt display from the slider options page.  The 'Slider Options' page is under 'Promotions' in the left hand navigation in the WordPress admin area.  You can also enable the display of the title and/or excerpt using the shortcode attributes.

Enabling the excerpt while the default slider navigation is active will result in some overlap of the two elements.  I would recommend switching to the navigation links instead. You can also disable the slider navigation altogether from here if you wish.

Note: By using the 'id' attribute in the shortcode, you can assign all instances of the promotion slider a unique id.  This will allow you to customize the look and feel of individual sliders by editing the css in your theme's stylesheet.

More advanced users might want to display extra content on one slider, but not on another.  This can be done by using the id attribute available in the shortcode and using the action hooks to add custom functions.  Here is an example:

>	`function my_slider_content($values){
	  extract($values);
	  if( $id == 'my_unique_id' ){
		add_action('promoslider_content', 'promoslider_display_title', 9);
		add_action('promoslider_content', 'promoslider_extra_content');
	  }
	}
	add_action('promoslider_content', 'my_slider_content');
	function promoslider_extra_content(){
	  echo '<< INSERT CUSTOM CONTENT HERE >>';
	}`
>

= How can I change the time delay between slides? =

Just click on 'Promotions' in the WordPress admin area and select the 'Slider Options' page.  You can change the default time delay for all your sliders from here.  To change the time delay for just one instance of a slider, just use the shortcode attributes described previously.

= What if I don't want the slides to automatically advance? =

If you would rather not have the slides advance automatically, users can still browse through the promotions in the slider with the slider navigation.  To disable the automatic advancement of slides, just click on 'Promotions' in the WordPress admin area and select the 'Slider Options' page.  You can disable automatic slide advancement from here.  To change the automatic advancement for just one instance of a slider, just use the shortcode attributes described previously.

= What if I want to show the slider in my sidebar? =

All you need to do is add a text widget to your sidebar and include the shortcode as described earlier.  Most likely, you will need to adust the height of the slider in your sidebar by using the shortcode attribute.  You may also want to use a more space-saving navigation option, or remove the slider navigation altogether.

= What if I don't want to use the shortcode?  Can I hardcode the slider into my theme? =

Hardcoding the slider into your theme is just as simple as using the shortcode.  All you do is insert the following line into your theme where you want the slider to appear:

>`<?php echo do_shortcode('[promoslider]') ?>`

If you want to use any of the shortcode attributes when hardcoding your theme, you may do so like this:

>`<?php echo do_shortcode('[promoslider id="my_id" post_type="post" category="my_category"]'); ?>`

= Can I control the order in which the slides appear? =

By default, the slides appear in order by publication date.  You can change the order by changing the publish date for a promotion.  This is the simplest way, but you can also use the more advanced method below.

We provide a filter which allows you to customize the get_posts() query.  You can use any of the [documented parameters](http://codex.wordpress.org/Template_Tags/get_posts#Parameters:_WordPress_2.6.2B) in your query.  Here is an example of how you could control the order:

>	`function order_promotions_by_title($query){
	  $query['orderby'] = 'title';
	  $query['order'] = 'ASC';
	  return $query;
	}
>	add_filter('promoslider_query', 'order_promotions_by_title');

= How can I change the default linking behaviour for a slider panel? = 

When creating or editing a promotion, you can easily change the linking behaviour for that particular promotion from the meta box displayed below the content editing area.  You can have the links open in a new window, define a custom destination URL and disable the links altogether.

= How can I insert third party ad code into the slider? = 

You can easily insert ad code into the slider by using the meta box below the content editing area when creating or editing a promotion in the WordPress admin.  There is a box where you can insert your third party code and a checkbox which will make it actively display for that particular promotion.  We recommend only using this feature when you know the exact size of the ads that will appear.

= What hooks do you provide in this plugin? =

Here is a list of all the hooks available to advanced users:

1. **before_promoslider** - This action hook is called just before each instance of the Promotion Slider within a wrapper div.  The id of the slider is passed as an argument.  The id will be null if it is not set for a particular slider.
2. **after_promoslider** - This action hook is called just after each instance of the Promotion Slider within a wrapper div. The id of the slider is passed as an argument.  The id will be null if it is not set for a particular slider.
3. **promoslider_content** - This action hook is called within each panel in the Promotion Slider.  We use this hook to populate the content in the slider, including the title, excerpt and image.  The $values argument is available when using this hook and is an array containing the following variables: $id (the HTML id attribute for the current slider), $title (the post title), $excerpt (the post excerpt), $image(the HTML image element for the current post's featured image), $destination_url (the destination URL for the post), $target (the HTML anchor target attribute to be used with the destination URL), $disable_links (a boolean variable that is true when a user wishes to disable all links for a particular post), $display_title (a string indicating whether to show the title and, if so, which one), and $display_excerpt (a string indicating whether to show the excerpt or not).  Reference the shortcode attributes for acceptable values for display_title and display_excerpt.
4. **promoslider_nav** - This action hook is called after all the panels and before the closing div tag for the slider, but only if there is more than one panel and the thumbnail navigation is not being used.  We use this hook to insert the slider navigation. $display_nav is passed as an argument.  See the shortcode attribute for acceptable values.
5. **promoslider_thumbnail_nav** - This action hook is called just before the 'before_promoslider' hook after the slider and before the closing wrapper div tag.  We use this hook to insert the slider thumbnail navigation. Several values are passed as an array: $id(the HTML id attribute for the current slider), $title(the post title), $thumbs(a collection of thumbnails to be used for the navigation) and $width(used to match the thumbnail nav witdth to that of the slider).
6. **promoslider_query** - This filter is applied to the get_posts() query for each slider before it is run.  This hook can only be used to make changes to all the sliders on the site because the $query variable is all that is passed as an argument.
7. **promoslider_query_by_id** - This filter is applied to the get_posts() query before it is run and after the promoslider_query filter.  The argument passed to this filter is an associative array containg the query and id.  You can use the PHP extract() function to easily gain access to the $query and $id variables.  Be sure to return the $query within an associative array at the end of your filter.  The purpose of this filter is to allow you to change the query for a slider with a particular id.
8. **promoslider_custom_query_results** - This filter allows you to return the results object of your own custom query.  The purpose of this filter is so users can bypass the get_posts() function and run more advanced custom queries.
9. **promoslider_image_size** - This filter allows you to change the default image size in the slider.  The default value is the string 'full'.  The value passed through this filter is directly applied to the $size argument in the get_the_post_thumbnail() function.  See the WordPress codex for more information on acceptable values and functionality.
10. **promoslider_image_size_by_id** - This filter allows you to change the default image size in the slider based on the slider's id.  This filter passes an associative array containing the $id and $image_size values.  The $image_size must be returned within an associative array.  The value passed through this filter is directly applied to the $size argument in the get_the_post_thumbnail() function.  See the WordPress codex for more information on acceptable values and functionality.
11. **promoslider_thumb_size** - This filter allows you to change the default thumbnail size used in the slider thumbnail navigation.  The default value is the string 'thumbnail'.  The value passed through this filter is directly applied to the $size argument in the get_the_post_thumbnail() function.  See the WordPress codex for more information on acceptable values and functionality.
12. **promoslider_add_meta_to_save** - This filter is applied to the data array prior to processing and saving the promotion meta data.  It is designed for developers who need to save additional meta data to the promotion custom post type after having created a custom meta box.  The $data variable is available when using this filter and contains an array of all the meta keys to be saved when a promotion is created or updated.

== Screenshots ==

1. An example of the Promotion Slider with a default installation.
2. An example of the Promotion Slider with the title display enabled.  This slider is using the default styling for the title.
3. An example of the Promotion Slider with some minor customizations.  By following the instructions in the FAQ section, you can make your customizations upgrade-proof.
4. An example of the Promotion Slider using the alternate navigation links.
5. A look at the 'add new promotion' screen.  You can see options available under the promotions menu item on the left.  The 'Set featured image' link on the bottom right is how you upload an image to the slider.
6. An example of the Promotion Slider using the new thumbnail image navigation.

== Changelog ==

= 3.3.0 =
* Simplified the plugin options page so that it is less confusing. Added a sidebar with helpful information.
* Added several attributes to the shortcode: display_nav, display_title, display_excerpt and pause_on_hover.
* Allowed users to optionally pause the slider when the mouse is over it.
* Added a category column and filter to the promotions listing page.
* Added filters for image sizes.
* Added filter to allow for more complex queries, so users aren't forced to use get_posts().
* Added ability for slider to pull images from a URL, intended for use with a CDN.
* Added the ability to filter image and thumbnail sizes.
* Added the option to load the plugin javascript in the header rather than the footer.
* Added contextual help to all of the plugin admin panels.
* Cleaned up the code and created created more advanced handling of new and legacy options.
* Made sure that the activation function will run when the plugin is updated.
* Added a version check for PHP and WordPress so users will get an error message on activation.
* Fixed a bug where the slider thumbnail nav was pulling full sized images.
* Fixed a bug where the slider would disallow comments on the page where it was inserted.

= 3.2.3 =
* Fixed bug where promotions would disappear on upgrade.

= 3.2.2 =
* Fixed a bug that prevented users from pulling posts from a particular category.

= 3.2.1 =
* For those of you getting this error: 'Fatal error: Class 'ps_legacy_options' not found', this update should get you back up and running.

= 3.2.0 =
* Overhauled the code to make it more modular and easier to manage.
* Ensured proper support for post thumbnails and made the plugin load last so other plugins can't overwrite my theme support.
* New install runs an update on the post type and changes it to a properly namespaced post type.
* Condensed individual plugin options into a single array after transferring previous settings.  Plugin removes legacy options afterward.
* Wrapped default navigation separators in <b> tags with their own class for easy styling.
* Wrapped each instance of the slider in a <div> to allow better use of the before_promoslider and after_promoslider hooks.
* Added option to start slider on the first or a random slide when the page loads. This can be set site-wide from the options page or on a per slider basis with the shortcode.
* Fixed a bug where auto advance was enabled if the panel count was set to one.
* Updated the title attribute for the span elements in the nav to use the post title.
* Added a new thumbnail navigation layout.
* Removed the grey background image from the slider div and added it to the panel divs to avoid the slightly odd transition between fade in and fade out.

= 3.1.0 =
* Added the ability to easily insert third party ad code into the slider using the meta box at the bottom of the promotion editing page.
* Added the 'numberposts' attribute to the promoslider shortcode so users can set the number of posts or promotions to display.
* Fixed a bug from version 3.0.1 where the slider would only pull posts and not promotions.
* Fixed a bug where the slider would not properly display posts of a specific category when using the default WordPress post type.

= 3.0.1 =
* Removed an invalid argument from the promoslider_query filter.
* Added the promoslider_query_by_id filter for more advance customization of individual sliders.
* Updated the readme.txt file to reflect the changes to the hooks and the need to use the word 'echo' prior to the do_shortcode() function.
* Added an argument to the default get_posts() query to remove the post limit.
* Slimmed down the code used for loading our jQuery and CSS files and ensured they only load for the front end of the site.
* Fixed some minor, behind-the-scenes, notices when creating a new promotion.

= 3.0.0 =
* Cleaned up the jQuery code to be more compact and made sure that it properly handles values for individual slider settings.
* Fixed the issue where additional sliders would not automatically advance.
* Added ability to set the width, height, time delay and auto advance using the shortcode.
* Added action hooks before and after the slider.
* Fixed the way that the shortcode is output on the page.  The shortcode content will now display exactly where it is put on the page.
* Added an alternate slider navigation option with custom css.
* Ensured that all text is prepared for I18n processing later.
* Added option to disable all links for a given promotion.

= 2.2.3 =
* Properly activated support for post thumbnails within the plugin for users whose themes do not support it.
* Added the following options to the options page: Enable title display, enable excerpt display, disable slider navigation display.

= 2.2.2 =
* Replaced bad function call apply_filter() with apply_filters()

= 2.2.1 = 
* Fixed issue where slider would not advance unless the disable auto advancement option was set.
* Fixed issue where slider would not progress because time delay option was not set.

= 2.2.0 =
* Added an options page where users can change the time delay and disable the automatic slide advancement.
* Reset the time delay when a user clicks on a navigation link in the slider.
* Fixed issue where the back button on the slider nav would perform the action for all sliders on the page.
* Fixed the issue where forward and back buttons on the slider nav would progress slides in the wrong direction.
* Added some basic CSS to highlight which image the user is viewing in the slider nav.
* Added support for all post types, including custom post types.
* Added a query filter to allow advanced users to customize the slider even more.
* Added the 'promoslider_add_meta_to_save' filter for easier saving of promotion meta; for users who may add their own meta boxes.
* Changed the functionality so that if a requested category does not exist, all promotions/posts are shown.
* Changed the way values are passed to the 'promoslider_content' action.  Values are now passed as an associative array for ease of use and flexibility in future updates.
* Added a 'Support this Plugin' box to the options page.

= 2.1.0 = 
* Added automatic flushing of rewrite rules via 'register_activation_hook' (still needs work)
* Replaced content of slider panels with an action hook.
* Created actions that allow users to easily add or remove the title, excerpt or image
* Replaced slider navigation with an action hook.
* Created an action so users can easily add or remove the slider navigation.
* Updated CSS to include default styling for the title and excerpt.
* Simplified instructions for hardcoding the slider in a theme.
* Updated processing of post meta so that empty meta box values are removed from the postmeta table.
* Updated the readme.txt file to reflect changes relating to the action hooks.

= 2.0.0 =
* Added ability to use external links with slider promotions.
* Added a custom taxonomy for the promotion post type.
* Added ability to use the promotion slider with the WordPress default post type.
* Added ability to show only promotions or posts from a particular category.
* Added attributes to shortcode.
* Added ability to display multiple instances of the promotion slider on a single page.
* Added ability to easily change the time delay and disable automatic slide advancement.
* Provided detailed instructions on how to display the title and/or excerpt in the slider.

= 1.0.0 =
* Promotion Slider is now available for download!

== Upgrade Notice ==

= 3.3.0 =
Added several shortcode attributes and plugin options.  Made the options panel more clear, added a category filter to the promotions listing and fixed a few minor bugs.

= 3.2.3 =
Fixed a bug that caused existing promotions to be hidden after upgrading.

= 3.2.2 = 
Fixed a bug that prevented users from displaying posts from particular categories.

= 3.2.1 = 
Fixed a class loading issue that affects a portion of the users.

= 3.2.0 =
Added a new thumbnail navigation feature.  Fixed a few minor bugs and implemented some minor feature requests.

= 3.1.0 =
Added ability to insert third party ad code into the slider.  Fixed a major issue with version 3.0.1 where the slider would only show posts instead of promotions.

= 3.0.1 =
Fixed a bug where sliders would only show 5 posts.  Made the code a bit more efficient and updated the documentation.

= 3.0.0 =
Plugin now comes with two different slider navigation styles. Added width, height, time delay and auto advancement as shortcode attributes.

= 2.2.3 =
Added some slider display options to the slider options page. Added support for post thumbnails for users whose themes don't support it.

= 2.2.2 = 
Replaced a bad function call.

= 2.2.1 =
Fixed a couple of bugs that prevented slider from working properly.

= 2.2.0 = 
Users can now easily set the time delay and disable the automatic advancement of slides.  A lot of advanced functionality has been added allowing power users to infinitely customize their sliders.

= 2.1.0 = 
The latest version makes it very simple to customize the slider to display only the content you want to appear.  You can easily add or remove the image, title, excerpt or navigation for the Promotion Slider.

= 2.0.0 =
The latest version allows you to specify link targets instead of having the promotions always link to promotion pages.  You can display promotions or WordPress's default posts in the slider and can limit posts by category.  Support added for the display of title and/or excerpt in the slider.