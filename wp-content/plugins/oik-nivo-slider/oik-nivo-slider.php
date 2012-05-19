<?php
/**
Plugin Name: oik-nivo-slider
Depends: oik base plugin
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-nivo-slider/
Description: [nivo] shortcode for the Nivo slider using oik
Version: 1.3
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

    Copyright 2012 Bobbing Wide (email : herb@bobbingwide.com )

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
add_action( "oik_loaded", "bw_nivo_init" );

function bw_nivo_init() {
  bw_add_shortcode( "nivo", "bw_nivo_slider", oik_path( "nivo.inc", "oik-nivo-slider" ), false ); 
  add_action( "admin_menu", "oik_nivo_admin_menu" );
}


function oik_nivo_admin_menu() {
  require_once( "admin/oik-nivo-slider.php" );
  oik_nivo_lazy_admin_menu();
}


/* This code will produce a message when oik-nivo-slider is activated but oik isn't */
add_action( "after_plugin_row_" . plugin_basename(__FILE__), "oik_nivo_activation" );
add_action( "admin_notices", "oik_nivo_activation" );

/**
 * Note: oik version 1.12's dependency checking did not work for WordPress MultiSite
 * so oik-nivo-slider v1.2 is NOW dependent upon oik version 1.12.1 in MultSite
 * but it will work with oik version 1.12 in a single installation
*/ 
function oik_nivo_activation() {
  require_once( "admin/oik-nivo-slider.php" );
  if ( is_multisite() ) { 
    $depends = "oik:1.12.1"; 
  } else {
    $depends = "oik:1.12";
  }     
  oik_nivo_lazy_activation( __FILE__, $depends, "oik_nivo_inactive" );
}



