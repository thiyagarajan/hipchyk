<?php // (C) Copyright Bobbing Wide 2012

/*

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


oik_require( "includes/bw_posts.inc" );

/**
 * List sub-pages of the current or selected page - in a simple list 
 *
 * Same as bw_pages but producing a simple list of links to the content type
 *
 *
 * [bw_list class="classes for the list" 
 *   post_type='page'
 *   post_parent 
 *   orderby='title'
 *   order='ASC'
 *   posts_per_page=-1
 *   block=true or false
 *   thumbnail=specification - see bw_thumbnail
 *   customcategoryname=custom category value  
 */
function bw_list( $atts = NULL ) {
  
  $posts = bw_get_posts( $atts );
  
  sul( bw_array_get( $atts, 'class', 'bw_list' ));
  
  foreach ( $posts as $post ) {
    bw_format_list( $post, $atts );
  }
  eul();
  
  return( bw_ret() );
} 

