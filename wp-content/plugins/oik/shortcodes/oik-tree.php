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
 * format the tree - as a nested list
 * these functions are called recursively
 * to create trees of the children for each page
 */
function bw_format_tree( $post, $atts ) {
  stag( "li" );
  $url= get_permalink($post->ID);
  $title = $post->post_title; 
  alink( "bw_tree", $url, $title ); 
  $atts['post_parent'] = $post->ID;
  bw_tree_func( $atts );
  etag( "li" );
} 

/** 
 * build and format a tree
 */
function bw_tree_func( $atts ) {  
  $posts = bw_get_posts( $atts );
  if ( count( $posts ) ) {
    stag( "ul" );
    foreach ( $posts as $post ) {
      bw_format_tree( $post, $atts );
    }
  etag( "ul" );
  }
}

/**
 * Create a simple tree of the 'pages' under the selected id
 * We default the ordering to match the menu order of the pages
 * The default tree starts from the current 'post'
 */
function bw_tree( $atts = NULL ) {
  $atts['orderby'] = bw_array_get($atts, "orderby", "menu_order" );
  $atts['order'] = bw_array_get( $atts, "order", "ASC" );
  $atts['post_parent'] = bw_array_get_dcb( $atts, "post_parent", null, "bw_global_post_id" );
  bw_tree_func( $atts );
  return( bw_ret() );
}


/** 
 * bw_tree doesn't currently support classes
*/
function bw_tree__syntax( $shortcode="bw_tree" ) {
  $syntax = _sc_posts(); 
  // $syntax = array_merge( $syntax, _sc_classes() );
  return( $syntax );   
}
