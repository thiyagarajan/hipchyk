<?php // (C) Copyright 2011,2012 Bobbing Wide
/**
 * Functions for a system which is NOT WordPress
 * It could be Drupal or some other PHP based CMS, perhaps not even a CMS
 */
 

/** 
 * I need to decide if I should prefix each field with bw_
 * Decision: I didn't prefix the fields in Drupal settings
 */
function bw_get_option( $field ) {
  return( variable_get( $field, "" ) );
}
 
/** 
 * Dummy implementation of add_filter() so that shortcodes.php can be copied unchanged from WordPress.
 */
function add_filter() {
 // Dummy implementation of add_filter() so that shortcodes.php can be copied unchanged from WordPress.
} 

/**
 * Dummy implementation of add_action for non WordPress sites
 */
function add_action( $tag, $function_to_add, $priority=NULL, $accepted_args=NULL ) {
// e.g. add_action( admin_init', array($this, 'bw_pp_admin_init'));
}

/** 
 * Drupal implementation of site_url using the 'domain' variable 
 * This is to allow for local implementations e.g. qw/contacts
 */
function site_url( $path=NULL, $scheme='http:' ) {
  $url  = $scheme; 
  $url .= '//';
  $url .= bw_get_option( 'domain' );
  $url .= $path;
  return( $url );
}  

if ( bw_is_drupal() ) {    
  require_once( "shortcodes.php" );  // This is a copy of the WordPress include specifically for the Drupal version of oik.
}
