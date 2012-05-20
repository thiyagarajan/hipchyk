=== CataBlog ===
Contributors: macguru2000
Donate link: http://catablog.illproductions.com/donate/
Tags: plugin, admin, image, images, posts, Post, widget, links, catalog, gallery, portfolio, product catalog, discography, library, collection, store, organize, media, photo, thumbnail, product, listing, list, BuddyPress, ecommerce
Requires at least: 3.3
Tested up to: 3.3.1
Stable tag: 1.6.3

CataBlog is a comprehensive and effortless tool that allows you to create catalogs, stores and galleries for your blog.

== Description ==

CataBlog allows you to catalog pretty much anything you would want and share it through your blog in a simple but elegant gallery. Upload images, give them titles, links, descriptions and then save them into your catalog. Use categories to organize and filter your catalog items into multiple different catalogs. Show off your photos in high resolution with the LightBox effect, perfect for simple galleries. Easy, intuitive and smart design makes it trivial to keep track of all your different catalogs and create amazing e-stores, galleries, lists and more.

PLEASE DO NOT EMAIL ME DIRECTLY

Instead post your questions and comments on the appropriate forums. If you have a technical support question, please [write a new topic](http://wordpress.org/tags/catablog?forum_id=10#postform "Create new support thread for CataBlog") on the wordpress.org forums. If you want to join the discussion and suggest new features or development paths, feel free to comment on relevant posts at the [blog](http://catablog.illproductions.com/ "catablog.illproductions.com"). You may also contribute to the community on the [facebook page](http://www.facebook.com/pages/CataBlog/183454038341567 "CataBlog Facebook Page").

Highlighted Features:

* Easily format your catalog descriptions with the WYSIWYG TinyMCE editor.
* Organize your catalog library into galleries with any order you want.
* Widgets for displaying your catalog and catalog categories in sidebars.
* Automatic pagination with a ShortCode limit parameter.
* Set separate height and width values for catalog thumbnails.
* Generate individual and category pages for your entire catalog.
* Filter by multiple categories with one ShortCode.
* Localized for Spanish, French, Swedish and German.
* Sort your catalog by order, title, date or randomly.
* Add multiple images to a catalog item.
* Control exactly how your catalog HTML code is rendered.
* Import and Export your catalog in XML and CSV formats.
* Compatible with WordPress MultiSite and Network Activation.
* Upload images with FTP and automatically import new files into the catalog.
* The Options Page is well organized and supports many configurable settings.
* Easy management of your catalog with superiorly designed admin controls.

Please remember that CataBlog is written, maintained, supported and documented by Zachary Segal. CataBlog is free software, and as such comes with absolutely no warranty or guarantee of service. Please feel free to visit http://catablog.illproductions.com and http://www.illproductions.com for more information about CataBlog and Zachary anytime.

== Installation ==

1. Make sure your server is running `PHP 5` or better and has the `GD` extension.
1. Upload `catablog` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. If you want you may also network activate CataBlog.
1. Create catalog items by uploading image files.
1. Sprinkle the `[catablog]` [Shortcode](http://codex.wordpress.org/Shortcode_API "The Shortcode API") throughout your blog to show your catalog.
1. You may also use the template tag, which operates similarly to the Shortcode: `<?php catablog_show_items($cat, $template, $sort, $order, $operator, $limit, $show_navigation); ?>`

== Frequently Asked Questions ==

= Where is the documentation =

[Click Here](http://catablog.illproductions.com/documentation/introduction-and-installation/ "CataBlog Documentation") for the official documentation.

= Something is broken, how can I get help =

1. Read all the documentation at the [official blog](http://catablog.illproductions.com/ "catablog.illproductions.com"), this does get updated so check back frequently.
1. [Create a new thread](http://wordpress.org/tags/catablog?forum_id=10#postform "Create new support thread for CataBlog") on the wordpress.org forums and make sure to tag it with catablog.
1. Search the [official blog](http://catablog.illproductions.com/ "catablog.illproductions.com") for a post that is relevant to your question and write a comment with your question.

= What browsers do you support =

The CataBlog Admin section is made and tested to work best with these browsers:

1. Internet Explorer 9
1. FireFox 11
1. Safari 5
1. Chrome 17
1. JavaScript should be enabled for full support.

The CataBlog LightBox and Templates are tested to work in these browsers:

1. Internet Explorer 7, 8 and 9
1. Firefox 3 and above
1. Safari and Chrome
1. JavaScript must be enabled for LightBox support.

= I installed CataBlog, now where is it? =

Look for CataBlog in your WordPress Admin Menu on the bottom, underneath the Settings section.

= How do I add a new item to my catalog? =

Login to the Admin Panel of your WordPress blog and go to the CataBlog section by clicking its icon below the Settings section. Now you can click "Add New" next to the page title or in the CataBlog menu itself.

= How do I customize my catalog's layout? =

You can easily override CataBlog's CSS classes to create your own design and easily incorporate CataBlog into your site's layout. The recommended way to do this would be to create a catablog.css style file in your theme's directory and add your CSS override code in there. Read more about it at http://catablog.illproductions.com/documentation/displaying-your-catalog-in-posts/.

= Where can I learn more about CataBlog? =

Go to http://catablog.illproductions.com, it is a place to learn and share more about your experiences with CataBlog.

== Screenshots ==

1. Use the list view for a bird's eye of the entire catalog.
2. Use the grid view for bigger thumbnails and easier sorting.
3. Easy and familiar forms for making and editing catalog items.
4. Turn your catalog into a photo gallery using custom templates.
5. Display your catalog photos in high resolution with the LightBox.
6. CataBlog is now localized for Español.

== Changelog ==

= 1.6.3 =
* Fix: XSS attack vulnerabilities have been hardened.

= 1.6.2 =
* New: Regenerate thumbnail and lightbox images for a selected group of library items.
* New: Delete custom templates directly from the CataBlog Templates Admin Panel.
* Fix: LightBox generated images with transparency will now use the thumbnail background color.
* Fix: Should now save default values for the catablog-view-cookie in the cookie before explicitly being set.

= 1.6.1 =
* New: Flash Upload's Micro Save Form now has configurable fields.
* New: Enhanced catalog navigation links, including jump to page links.
* Fix: Deleting Library items no longer breaks the galleries they were in.
* Fix: Widened the Select Images button in the Add New page for localization.
* Fix: Add to Gallery form removes previously selected library item ids after closing and reopening.

= 1.6 =
* New: WYSIWYG MCE Editor for the catalog item description.
* New: Edit category name and slug.
* New: Added French localization. (Thanks Nicolas)
* Fix: Javascript syntax error fixed in Remove All CataBlog Data script.
* Fix: Forwards browser to the CataBlog interface when a library item is loaded into the standard WP Post editor.
* Fix: Edit library item panels do not overlap on small screens.
* Fix: Category list line up correctly in IE now.
* Fix: Text input boxes shouldn't cut off dangling letters in IE.
* Fix: Modal window's position is now fixed, meaning no scrolling back to the top of the page to use it.

= 1.5 =
* New: Gallery feature for specific custom ordered groupings of catalog library items.
* Fix: CataBlog Options Form did not pass validation when it should have, it passes now.
* Fix: Excerpt length must now be a positive integer to save your options.

= 1.4.8 =
* New: Beta version of the new Galleries feature.

= 1.4.4 =
* Fix: The CataBlog Category Shortcode now returns the HTML code instead of writing it to the main buffer.
* Fix: All CataBlog Shortcodes are now registered in the admin side of WordPress for Relevanssi. (Thanks msaari)
* Fix: Boolean Shortcode attributes properly understand strings 'true' and 'false'.

= 1.4.3 =
* Fix: Corrected a rendering bug in the CataBlog Categories Widget. (Thanks xdmytro)

= 1.4.2 =
* Fix: Updated localization, including a now complete spanish translation.
* Fix: All catalog items are saved with a status of published, even with a future date.
* Fix: Better validation and error checking for the CataBlog Options Admin Panel.

= 1.4.1 =
* Fix: Categories Function now disables itself if the public option is not enabled.
* Fix: Categories Shortcode now disables itself if the public option is not enabled.
* Fix: Categories Shortcode drop down menu now goes to category pages automatically.
* Fix: Categories Widget now disables itself if the public option is not enabled.
* Fix: LightBox description is now a div for increased compatibility.

= 1.4 =
* New: CataBlog Sidebar Widget for displaying your catalogs.
* New: CataBlog Category Sidebar Widget for displaying your catalog's categories.
* New: CataBlog Category ShortCode and PHP Function for better theme integration and easier setup.
* Fix: Import script properly updates entries with a matching id.
* Fix: Import script is more verbose when errors occur.

= 1.3.2 =
* New: Navigation options allow for changing the link labels and position in paginated catalogs.
* New: Template View added for archive listing pages.
* New: Added an %EXCERPT% token with a max length option for archive pages.
* Fix: Create and populate the templates folder before extracting old template data to upgrade from a version before 1.3.

= 1.3.1 =
* New: Added limit and navigation parameters to the PHP function catablog_show_items().
* Fix: Resolved upgrade issue where system template wasn't being migrated properly.
* Fix: Resolved display bugs with selected tabs.

= 1.3 =
* New: Removed PayPal Email from plugin options, you may now set it directly in the store template.
* New: Templates now have their own section with more controls than before.
* New: CataBlog ShortCode now has a limit parameter with basic pagination for large catalogs.
* New: CataBlog ShortCode now has a navigation parameter for hiding a limited ShortCode's next and previous links.
* New: Toggle link to easily enable/disable the flash upload form on the CataBlog Add New page.
* New: Updated look and feel for tabbed panels that match WordPress 3.3.1.
* New: Server's GD Version is now displayed in the about page.
* Fix: CataBlog's WP Admin Bar buttons now appear in the appropriate order for WordPress 3.3.1.
* Fix: Default CSS now removes Theme constrained width and height for gallery thumbnail images.
* Fix: Resolved PHP warnings being fired throughout the plugin when WP_DEBUG is enabled.

= 1.2.9.9 =
* New: LightBox option lets you set if the LightBox scrolls from one ShortCode to the next on a single page.
* Fix: LightBox navigation should behave as expected when secondary images are used.
* Fix: The CataBlog menu is only added to the WP Admin Bar if the current user has proper privileges
* Fix: Default CSS now removes Theme constrained width and height for catalog thumbnail images.

= 1.2.9.8 =
* New: Thumbnail size now supports separate width and height values.
* Fix: Category operator 'AND' should work.
* Fix: LightBox next and previous navigation should stop at the start and end of each Shortcode.
* Fix: Removed item id from the beginning of an item's Permalink.
* Fix: Better looking multiple file upload button.
* Fix: Multiple file upload should work on WordPress installations that have a base path.
* Fix: Changes to the catalog slugs should now be effective immediately.
* Fix: When uploading multiple files the item's order attribute should be set correctly.
* Fix: Bulk menu is now always visible, fixing a jumpy interface on slower internet connections.
* Fix: Errors should be reported when servers runs out of memory rendering images.

= 1.2.9.7 =
* Fix: Multiple file uploads should not give 404 errors with certain WordPress setups.
* Fix: Only loads the flash upload javascript libraries when needed.

= 1.2.9.6 =
* Fix: Better error reporting for flash upload.

= 1.2.9.5 =
* New: Upload multiple files (JavaScript and Flash required).
* Fix: %PERMALINK% token now works.

= 1.2.9.2 =
* Fix: Swedish localization updated.

= 1.2.9.1 =
* Fix: Individual and CataBlog category pages now apply WordPress filters on the description if enabled.
* Fix: CataBlog Option tabs have been enlarged for German language.
* Fix: German localization updated.

= 1.2.9 =
* New: Public Catalog Items: create individual and category pages for your entire catalog.
* New: Screen Options for the Library page give users control over pagination and visible data.
* New: Add CataBlog menu to the WordPress Admin Bar.
* New: Preliminary contextual help added through out the plugins Admin panels.
* New: New template function to get a single catalog item by id, catablog_get_item($id).
* New: German localization.
* Fix: Setting or not setting the category parameter in the Shortcode should behave as expected.
* Fix: %TITLE-LINK% token now includes the title target and relationship settings.
* Fix: %LINK-TARGET% and %LINK-REL% tokens work once again.
* Fix: Image uploads no longer append a timestamp to the file name.
* Fix: Your theme's catablog.css file is once again automatically loaded.
* Fix: CataBlog class should no longer load if PHP is not at least version 5.

= 1.2.8 =
* Fix: Category filter is better checked before being attached to the catalog query.
* New: CataBlog Admin Library limits results to 20 catalog items at a time with pagination.
* New: %CATEGORY% token added for listing which categories a catalog item is in.
* New: %CATEGORY-SLUGS% token added to be used as category specific CSS classes.
* Fix: %DATE% token is now rendered in the blogs set date format.
* New: %TIME% token added for displaying the current catalog item's creation time.
* Fix: Minor UI tweaks, enhancements and localization file updated with new strings.
* New: Works with WordPress 3.2 beta 1.

= 1.2.7.1 =
* New: Swedish localization added

= 1.2.7 =
* New: Bulk Edit Item Categories.
* New: The PHP MultiByte String Library is no longer necessary to run CataBlog, but still recommended.
* New: Replace reset action with remove, allowing for an easy way to uninstall CataBlog.
* Fix: All Admin actions that edit your catalog now check for a security nonce.
* Fix: CSV Export no longer adds an extra header for the nonexistent id column.
* Fix: %MAIN-IMAGE% token will always be linked to its larger size image if the LightBox is enabled.
* Fix: %TITLE-LINK% token is now replaced correctly and in used in the default template.
* Fix: Refactored activation, installation and upgrade methods.
* Fix: LightBox console does not interfere with Internet Explorer browser.
* Fix: LightBox will attempt to load the original upload if the rendered image request returns not found.
* Fix: jQuery selector for the LightBox reverts to .catablog-image if empty.
* Fix: Incrementally better JavaScript performance in the Admin Panels.

= 1.2.6 =
* Fix: All plugin URLs will now be secure connections if the main page is securely loaded.
* Fix: Removed die() function from plugin completely, so changing your server configurations won't break your entire site in some fringe cases.
* Fix: Refactored the plugin activation function so it is more reliable.
* New: Plugin version number is now saved in your database to help the plugin update itself.

= 1.2.5.3 =
* Fix: Moved the PHP 5 requirement check out of the CataBlog class, allowing proper checking.
* Fix: Removed a deprecated token from the built in gallery template.
* Fix: Removed the catablog.pot file and instead will include a catablog.po file to be duplicated and translated.
* New: The entire CataBlog collection is now labeled 'Library' in the admin menu.
* New: Modified the installation instructions to include the server requirements.

= 1.2.5.2 =
* Fix: Fixed display of gallery template, especially in themes that have a #content element.
* Fix: CSS tweaks, still trying to make this as compatible as possible.

= 1.2.5.1 =
* Fix: Thumbnail in the edit catalog item form is now resized properly.
* Fix: Added a missing CSS class for img.catablog-image
* Fix: Fixed the CSS classes for sub images in the default template.
* Fix: The inline Stylesheet classes now have #content to help CSS overrides in certain themes.
* Fix: The LightBox check if the file extension is an image is no longer case sensitive.
* Fix: Secondary images now link to the proper full size images.
* Fix: The function for theme developers now has the new shortcode parameters.

= 1.2.5 =
* Important: Requires WordPress 3.1 or better.
* Important: Removed drag and drop reordering and exposed the order value for each catalog item.
* New: CataBlog ShortCode now supports multiple categories separated by commas.
* New: CataBlog ShortCode has a new operator parameter for querying categories.
* New: Sort your catalog by order, date, title or randomly.
* New: CataBlog ShortCode has sorting parameters.
* New: CataBlog ShortCode has a template parameter for overriding the system template.
* New: All messages and language may now be localized with included POT file.
* New: Preliminary Spanish localization included.
* New: Added Date field to the edit catalog item form.
* Fix: Removed restrictions on foreign characters for the category name.
* Fix: If image_rotate() is not an available function then CataBlog will not use it.
* Fix: Thumbnail backgrounds are now filled with a rectangle for better system compatibility.
* Fix: The Admin menu position of CataBlog no longer will conflict with certain setups.
* Fix: Removed all !important declarations from the catablog.css file.
* Fix: Optimized the templates for better theme compatibility.
* Fix: Optimized the edit catalog item form for multiple languages.
* Fix: When the link field is empty the %LINK% token will now return the full size image instead of #empty-link.
* Fix: LightBox is now designed to work best with anchor tags instead of image tags.
* Fix: You may now enable the LightBox library and the full size image rendering separately.
* Fix: You may change the jQuery selector used to find the LightBox images.
* Fix: Optimized front end CSS, instead of inline styles a stylesheet is generated in your pages head tag.

= 1.2 =
* New: Upload multiple images per catalog item now.
* New: Template HTML code uses a new catablog-images-column class to group images.
* New: Next and previous catalog item links in the edit panel.
* Fix: Import and export work better and give more feedback.
* Fix: When deleting items your catalog order is now re-indexed properly.
* Fix: CSS classes for front end are more verbose and flexible.
* Fix: ShortCode Category attribute is not case sensitive.
* Fix: Change order button is disabled when viewing a specific category because it didn't work.
* Fix: New 'Uncategorized' default category for all new items.
 
= 1.1.9 =
* Fix: Import no longer makes a sub image when the subimages field is empty.
* Fix: Category filter now finds the category slug for the passed in category name.
* Fix: New categories made by import or manually will all have a common prefix in their slug.
* Fix: Clear database when importing should always delete all catalog items now.

= 1.1.8 =
* Fix: Lots and lots of testing, hopefully everything is a little more stable.
* Fix: Database query reduction and optimization across application.
* Fix: Categories are now consistently set throughout entire plugin.
* Fix: Category slugs should now be completely unique, preventing taxonomy conflicts.
* Fix: Code cleaned up and removed old database upgrade methods.
* Fix: Various user interface enhancements and bug fixes.

= 1.1.7 =
* Fix: Single site versions of WordPress may now upload sub images.
* Fix: Reduced database load on frontend rendering.

= 1.1.6 =
* Fix: Better multiple image per catalog item support.
* Fix: catablog-image CSS class reverted to stop upgrade bugs.
* Fix: Default template reverted to stop upgrade bugs and new subimages template added.
* Fix: Optimized next and previous item links in edit panel.
* Fix: Long item descriptions are truncated in the admin list view.
* Fix: Successful form submissions now forwards you to the appropriate url.
* Fix: Lazy loaded thumbnail images in admin list view refined.
* Fix: Better file upload validation and error messages.
* New: List view now renders the description in html
* New: Template drop down menus now lists all files in the directory.

= 1.1.5 =
* New: Added multiple image per catalog item support.
* New: Thumbnail images in the admin list view are now lazy loaded.
* New: Navigation controls in the admin edit panel.
* Fix: Rendering thumbnail and fullsize images are now separate threads.

= 1.1 =
* New: Added CSV (comma separated values) support for importing and exporting catalog data.

= 1.0.2 =
* New: New grid template default for showing product grids that use the item's link.
* New: Title link relationship field now in title options.
* New: Support for inserting tab characters into the template code.
* New: Only load CataBlog frontend JavaScript and CSS on pages with the shortcode.
* New: Thumbnail preview now has an image in options panel.
* Fix: Updated gallery template default to link to the full size image.
* Fix: Title link target attribute is now free form text in title options.

= 1.0.1 =
* New: Added %IMAGE-FULLSIZE% token to the HTML template feature.

= 1.0 =
* New: Rescan the originals folder for new images to add to the database.
* New: Category filter in the CataBlog Admin Panel for quick filter previews.
* New: Grid mode for easier resorting and photo gallery usages.
* New: Bulk selection of items for the delete function.
* Fix: Small interface bugs in LightBox when using an old browser.

== Upgrade Notice ==

= 1.6.3 =

This version of CataBlog has patched some vital security holes, we recommend you upgrade to this version or better as soon as possible.

= 1.2.5 =

LightBox JavaScript Library was upgraded, please upgrade your template code accordingly.
http://catablog.illproductions.com for more information and specifics.

= 1.2 =

Category name and slug bug was fixed in this version, you may want to export and reimport after upgrading.
http://catablog.illproductions.com for more information and specifics.

= 0.9.5 =

WARNING: Complete removal of all database manipulation code from CataBlog. This is very good, as all data storage and retrieval will be delegated by built in WordPress functions. To upgrade you will be required to Export and then Import your catalog data. After a successful Import, you may remove the old data from your database in the CataBlog > Options > Systems section . You should leave the images folder alone, as nothing much has changed when it comes to uploads and storage.
