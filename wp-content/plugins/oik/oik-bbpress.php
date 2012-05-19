<?php

/*
Plugin Name: oik bbpress
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-bbpress-plugin/
Description: strip tags from bbPress forum title tooltips  
Version: 1.13
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

    Copyright 2011-2012 Bobbing Wide (email : herb@bobbingwide.com )

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


/** 
 * bw_strip_tags() is equivalent to esc_attr( strip_tags() )
 * but it also gets passed the current_filter - future use
*/
if ( !function_exists( "bw_strip_tags" ) ) {
  function bw_strip_tags( $string, $current_filter=NULL ) {
    $rstring = $string;
    $rstring = strip_tags( $rstring );
    $rstring = esc_attr( $rstring );
    return $rstring;
  }
}

/** 
 * bbPress does not cater for shortcode expansion in titles so we need to apply a filter when 
 * the title is being used in a text= attribute
 */
add_filter( 'bbp_get_forum_title', 'bw_strip_tags' );






