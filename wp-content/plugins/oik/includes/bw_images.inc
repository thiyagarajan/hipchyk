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
 * Return an array suitable for passing to image functions to determine the size
 * 
 * @param mixed $size string representing the size.
 * if a single integer then make the array square
 * otherwise it's widthxheight or width,height or some other way of specifying width and height
 * so we split at the non numeric value(s) and take the first two integer bits
 * @return array containing width and height
 */
function bw_get_image_size( $size=100 ) {
  $pattern = "/([\d]+)/";
  preg_match_all( $pattern, $size, $thumbnail );
  bw_trace2( $thumbnail );
  if ( count( $thumbnail[0] ) < 2 ) 
    $thumbnail[0][1] = $thumbnail[0][0];
    
  $size = array( $thumbnail[0][0], $thumbnail[0][1] );  

  // bw_trace( $size, __FUNCTION__, __LINE__, __FILE__, "size" );
  return( $size ); 
}

/**
 * Force the thumbnail size to be what we asked for
 * 
 * wp_attachment_get_image_src() can sometimes attempt to return a $thumbnail where the
 * width and height don't match what we asked for.
 * 
 * The values of $thumbnail[1] and $thumbnail[2] are used to set the width and height values on the <img> tag
 * We choose to reset these to the values first thought of. 
 * 
 * Note: The side effect of this is that the images get reshaped rather than being cropped.
 * It's up to the web designer to set the sizes for thumbnail, small, medium and large
 * and/or upload images that will scale nicely for the chosen figures. 
 *
 * **?** Perhaps we could try to do this in some wrapping div rather than against the img tag.
 *
 */
function bw_force_size( $thumbnail, $size ) {
  if ( is_array( $size ) ) {
  
    $thumbnail[1] = $size[0];
    $thumbnail[2] = $size[1];
  }  
  return( $thumbnail );
}

/**
  * Validate the thumbnail size, returning appropriate value for subsequent calls
  */
function bw_get_thumbnail_size( $atts ) {
  $thumbnail = bw_array_get( $atts, 'thumbnail', 'thumbnail' );
  switch ( $thumbnail ) {
    case 'none';
      $thumbnail = false;
    break; 
     
    case 'full';
      $thumbnail = bw_get_fullimage( $post_id, $thumbnail, $atts );
    break;
    
    case 'thumbnail':
    case 'medium':
    case 'large':
    break;
           
    default:
      $torf = bw_validate_torf( $thumbnail );
      if ( $torf ) {
        $thumbnail = 'thumbnail';
      } else {
        $thumbnail = bw_get_image_size( $thumbnail ); 
      } 
  } 
  bw_trace2( $thumbnail );
  return( $thumbnail );
}  
 
/**
 * Get the thumbnail of the specified size
 *
 * Note: I don't think getting the 'full' size thumbnail makes any sense **?**
 */
function bw_thumbnail( $post_id, $atts=NULL, $attachment=false ) {
  bw_trace( $post_id, __FUNCTION__, __LINE__, __FILE__, "post_id" );
  $thumbnail = bw_get_thumbnail_size( $atts );
  if ( $thumbnail ) {
    switch ( $thumbnail ) {
      case 'full';
        $thumbnail = bw_get_fullimage( $post_id, $thumbnail, $atts );
      break;     
      case 'thumbnail':
      case 'medium':
      case 'large':
      default: 
        if ( $attachment ) {
          $thumbnail = bw_get_attachment_thumbnail( $post_id, $thumbnail, $atts );
        } else {
          $thumbnail = bw_get_thumbnail( $post_id, $thumbnail, $atts );
        }
      break;
    }
  }  
  return( $thumbnail ); 
}


function bw_get_fullimage( $post_id, $size='full', $atts=null ) {
  $return_value = FALSE;
  $arr_thumb = bw_get_attached_image( $post_id, 1, 'rand', $size );
  bw_trace( $arr_thumb, __FUNCTION__, __LINE__, __FILE__, "arr_thumb" );
  $thumbnail = bw_array_get( $arr_thumb, 0, null );
  $img = bw_array_get( $thumbnail, 0, null );
  if ( $img ) {
    $text = bw_array_get( $atts, "title", NULL );
    $return_value = retimage( "bw_thumbnail", $img, $text);
  }      
  bw_trace( $return_value, __FUNCTION__, __LINE__, __FILE__, "return_value" ); 
  return( $return_value );
} 

/** 
 * Create a thumbnail link
 *
 * Create a thumbnail with a link to the post_id specified, either via $post_id or the $atts['link']
 * otherwise just create the image 
 *
 * @param string $thumbnail full HTML for the thumbnail image
 * @param id     $post_id   default post id if not specified in $atts
 * @param array  $atts      shortcode attributes array
 *
 * As of v1.13 this supports custom image links
 */
