<?php

/*
Plugin Name: oik bwtrace 
Plugin URI: http://www.oik-plugins.com/oik
Description: Easy to use trace macros for oik plugins
Version: 1.13
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

    Copyright 2011,2012 Bobbing Wide (email : herb@bobbingwide.com )

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
global $bw_trace_options, $bw_trace_on, $bw_trace_level;

// Since this plugin is defined to load first... so that it can perform the trace reset
// then we need to load oik_boot ourselves. We need bw_array_get().
require_once( "oik_boot.inc" );
oik_init();
oik_require( "bwtrace_boot.inc" ); 
oik_require( "includes/bwtrace.inc" );

function oik_bwtrace_version() {
  return oik_version();
}

function bw_this_plugin_first() {
	// ensure path to this file is via main wp plugin path
	$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
	$this_plugin = plugin_basename(trim($wp_path_to_this_file));
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
	if ($this_plugin_key) { // if it's 0 it's the first plugin already, no need to continue
		array_splice($active_plugins, $this_plugin_key, 1);
		array_unshift($active_plugins, $this_plugin);
		update_option('active_plugins', $active_plugins);
	}
}

if (function_exists( "add_action" )) {
  bw_trace_plugin_startup();
}

/**
 * Return TRUE if option is '1', FALSE otherwise
 */
function bw_torf( $array, $option ) {
  $opt = bw_array_get( $array, $option );
  $ret = $opt > '0';
  return $ret;
}


function bw_trace_plugin_startup() {
  global $bw_trace_options;
  add_action("activated_plugin", "bw_this_plugin_first");

  // Moved the calls to bw_add_shortcode into oik-add-shortcodes
  // 

  //add_filter('widget_text', 'do_shortcode');
  //add_filter('the_title', 'do_shortcode' ); 
  //add_filter('wpbody-content', 'do_shortcode' );


  $bw_trace_options = get_option( 'bw_trace_options' );

  $bw_trace_level = bw_torf( $bw_trace_options, 'trace' ); 
  if ( $bw_trace_level ) {
    bw_trace_on();
    global $bw_include_trace_count, $bw_include_trace_date, $bw_trace_anonymous;
    $bw_include_trace_count = bw_torf( $bw_trace_options, 'count' );
    $bw_include_trace_date = bw_torf( $bw_trace_options, 'date' );
    $bw_trace_anonymous = !bw_torf( $bw_trace_options, 'qualified' );
    
    // We should only do this if we want to trace actions
    
    add_action( "init", "bw_trace_actions" );
  } else {
    bw_trace_off();  
  } 

  // We can reset the trace file regardless of the value of tracing
  $bw_trace_reset = bw_torf( $bw_trace_options, 'reset' );
  
  if ( !empty( $_REQUEST['_bw_trace_reset'] ) ) {
    $bw_trace_reset = TRUE;
  } 
  
 
  $bw_action_options = get_option( 'bw_action_options' );
  $bw_action_reset = bw_torf( $bw_action_options, 'reset' );
  if ( !empty( $_REQUEST['_bw_action_reset'] ) ) {
    $bw_action_reset = TRUE;
  } 
  
  if ( $bw_trace_reset ) {
    oik_require( "includes/bwtrace.inc" );
    bw_trace_reset();
    $bw_action_reset = true;
  } 
  
  if ( $bw_action_reset ) {
    oik_require( "includes/oik-bwtrace.inc" );
    bw_action_reset();
  }
  
  //$bw_trace_errors = $bw_trace_options[ 'errors']; 
  //bw_trace_errors( $bw_trace_errors );

  // bw_trace_log( "Trace log starting"  );

  if ( $bw_trace_level > '0' ) {
    bw_lazy_trace( ABSPATH . $bw_trace_options['file'], __FUNCTION__, __LINE__, __FILE__, 'tracelog' );
    bw_lazy_trace( $_SERVER, __FUNCTION__, __LINE__, __FILE__, "_SERVER" ); 
    bw_lazy_trace( bw_getlocale(), __FUNCTION__, __LINE__, __FILE__, "locale" );
    bw_lazy_trace( $_REQUEST, __FUNCTION__, __LINE__, __FILE__, "_REQUEST" );
    //bw_lazy_trace( $_POST, __FUNCTION__, __LINE__, __FILE__, "_POST" );
    //bw_lazy_trace( $_GET, __FUNCTION__, __LINE__, __FILE__, "_GET" );
  } 

  add_action('admin_init', 'bw_trace_options_init' );
  add_action('admin_init', 'bw_action_options_init' );
  add_action('admin_menu', 'bw_trace_options_add_page');
  add_action('admin_menu', 'bw_action_options_add_page');

}

/** 
 * Start the trace action logic if required 
*/ 
function bw_trace_actions() {
  $bw_action_options = get_option( 'bw_action_options' );
  $trace_actions = bw_array_get( $bw_action_options, "actions", "off" );
  bw_trace2( $bw_action_options, "bw_action_options" );
  if ( $trace_actions ) {
    oik_require( "includes/oik-bwtrace.inc" );
    bw_lazy_trace_actions();
  }  
}

if ( function_exists( "is_admin" ) ) {
if ( is_admin() ) {   

  require_once( 'admin/oik-bwtrace.inc' );
}
}



