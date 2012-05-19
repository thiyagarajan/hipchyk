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
oik_require( "includes/bw_images.inc" );


/**
 * List sub-pages of the current or selected page 
 *
 * This function is designed to replace the functionality of the [bw_plug name='extended-page-lists'] plugin and other plugins that list pages.
 * It works in conjunction with Artisteer blocks - to enable the page list to be styled as a series of blocks
 * Well, that's the plan
 *
 * [bw_pages class="classes for bw_block" 
 *   post_type='page'
 *   post_parent 
 *   orderby='title'
 *   order='ASC'
 *   posts_per_page=-1
 *   block=true or false
 *   thumbnail=specification - see bw_thumbnail
 *   customcategoryname=custom category value  
 */
function bw_pages( $atts = NULL ) {
  $posts = bw_get_posts( $atts );
  bw_trace( $posts, __FUNCTION__, __LINE__, __FILE__, "posts" );
  
  foreach ( $posts as $post ) {
    bw_format_post( $post, $atts );
  }
  
  bw_clear_processed_posts();
  
  return( bw_ret() );
}


/** 
 * @see http://codex.wordpress.org/Template_Tags/get_posts
 * Default usage copied on 2012/02/27
*/    
    

function bw_pages__syntax( $shortcode="bw_pages" ) {
  $syntax = _sc_posts(); 
  $syntax = array_merge( $syntax, _sc_classes() );
  return( $syntax );   
}

function bw_pages__help( $shortcode="bw_pages" ) {
  return( "Display page thumbnails and excerpts as links" );
  
}

function bw_pages__example( $shortcode="bw_pages" ) {

 e( "Display sub-pages of the current or selected item" );
 e( "The item may be a page, post or custom post type" );
 e( "The default display is formatted with a featured image, excerpt and a read more link." );
 e( "For examples visit ");  
 $link = "http://www.oik-plugins.com/oik-shortcodes/$shortcode";
 alink( NULL, $link, "$shortcode help" );   

} 

