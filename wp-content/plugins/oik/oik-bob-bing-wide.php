<?php

/*
Plugin Name: oik bob bing wide shortcodes
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-bob-bing-wide-plugin
Description: More lazy smart shortcodes: bw_plug, bw_page, bw_post, bob/fob bing/bong wide/hide & wow, oik and loik, wp, wpms, bp, artisteer, drupal
Version: 1.13
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

    Copyright 2010-2012 Bobbing Wide (email : herb@bobbingwide.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 2,
    as published by the Free Software Foundation.

    You may NOT assume that you can use any other version of the GPL.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    The license for this software can likely be found here:
    http://www.gnu.org/licenses/gpl-2.0.html

*/

function oik_bob_bing_wide_api_version() {
  return bw_oik_version();
}

add_action( "oik_loaded", "oik_bob_bing_wide_init" );

function oik_bob_bing_wide_init() {

  /* Shortcodes for each of the more useful bobbingwide babbles  */
  bw_add_shortcode( 'bob', 'bw_bob', oik_path("shortcodes/oik-bob-bing-wide.php"), true );
  bw_add_shortcode( 'fob', 'bw_fob', oik_path("shortcodes/oik-bob-bing-wide.php"), true );

  bw_add_shortcode( 'bing', 'bw_bing', oik_path("shortcodes/oik-bob-bing-wide.php"), true );
  bw_add_shortcode( 'bong', 'bw_bong', oik_path("shortcodes/oik-bob-bing-wide.php"), true );

  bw_add_shortcode( 'wide', 'bw_wide', oik_path("shortcodes/oik-bob-bing-wide.php"), true );
  bw_add_shortcode( 'hide', 'bw_hide', oik_path("shortcodes/oik-bob-bing-wide.php"), true );

  bw_add_shortcode( 'wow', 'bw_wow', oik_path("shortcodes/oik-bob-bing-wide.php"), true );
  bw_add_shortcode( 'WoW', 'bw_wow', oik_path("shortcodes/oik-bob-bing-wide.php"), true );
  bw_add_shortcode( 'WOW', 'bw_wow_long', oik_path("shortcodes/oik-bob-bing-wide.php"), true);

  bw_add_shortcode( 'oik', 'bw_oik', oik_path("shortcodes/oik-bob-bing-wide.php"), true );  
  bw_add_shortcode( 'loik', 'bw_loik', oik_path("shortcodes/oik-bob-bing-wide.php"), false ); // Link to the plugin  
  bw_add_shortcode( 'OIK', 'bw_oik_long', oik_path("shortcodes/oik-bob-bing-wide.php"), true); // Spells out often included key-information

  bw_add_shortcode( 'bw_page', 'bw_page', oik_path("shortcodes/oik-bob-bing-wide.php"), false );
  bw_add_shortcode( 'bw_post', 'bw_post', oik_path("shortcodes/oik-bob-bing-wide.php"), false );
  bw_add_shortcode( 'bw_plug', 'bw_plug', oik_path("shortcodes/oik-bob-bing-wide.php"), false );

  bw_add_shortcode( 'bw_module',  'bw_module', oik_path("shortcodes/oik-bob-bing-wide.php"), false );

  bw_add_shortcode( 'bp', 'bw_bp', oik_path("shortcodes/oik-bob-bing-wide.php"), true );   // BuddyPress
  bw_add_shortcode( 'lwp', 'bw_lwp', oik_path("shortcodes/oik-bob-bing-wide.php"), false ); // Link to WordPress.org 
  bw_add_shortcode( 'lbp', 'bw_lbp', oik_path("shortcodes/oik-bob-bing-wide.php"), false ); // Link to BuddyPress.org 
  bw_add_shortcode( 'wpms', 'bw_wpms', oik_path("shortcodes/oik-bob-bing-wide.php"), true );   // WordPress Multisite
  bw_add_shortcode( 'lwpms', 'bw_lwpms', oik_path("shortcodes/oik-bob-bing-wide.php"), false ); // Link to WordPress multisite - .org
  bw_add_shortcode( 'drupal', 'bw_drupal', oik_path("shortcodes/oik-bob-bing-wide.php"), true );   // Drupal
  bw_add_shortcode( 'ldrupal', 'bw_ldrupal', oik_path("shortcodes/oik-bob-bing-wide.php"), false ); // Link to Drupal.org
  bw_add_shortcode( 'artisteer', 'bw_art', oik_path("shortcodes/oik-bob-bing-wide.php"), true ); // Artisteer
  bw_add_shortcode( 'lartisteer', 'bw_lart', oik_path("shortcodes/oik-bob-bing-wide.php"), false ); // Link to artisteer.com 

  bw_add_shortcode( 'lbw', 'bw_lbw', oik_path("shortcodes/oik-bob-bing-wide.php"), false); // Link to Bobbing Wide or other websites


  // This is just a bit of code to help determine if a fix to shortcodes (ticket #17657) has been implemented or not
  // whether or not a shortcode containing hyphen(s) is handled depends on when it's registered.
  // if it's registered before the shortcode that is the same as the prefix before the '-' it's OK
  // it it's registed after, then the shorter shortcode will take precedence


  bw_add_shortcode( 'wp-1', 'wp1', oik_path("shortcodes/oik-bob-bing-wide.php"), true);
  bw_add_shortcode( 'wp-2', 'wp2', oik_path("shortcodes/oik-bob-bing-wide.php"), true);
  bw_add_shortcode( 'wp', 'bw_wp', oik_path("shortcodes/oik-bob-bing-wide.php"), true );   // WordPress
  bw_add_shortcode( 'wp-3', 'wp3', oik_path("shortcodes/oik-bob-bing-wide.php"), true);

}
