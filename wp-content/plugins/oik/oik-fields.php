<?php

/*
Plugin Name: oik fields
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-fields
Description: [bw_field] [bw_fields] shortcodes to display Custom Fields (post metadata)
Version: 1.13
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

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
//require_once( 'oik-artisteer.php' );


bw_add_shortcode( 'bw_field', 'bw_metadata' );
bw_add_shortcode( 'bw_fields', 'bw_metadata' );
// bw_add_shortcode( 'bw_metadata', 'bw_metadata' );


/**
 * Format a field - by what method? 
 *
*/
function bw_format_field( $customfield ) {
  bw_trace2();
  foreach ( $customfield as $key => $value ) {
    bw_theme_field( $key, $value );
  }
}

/**
 * format the meta data for the 'post'
 */
function bw_format_meta( $customfields ) {
  
  bw_trace2();
  foreach  ( $customfields as $key => $customfield ) {
    $cf = array( $key => $customfield[0] );
    bw_format_field( $cf );
  }  
} 

/**
 * Wrapper to get_post_meta
 */
function bw_metadata( $atts = NULL ) {
  $post_id = bw_array_get( $atts, "id", get_the_id());

  $name = bw_array_get( $atts, "name", NULL );
  if ( NULL == $name ) {
    // the_meta();
    $customfields = get_post_custom( $post_id );
    bw_format_meta( $customfields );
  }
  else {
    $name = wp_strip_all_tags( $name, TRUE );
    $names = explode( ",", $name );
    
    foreach ( $names as $name ) {
      $post_meta = get_post_meta( $post_id, $name, FALSE );
      bw_trace2( $post_meta );
      $customfields = array( $name => $post_meta ); 
      bw_format_meta( $customfields );
    }
  }  
  
  return( bw_ret() );

}

/**
 * Theme a custom field  
 * 
 * @param string $key - field name e.g. _txn_amount
 * @param mixed $value - post metadata value
 * @param array $field - the field structure if defined using bw_register_field()
 *
 * 
 * 
 */

function bw_theme_field( $key, $value, $field=null ) {
  $type = bw_array_get( $field, "#field_type", null );
  // Try for a theming function named "bw_theme_field_$type_$key 
  
  $funcname = bw_funcname( "bw_theme_field_${type}", $key );
  // If there isn't a generic one for the type 
  // nor a specific one just try for the field
  
  if ( $funcname == "bw_theme_field_" && $type ) { 
    $funcname = bw_funcname( "bw_theme_field_", $key );
  }  
  bw_trace2( $funcname );
  
  if ( is_callable( $funcname ) ) {
    call_user_func( $funcname,  $key, $value, $field );
  } else {
    _bw_theme_field_default( $key, $value, $field );
  }
}  

/** 
 *   Default theming of metadata based on field name ( $key )
 *   or content? ( $value )
 */   

function _bw_theme_field_default( $key, $value ) {

  $funcname = "_bw_theme_field_default_" . $key;
  if ( function_exists( $funcname ) ) {
    $funcname( $key, $value );
  } else {
    // this could be a function called _bw_theme_field_default__unknown_key
    span( "metadata $key" );
    span( $key ); 
    e( $key );
    epan( $key );
    span( "value" );
    e( $value );
    epan( "value" ); 
    epan( "metadata $key" );
  }  
}

/** 
 * Default function to display a field of name "bw_header_image"
 * 
 * A 'bw_header image' field contains the full file name of the image to be used as the header image
 * It's not exactly related to "custom header image" but could be.
 */
function _bw_theme_field_default_bw_header_image( $key, $value ) {
  image( $key, $value );
} 


/**
 * Template tag to return the header image for a specific page
 *
 *
 * If none is specified then it doesn't return anything
 * so should we then call custom_header logic?
 */
if ( !(function_exists( "bw_header_image" ))) {
 
  function bw_header_image() {
    return( bw_metadata( array( "name" => "bw_header_image" )));
  } 
}





