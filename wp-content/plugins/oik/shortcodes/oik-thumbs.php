<?php 
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
oik_require( "shortcodes/oik-attachments.php" );

/**
 * Format the "thumb" 
 *
 * Format the 'post' in a block or div with image with link only 
 *
 * @param object $post - A post object
 * @param array $atts - Attributes array - passed from the shortcode
 * 
 *
 */
function bw_format_thumb( $post, $atts ) {
  setup_postdata( $post );
  
  bw_trace( $post, __FUNCTION__, __LINE__, __FILE__, "post" );
  
  $atts['title'] = get_the_title( $post->ID );
  //$read_more = bw_array_get( $atts, "read_more", "read more" );
  $thumbnail = bw_thumbnail( $post->ID, $atts );
  
  $in_block = bw_validate_torf( bw_array_get( $atts, "block", false ));
  if ( $in_block ) {
    oik_require( "shortcodes/oik-blocks.php" );
    e( bw_block( $atts ));
    bw_link_thumbnail( $thumbnail, $post->ID, $atts );
    e( bw_eblock() ); 
  } else {
    $class = bw_array_get( $atts, "class", "" );
    sdiv( $class );
    bw_link_thumbnail( $thumbnail, $post->ID, $atts );
    //sediv( "cleared" );
    ediv();  
  }  
}

/**
 * Display thumbnail links to pages
 * 
 * For each "page" display links using thumbnails only; no excerpt nor read more links
 */
function bw_thumbs( $atts = NULL ) {
  $atts['orderby'] = bw_array_get( $atts, "orderby", "title" );
  $atts['order'] = bw_array_get( $atts, "order", "ASC" );
  $posts = bw_get_posts( $atts );
  if ( count( $posts ) ) {
    foreach ( $posts as $post ) {
      bw_format_thumb( $post, $atts );
    }
  }  
  return( bw_ret() );
} 