function bw_link_thumbnail( $thumbnail, $post_id=NULL, $atts=NULL )  {
  bw_trace2();
  $link_id = bw_array_get( $atts, "link", $post_id );
  if ( $link_id ) {
    $text = bw_array_get( $atts, "title", NULL );
    $class = bw_array_get( $atts, "imgclass", null );  /* changed from class to imgclass **?** 2012/03/22 */
    //alink( $class, get_permalink( $link_id ), $thumbnail, $text, "link-".$link_id );  
    alink( $class, bw_get_image_link( $link_id ), $thumbnail, $text, "link-".$link_id );  
  } else {
    e( $thumbnail );
  }
}

/** 
 * get the post thumbnail 
 * Returns the HTML for the thumbnail image which can then be wrapped in a link if required
 * @param integer $post_id - the id of the content for which the thumbnail is required
 *                defaults to the current post id
 * @param mixed  $size - the required image size: either a preset or specified in an array
 * @atts  array  array of key value pairs that may be needed
 * 
 *  Return Value: An array containing:
 *    $image[0] => url
 *    $image[1] => width
 *    $image[2] => height
 *
 */
function bw_get_thumbnail( $post_id = null, $size = 'thumbnail', $atts=NULL ) {
  $return_value = FALSE;
  if ($post_id == null) 
    $post_id = get_the_id();
  
  bw_trace( $post_id, __FUNCTION__, __LINE__, __FILE__, "post_id" );
  
  if ( has_post_thumbnail( $post_id ) ) {
    //bw_trace( $size, __FUNCTION__, __LINE__, __FILE__, "size" );
    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
  }
  elseif ( function_exists('get_post_thumbnail_id') && $thumb_id = get_post_thumbnail_id( $post_id ) ) {
    // bw_trace( $thumb_id, __FUNCTION__, __LINE__, __FILE__, "thumb_id" );
    $thumbnail =  wp_get_attachment_image_src( $thumb_id, $size ) ;
  }  
  elseif ( $arr_thumb = bw_get_attached_image( $post_id, 1, 'rand', $size )) {
    //bw_trace( $arr_thumb, __FUNCTION__, __LINE__, __FILE__, "arr_thumb" );
    $thumbnail = $arr_thumb[0];
  } else {
    $thumbnail = NULL; 
  }  
  // bw_trace( $thumbnail, __FUNCTION__, __LINE__, __FILE__, "thumbnail" ); 
  if ( bw_array_get( $thumbnail, 0, FALSE)  ) {
    $text = bw_array_get( $atts, "title", NULL );
    $thumbnail = bw_force_size( $thumbnail, $size );
    $return_value = retimage( "bw_thumbnail", $thumbnail[0], $text, $thumbnail[1], $thumbnail[2] ); 
  }
  bw_trace( $return_value, __FUNCTION__, __LINE__, __FILE__, "return_value" ); 
  return( $return_value );
}

/**
 * get the attached image 
 *
 * Return an array of images attached to a specific post ID
 *   
 * Return Value: An array containing:
 *       $image[0] => url
 *       $image[1] => width
 *       $image[2] => height
 *       $image[3] => attachment id
 */
function bw_get_attached_image( $post_id = null, $number = 1, $orderby = 'rand', $image_size = 'thumbnail') {
  if ($post_id == null) 
    $post_id = get_the_id();
  bw_trace2(); 
  $number = intval( $number );
  $arr_attachment = get_posts( array( 'post_parent'    => $post_id,
                                      'post_type'      => 'attachment',
                                      'numberposts'    => $number,
                                      'post_mime_type' => 'image',
                                      'orderby'        => $orderby ));
  if ( count( $arr_attachment ) ) {
    foreach ( $arr_attachment as $index => $attachment ) {
      $arr_attachment[$index] = array_merge ( (array) wp_get_attachment_image_src($attachment->ID, $image_size), array($attachment->ID) );
    }
  }
  bw_trace( $arr_attachment, __FUNCTION__, __LINE__, __FILE__, "arr_attachment" );
  return $arr_attachment;
}

/**
 * Get the "thumbnail" image for an attachment
 * 
 * @param int $post_id - ID of the attachment
 * @param mixed $size - size of the required image
 * @param array $atts - contains the title for the attachment
 * @return string $thumbnail - either an image or just the attachment file name
 * 
 */ 
function bw_get_attachment_thumbnail( $post_id, $size, $atts ) {
  $thumbnail_arr = wp_get_attachment_image_src( $post_id, $size, false );
  bw_trace2( $thumbnail_arr, "thumbnail_arr" );
  $thumbnail = bw_array_get( $thumbnail_arr, 0, null);
  $text = bw_array_get( $atts, "title", NULL );
  if ( $thumbnail ) {
  
    $thumbnail_arr = bw_force_size( $thumbnail_arr, $size );
    
    $thumbnail = retimage( "bw_thumbnail", $thumbnail, $text, $thumbnail_arr[1], $thumbnail_arr[2] ); 
  } else {
    $thumbnail = wp_get_attachment_link( $post_id, $size, false, false ); 
  }  
  //   $attachment_id, $size='thumbnail', $icon = false)
  // function wp_get_attachment_link( $id = 0, $size = 'thumbnail', $permalink = false, $icon = false, $text = false ) {
  return( bw_trace2( $thumbnail ));
}

  
  






