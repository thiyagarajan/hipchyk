<?php
/*
Plugin Name: CataBlog
Plugin URI: http://catablog.illproductions.com/
Description: CataBlog is a comprehensive and effortless tool that helps you create, organize and share catalogs, stores, galleries and portfolios on your blog.
Version: 1.6.3
Author: Zachary Segal
Author URI: http://catablog.illproductions.com/about/

Copyright 2011  Zachary Segal  (email : zac@illproductions.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/



// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}



// Global CataBlog Singleton
// Always reference this variable instead of instantiating a new CataBlog class.
global $wp_plugin_catablog_class;



// Load the CataBlog WordPress plugin if PHP is version 5.0 or above
function catablog_load_plugin() {
	
	// load necessary libraries
	require('lib/CataBlog.class.php');
	require('lib/CataBlogItem.class.php');
	require('lib/CataBlogGallery.class.php');
	require('lib/CataBlogDirectory.class.php');
	require('lib/CataBlogWidget.class.php');

	// create CataBlog class and hook into WordPress
	global $wp_plugin_catablog_class;
	$wp_plugin_catablog_class = new CataBlog();
	$wp_plugin_catablog_class->registerWordPressHooks();

	/**
	 * Template function to emulate the CataBlog Shortcode behavior.
	 * This function will echo out your catalog.
	 *
	 * @param string $category A list of CataBlog category names separated by commas
	 * @param string $template A CataBlog template file name
	 * @param string $sort The column to sort your catalog by, defaults to menu_order
	 * @param string $order The order to sort your catalog by, default to ascending
	 * @param string $operator The operator to apply to the categories passed in, defaults to IN
	 * @param integer $limit The number of catalog items shown per page
	 * @param boolean $navigation Wether to render the navigation controls for this catalog.
	 * @return void
	 */
	function catablog_show_items($category=null, $template=null, $sort='menu_order', $order='asc', $operator='IN', $limit=null, $navigation=true) {
		global $wp_plugin_catablog_class;
		$wp_plugin_catablog_class->frontend_init(true);
		
		if ($template === null) {
			$template = 'default';
		}
		
		if ($limit === null) {
			$limit = -1;
		}
		
		echo $wp_plugin_catablog_class->frontend_shortcode_catablog(array('category'=>$category, 'template'=>$template, 'sort'=>$sort, 'order'=>$order, 'operator'=>$operator, 'limit'=>$limit, 'navigation'=>$navigation));
	}
	
	/**
	 * Template function for fetching a single catalog item by id
	 *
	 * @param integer $id The id of a catalog item to fetch
	 * @return CataBlogItem|NULL Returns a CataBlogItem object if a catalog item was found, otherwise NULL
	 */
	function catablog_get_item($id=false) {
		if (is_numeric($id) && $id > 0) {
			return CataBlogItem::getItem($id);
		}
		return null;
	}
	
	/**
	 * Template function for rendering a list or dropdown
	 * menu of all CataBlog categories.
	 *
	 * @param boolean $is_dropdown Render the list as a dropdown menu
	 * @param boolean $show_count Render the number of items in each category
	 * @return void
	 */
	function catablog_show_categories($is_dropdown=false, $show_count=false) {
		global $wp_plugin_catablog_class;
		
		echo $wp_plugin_catablog_class->frontend_render_categories($is_dropdown, $show_count);
	}

}
if (version_compare(phpversion(), '5.0.0', '>=')) {
	catablog_load_plugin();
}



// Test that the system meets all requirements on activation
function catablog_activate() {
	
	// check if PHP is version 5
	if (version_compare(phpversion(), '5.0.0', '<')) {
		wp_die(__("<strong>CataBlog</strong> requires <strong>PHP 5</strong> or better running on your web server. You're version of PHP is to old, please contact your hosting company or IT department for an upgrade. Thanks.", 'catablog'));
	}
	
	// check if GD Library is loaded in PHP
	if (!extension_loaded('gd') || !function_exists('gd_info')) {
	    wp_die(__("<strong>CataBlog</strong> requires that the <strong>GD Library</strong> be installed on your web server's version of PHP. Please contact your hosting company or IT department for more information. Thanks.", 'catablog'));
	}
	
	// check WordPress version
	if (version_compare(get_bloginfo('version'), '3.1', '<')) {
		wp_die(__("<strong>CataBlog</strong> requires <strong>WordPress 3.1</strong> or above. Please upgrade WordPress or contact your system administrator about upgrading.", 'catablog'));
	}
	
	// check if the wp uploads folder is writable
	$uploads_dir = wp_upload_dir();
	if ($uploads_dir['error'] !== false) {
		wp_die(__("<strong>CataBlog</strong> could not detect your upload directory or it is not writable by PHP. Please make sure Apache and PHP have write permission for the configured uploads folder. Contact your hosting company or IT department for more information. Thanks.", 'catablog'));
	}
}
register_activation_hook( __FILE__, 'catablog_activate' );



// Do nothing on deactivation
function catablog_deactivate() {
	// do nothing
}
register_deactivation_hook( __FILE__, 'catablog_deactivate' );

