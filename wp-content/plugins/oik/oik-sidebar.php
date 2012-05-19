<?php

/*
Plugin Name: oik sidebar
Plugin URI: http://www.oik-plugins.com/oik
Description: Applies widget wrangler sidebar functionality to Artisteer themes
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
global $oik_options;
require_once( 'bobbfunc.inc' );

function oik_sidebar_api_version() {
  return bw_oik_version();
}

/* Note: for this plugin to do anything you need to do the following: 
   o change your Artisteer theme to call bw_dynamic_sidebar instead of art_dynamic_sidebar
   o install the Widget Wrangler plugin  
   o define Widget Wrangler sidebars for each dynamic sidebar you want to control
   o The sidebar names you may use are:
     Artisteer 2.6
       default_before
       default_after
       secondary_before
       secondary_after
     Artisteer 3
       as for Artisteer 2.6 plus you can have _before and/or _after versions for the other sidebars:
   o For more information see  http://www.bobbingwidewebdevelopment.com/content/how-control-which-widget-appears-particular-page 
*/   

function bw_dynamic_sidebar( $name ) {
  
  global $art_widget_args, $art_sidebars, $theme_sidebars;
  // Add Widget Wrangler sidebars: _before and _after
  // Note: We believe that dynamic_sidebar exists as this was pre-requisite to calling this routine
  // but we can't be sure that widget wrangler is activated so don't call it if it's not.
   
  $wwsb = function_exists( 'ww_dynamic_sidebar' );
  
  
  bw_trace( $wwsb, __FUNCTION__, __LINE__, __FILE__, 'wwsb' );
  bw_trace( $name, __FUNCTION__, __LINE__, __FILE__, 'name' );

  if ( $wwsb ) {
    $success_b = ww_dynamic_sidebar($name.'_before');
    
    bw_trace( $success_b, __FUNCTION__, __LINE__, __FILE__ );
    
  }
  
  // Note: As of Artisteer version v3.0.0.39952 the global variable is called $theme_sidebars not $art_sidebars
  // use $theme_sidebars if theme_include_lib is defined
  // in previous versions the function was art_include_lib
  
  if ( function_exists( 'theme_include_lib' ))
    $success = dynamic_sidebar($theme_sidebars[$name]['id']);
  else
    $success = dynamic_sidebar($art_sidebars[$name]['id']);
    
  
  if ( $wwsb ) {
    $success_a = ww_dynamic_sidebar($name.'_after');
    bw_trace( $success_a, __FUNCTION__, __LINE__, __FILE__ );
  }
  return( $success );
}

