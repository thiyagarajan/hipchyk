<?php

/*
-Plugin Name: oik pages
-Plugin URI: http://www.oik-plugins.com/oik
-Description: [bw_pages], [bw_list] and [bw_bookmarks] shortcodes to summarize child pages or custom post types
-Version: 1.11
-Author: bobbingwide
-Author URI: http://www.bobbingwide.com
-License: GPL2

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

require_once( 'bobbfunc.inc' );
//require_once( 'bobbingwide.inc' );
/* This include will enable oik shortcodes even if the oik base is not enabled. Is this is good idea? */
require_once( 'oik-add-shortcodes.php' );
/* Include functions to determine level of Artisteer theme whilst Artisteer doesn't provide it */
require_once( 'oik-artisteer.php' );


/* We shouldn't let any of these expand in titles */

//bw_add_shortcode( 'bw_pages', 'bw_pages', NULL, false );
//bw_add_shortcode( 'bw_list', 'bw_list', NULL, false );
//bw_add_shortcode( 'bw_bookmarks', 'bw_bookmarks', NULL, false );
//bw_add_shortcode( 'bw_attachments', 'bw_attachments', NULL, false );
//bw_add_shortcode( 'bw_pdf', 'bw_pdf', NULL, false );
//bw_add_shortcode( 'bw_images', 'bw_images', NULL, false );




function bw_get_fullimage( $post_id, $size='full', $atts=NULL ) {
  $return_value = "silly"; 

}






