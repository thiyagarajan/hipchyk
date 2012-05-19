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
oik_require( "includes/bw_images.inc" );

/**
 * Format the "attachment" - basic first version
 *
 * Format the 'post' in a block or div with title and link to the attachment
 *
 * @param object $post - A post object
 * @param array $atts - Attributes array - passed from the shortcode
 * 
 * e.g. post_mime_type=image 
 *
 */
function bw_format_attachment( $post, $atts ) {
  setup_postdata( $post );
  bw_trace2();
  $atts['title'] = get_the_title( $post->ID );
  //$read_more = bw_array_get( $atts, "read_more", "read more" );
  //$thumbnail = bw_thumbnail( $post->ID, $atts );
  
  $in_block = bw_validate_torf( bw_array_get( $atts, "block", false));
  if ( $in_block ) { 
    e( bw_block( $atts ));
  } else {
    $class = bw_array_get( $atts, "class", "" );
    sdiv( $class );
  } 
  sp();
  // Display images as thumbnails and other attachments as text links
  // This call seems inefficient since we've already loaded the whole post
  // so wp_get_attachment_link is not doing much really! 
  $atts['thumbnail'] = bw_array_get( $atts, 'thumbnail', 'thumbnail' ); 
  if ( $atts['thumbnail'] == "full" ) { 
    $thumbnail = retimage( "full", $post->guid );
    bw_link_thumbnail( $thumbnail, $post->ID, $atts );
  } else {
    $thumbnail = bw_thumbnail( $post->ID, $atts, true );
    bw_link_thumbnail( $thumbnail, $post->ID, $atts );
  } 
  if ( bw_validate_torf( bw_array_get( $atts, 'titles', 'y' )) ) { 
    span( "title" );
    e( $post->post_title );   // Title
    epan();  
  }
  ep(); 
   
  if ( bw_validate_torf( bw_array_get( $atts, 'captions', 'n' )) ) { 
    p( $post->post_excerpt, "caption" ); // Caption
    p( $post->post_content, "description" ); // Description
  }
  
  if ( $in_block )
    e( bw_eblock() ); 
  else {  
    sediv( "cleared" );
    ediv();  
  }
}   

/**
 * List attachments
 *
 * This function is similar to bw_pages but formats attachments
 * It works in conjunction with Artisteer blocks - to enable the page list to be styled as a series of blocks
 * Well, that's the plan
 *
 * [bw_attachments class="classes for bw_block" 
 *   post_type='atachment'
 *   post_mime_type='
 *     application/pdf
 *      image/gif
 * 	image/jpeg
 * 	image/png
 * 	text/css
 *      video/mp4
 * 
 *   post_parent 
 *   orderby='title'
 *   order='ASC'
 *   posts_per_page=-1
 *   block=true or false
 *   thumbnail=specification - see bw_thumbnail()
 *   customcategoryname=custom category value  
 */
function bw_attachments( $atts = NULL ) {
  $atts[ 'post_type'] = bw_array_get( $atts, "post_type", "attachment" );
  $posts = bw_get_posts( $atts );
  bw_trace( $posts, __FUNCTION__, __LINE__, __FILE__, "posts" );
  
  foreach ( $posts as $post ) {
    bw_format_attachment( $post, $atts );
  }
  
  return( bw_ret() );
} 

function bw_pdf( $atts = NULL ) {
  $atts['post_mime_type'] = 'application/pdf';
  return( bw_attachments( $atts ));
}  

/**
 * Display the images attached to a post or page 
 */ 
function bw_images( $atts = NULL ) {
  $atts['post_mime_type'] = bw_array_get( $atts, 'post_mime_type', 'image' );
  $atts['thumbnail'] = bw_array_get( $atts, 'thumbnail', 'full' );
  return( bw_attachments( $atts ));
}


/** 
 * Return TRUE if the file names of the files are the same and the first is of type $extension
 *
 * We ignore the path information since the files could have been uploaded and attached in different months
 * This is a case sensitive search
 * **?** This should have been defined to pass a third parm of args which is an array of key value pairs
 * that way we can pass args to the $matchfunc
 *
*/
function bw_match_byguid_name( $given, $post, $extension='pdf' ) {
  $given_guid_name = pathinfo( $given->guid );
  $post_guid_name = pathinfo( $post->guid );
  $matched = ( $given_guid_name['extension'] == $extension &&  $given_guid_name['filename'] == $post_guid_name['filename'] );
  return( $matched );
}  

/**
 * Find a post in an array of post using the specified $matchfunc
 * This routine will not find the $given post
 *
*/
function bw_find_post( $posts, $given, $matchfunc="bw_match_byguid_name" ) {
  $matched = NULL;
  foreach ( $posts as $post ) {
    if ( $post->ID <> $given->ID ) {
      if ( $matchfunc( $given, $post ) ) {
        $matched = $post;
        break;
      }  
    }    
  }
  return( $matched );
}

/**
 * Format the matched post link 
 * @param post $post - the .pdf file for the link
 * @param post $matched_post - the image file with the matching name
 */
function bw_format_matched_link( $post, $matched_post, $atts ) {
  $class = bw_array_get( $atts, "class", "" );
  sdiv( $class );
  $image = retimage( "portfolio", $matched_post->guid, $post->post_title );
  $ptspan = "<span>".$post->post_title."</span>";
  alink( "portfolio", $post->guid, $image.$ptspan );
  ediv( $class );
}

/**
 * Process pairs of attachments
 */
function bw_paired_attachments( $posts, $atts ) {
  bw_trace2( $posts, "posts" );
  foreach ( $posts as $post ) {
  
    $matched_post = bw_find_post( $posts, $post );
    
    if ( $matched_post )
      bw_format_matched_link( $post, $matched_post, $atts ); 
  }
  return( bw_ret());
    
} 

/**
 * Display image links to PDF files
 * For each .PDF file that is linked to an image pair them up and display
 * with the image and the PDF file name as the selector and the 
 * PDF file name as the link.
 */
function bw_portfolio( $atts = NULL ) {
  $atts[ 'post_type'] = bw_array_get( $atts, "post_type", "attachment" );
  $atts['post_mime_type'] = bw_array_get( $atts, "post_mime_type", "image,application/pdf" );
  $atts['orderby'] = bw_array_get( $atts, "orderby", "title" );
  $atts['order'] = bw_array_get( $atts, "order", "ASC" );
  $posts = bw_get_posts( $atts );
  return( bw_paired_attachments( $posts, $atts ));
} 

