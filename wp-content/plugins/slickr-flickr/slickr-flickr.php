<?php
/*
Plugin Name: Slickr Flickr
Plugin URI: http://www.slickrflickr.com
Description: Displays photos from Flickr in slideshows and galleries
Version: 1.40
Author: Russell Jamieson
Author URI: http://www.russelljamieson.com

Copyright 2011 Russell Jamieson (russell.jamieson@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
define('SLICKR_FLICKR_VERSION','1.40');
define('SLICKR_FLICKR', 'slickr-flickr');
define('SLICKR_FLICKR_FOLDER', SLICKR_FLICKR);
define('SLICKR_FLICKR_PATH', SLICKR_FLICKR_FOLDER.'/slickr-flickr.php');
define('SLICKR_FLICKR_PLUGIN_URL', plugins_url(SLICKR_FLICKR_FOLDER));
define('SLICKR_FLICKR_HOME', 'http://www.slickrflickr.com');
define('SLICKR_FLICKR_PRO', 'http://www.diywebmastery.com/slickrflickrpro');
define('SLICKR_FLICKR_LICENCE', 'slickr_flickr_licence'); 

$slickr_flickr_options = array();
$slickr_flickr_pro_options = array();
$slickr_flickr_pro_defaults = array('consumer_secret' =>'', 'token' => '', 'token_secret' => '');
$slickr_flickr_defaults = array(
    'id' => '',
    'group' => 'n',
    'use_key' => '',
    'api_key' => '',
    'search' => 'photos',
    'tag' => '',
    'tagmode' => '',
    'set' => '',
    'gallery' => '',
    'license' => '',
    'date_type' => '',
    'date' => '',
    'before' => '',
    'after' => '',
    'cache' => 'on',
    'items' => '20',
    'type' => 'gallery',
    'captions' => 'on',
    'lightbox' => 'sf-lbox-manual',
    'galleria'=> 'galleria-1.0',
    'galleria_theme'=> 'classic',
    'galleria_options' => '',
    'options' => '',
    'delay' => '5',
    'transition' => '0.5',
    'start' => '1',
    'autoplay' => 'on',
    'pause' => '',
    'orientation' => 'landscape',
    'size' => 'medium',
    'width' => '',
    'height' => '',
    'bottom' => '',
    'thumbnail_size' => '',
    'thumbnail_scale' => '',
    'thumbnail_captions' => '',
    'thumbnail_border' => '',
    'photos_per_row' => '',
    'align' => '',
    'border' => '',
    'descriptions' => '',
    'flickr_link' => '',
    'link' => '',
    'target' => '_self',
    'attribution' => '',
    'nav' => '',
    'sort' => '',
    'direction' => '',
    'per_page' => 50,
    'page' => 1,
    'restrict' => '',
    'random' => '',
    'cache_expiry' => 43200,
    'private' => '',
    'scripts_in_footer' => false
    );

function slickr_flickr_get_options ($cache = true) {
   global $slickr_flickr_options;
   global $slickr_flickr_defaults;
   if ($cache && (count($slickr_flickr_options) > 0)) return $slickr_flickr_options;

   $flickr_options = array();
   $options = get_option("slickr_flickr_options");
   if (empty($options)) {
      $slickr_flickr_options = $slickr_flickr_defaults;
   } else {
     foreach ($options as $key => $option) {
       if (isset($options[$key]) && strpos($key,"flickr_")==0)  $flickr_options[substr($key,7)] = $option;
     }
     if (array_key_exists('lightbox',$flickr_options))
     	switch ($flickr_options['lightbox']) {
          	case "lightbox-slideshow": $flickr_options['lightbox'] = 'sf-lbox-auto'; break;
          	case "lightbox": $flickr_options['lightbox'] = 'sf-lbox-manual'; break;
     	}
     if (array_key_exists('galleria',$flickr_options))
     	switch ($flickr_options['galleria']) {
     		case "":
          	case "galleria_10": $flickr_options['galleria'] = 'galleria-1.0'; break;
          	case "galleria_12": $flickr_options['galleria'] = 'galleria-1.2'; break;
     	}
     $slickr_flickr_options = shortcode_atts( $slickr_flickr_defaults, $flickr_options);
   }
   return $slickr_flickr_options;
}

function slickr_flickr_get_option($option_name) {
    $options = slickr_flickr_get_options();
    if ($option_name && $options && array_key_exists($option_name,$options))
        return $options[$option_name];
    else
        return false;
}

function slickr_flickr_pro_get_options ($cache = true) {
   global $slickr_flickr_pro_options;
   global $slickr_flickr_pro_defaults;
   if ($cache && (count($slickr_flickr_pro_options) > 0)) return $slickr_flickr_pro_options;

   $options = get_option("slickr_flickr_pro_options");
   if (empty($options)) {
      $slickr_flickr_pro_options = $slickr_flickr_pro_defaults;
   } else  {
     $slickr_options = array();
     foreach ($options as $key => $option) {
       if (isset($options[$key]) && strpos($key,"slickr_")==0)  $slickr_options[substr($key,7)] = $option;
     }
     if (array_key_exists('licence',$slickr_options)) SlickrFlickrUpdater::save_licence($slickr_options['licence'], false);

     $slickr_flickr_pro_options = shortcode_atts( $slickr_flickr_pro_defaults, $slickr_options);
   }
   return $slickr_flickr_pro_options;
}

function slickr_flickr_scripts_in_footer() {
    $options = slickr_flickr_get_options();
    return $options['scripts_in_footer'];
}

function slickr_flickr_append_secrets(&$params, $keys = array('consumer_secret','token','token_secret')) {
    $pro_options = slickr_flickr_pro_get_options();
    foreach ($keys as $key) 
    	if (array_key_exists($key,$pro_options)) 
    		$params[$key] = $pro_options[$key];
}

function slickr_flickr_clear_rss_cache() {
    global $wpdb, $table_prefix;
    $prefix = $table_prefix ? $table_prefix : "wp_";
    $sql = "DELETE FROM ".$prefix."options WHERE option_name LIKE 'rss_%' and LENGTH(option_name) IN (36, 39)";
    $wpdb->query($sql);
}

function slickr_flickr_clear_rss_cache_transient() {
    global $wpdb, $table_prefix;
    $prefix = $table_prefix ? $table_prefix : "wp_";
    $sql = "DELETE FROM ".$prefix."options WHERE option_name LIKE '_transient_feed_%' or option_name LIKE '_transient_rss_%' or option_name LIKE '_transient_timeout_%'";
    $wpdb->query($sql);
}

function slickr_flickr_clear_cache() {
    slickr_flickr_clear_rss_cache();
    slickr_flickr_clear_rss_cache_transient();
}

function slickr_flickr_check_validity(){
    return SlickrFlickrUpdater::check_validity();
}
register_activation_hook(SLICKR_FLICKR_PATH,'slickr_flickr_pro_get_options');

require_once(dirname(__FILE__).'/slickr-flickr-updater.php');
require_once(dirname(__FILE__).'/slickr-flickr-'.(is_admin()?'admin':'public').'.php');
?>