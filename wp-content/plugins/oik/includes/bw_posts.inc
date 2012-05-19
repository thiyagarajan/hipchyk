<?php

/*
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


/** 
 *
 */
function bw_global_post_id() {
  if ( isset( $GLOBALS['post'] )) {
    $post_id = $GLOBALS['post']->ID;
  } else {
    $post_id = 0;
  }  
  return( $post_id ) ;
}  

/** 
 *
 */
function bw_global_post_type() {
  if ( isset( $GLOBALS['post'] )) {
    $post_type = $GLOBALS['post']->post_type;
  } else {  
    $post_type = NULL;
  }  
  return( $post_type ) ;
}  

function bw_process_this_post( $id ) {
  global $processed_posts;
  $processed = bw_array_get( $processed_posts, $id, false );
  if ( !$processed ) {
    $processed_posts[$id] = $id ;
  }
  bw_trace2( $processed_posts );
  return( !$processed );
}

function bw_clear_processed_posts() {
  global $processed_posts;
  $processed_posts = array();
}  



/**
 * Return the excerpt from the $post 
 * Note: The function _theme_get_excerpt is not expected to exist. 
 * Artisteer doesn't "properly" extract the excerpt, so the '_' prefix disables the call to the Artisteer function.
 * 
 * @param  post $post- post from which to extract the excerpt
 * @return string $excerpt the excerpt from the post
 * 
 */
function bw_excerpt( $post ) {
  /*
  if ( function_exists( '_theme_get_excerpt' ) ) {
  
    global $post;
    $save_post = $post;
    $post = $post_id;
    $excerpt = theme_get_excerpt();
    $post = $save_post; 
    
  } else
  */    
  if ( bw_process_this_post( $post->ID ) ) {

    if ( empty( $post->excerpt ) ) {
      $content = explode( '<!--more-->', $post->post_content); 
      $excerpt = $content[0];
    } else {
      $excerpt = $post->excerpt;
    }
    $excerpt = bw_do_shortcode( $excerpt );
  } else {
    $excerpt = "post already processed: " . $post->ID;
  }  
  return( $excerpt );
}


/**
 * Format the "post" - basic first version
 *
 * Format the 'post' in a block or div with title, image with link, excerpt and read more button
 *
 * @param object $post - A post object
 * @param array $atts - Attributes array - passed from the shortcode
 * 
 *
 */
function bw_format_post( $post, $atts ) {
  setup_postdata( $post );
  
  bw_trace( $post, __FUNCTION__, __LINE__, __FILE__, "post" );
  
  $atts['title'] = get_the_title( $post->ID );
  $read_more = bw_array_get( $atts, "read_more", "read more" );
  $thumbnail = bw_thumbnail( $post->ID, $atts );
  
  $in_block = bw_validate_torf( bw_array_get( $atts, "block", TRUE ));
  if ( $in_block ) {
    oik_require( "shortcodes/oik-blocks.php" );
    e( bw_block( $atts ));
    if ( $thumbnail ) {
      sdiv( "avatar alignleft" );
      bw_link_thumbnail( $thumbnail, $post->ID, $atts );
      ediv();
    }  
  } else {
    $class = bw_array_get( $atts, "class", "" );
    sdiv( $class );
    if ( $thumbnail ) {
      sdiv( "avatar alignleft" );
      bw_link_thumbnail( $thumbnail, $post->ID, $atts );
      ediv();
    }
    span( "title" );
    strong( $atts['title'] );
    epan();
    br();
  }  
  e( bw_excerpt( $post ) );
  sp();
  art_button( get_permalink( $post->ID ), $read_more, $read_more ); 
  ep(); 
  if ( $in_block )
    e( bw_eblock() ); 
  else {  
    sediv( "cleared" );
    ediv();  
  }    
}


/**
 * Format the "post" - in a simple list item list
 */
function bw_format_list( $post, $atts ) {
  setup_postdata( $post );
  
  // bw_trace( $post, __FUNCTION__, __LINE__, __FILE__, "post" );
  $title = get_the_title( $post->ID );
  stag( 'li' );
  alink( NULL, get_permalink( $post->ID ), $title );
  etag( 'li' );
  
}


/**
 * Get the list of categories for this "post" as a string of slugs separated by commas 
 */
function bw_get_categories() {
  global $post;
  $categories = get_the_category( $post->ID );
  $cats = '';
  foreach ( $categories as $category ) {
    $cats .= $category->slug;
    $cats .= ' ';
  }  
  $cats = trim( $cats );
  $cats = str_replace( ' ',',', $cats );
  return bw_trace2( $cats );
}




/**
 * Wrapper to get_posts() 
 * 
 * When no parameters are passed processing should depend upon the context
 * e.g for a 'page' it should list the child pages
 *     for a 'post' it should show related posts in the same category as the current post
 * 
 * # $atts       $post->     Default
 *   post_type   post_type   processing
 * - ---------   ---------   -----------------------------------
 * 1 -           page        list child pages - first level only
 * 2 -           post        list related posts - same categories
 * 3 -           custom      none
 * 4 page        page        as 1.
 * 5 page        post        ?
 * 6 page        custom      ?
 * 7 post        page        ?
 * 8 post        post        as 2.
 * 9 post        custom      ?
 * 10-12 custom  any         ?
 *
 * As you can see from the table above the default behaviour for listing posts on pages and vice-versa is not (yet) defined
 */
function bw_get_posts( $atts = NULL ) {
  
  // Copy the atts from the shortcode to create the array for the query
  // removing the class and title parameter that gets passed to bw_block()
 
  $attr = $atts;
  bw_trace( $atts, __FUNCTION__, __LINE__, __FILE__, "atts" );
  //bw_trace( $attr, __FUNCTION__, __LINE__, __FILE__, "attr" );    
  
  /* Set default values if not already set */
  $attr['post_type'] = bw_array_get_dcb( $attr, 'post_type', NULL, "bw_global_post_type"  );
  
  // Only default post_parent for post_type of 'page' 
  // This allows [bw_pages] to be used without parameters on a page
  // and to be used to list 'page's from other post types.
  // 
  if ( $attr['post_type'] == 'page' || $attr['post_type'] == 'attachment' ) {
    $attr['post_parent'] = bw_array_get_dcb( $attr, "post_parent", NULL, "bw_global_post_id" );
  }
  
  if ( $attr['post_type'] == 'post' ) {
    $attr['category_name'] = bw_array_get( $attr, "category_name", NULL );
    if ( NULL == $attr['category_name'] ) {
      $categories = bw_get_categories();
      $attr['category_name'] = $categories;
    }  
  }
        
  $attr['numberposts'] = bw_array_get( $attr, "numberposts", -1 );
  $attr['orderby'] = bw_array_get( $attr, "orderby", "title" );
  $attr['order'] = bw_array_get( $attr, "order", "ASC" );
  
  // Regardless of the post type, exclude the current post, 
  // Note: This could also be improved **?**
  $attr['exclude'] = bw_array_get_dcb( $attr, "exclude", NULL, "bw_global_post_id" );
  
  bw_trace( $attr, __FUNCTION__, __LINE__, __FILE__, "attr" );
  
  // if ( $attr['post_type'] == 'post' ) {
    $posts = get_posts( $attr );
  // } else {
  //  $posts = get_pages( $attr );
  // }  
  bw_trace( $posts, __FUNCTION__, __LINE__, __FILE__, "posts" );
  return( $posts );
  
}



