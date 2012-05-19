<?php //(C) Copyright Bobbing Wide 2011, 2012

/**
 * Display the company logo with a link if required
 * Notes: the attribute defaulting needs to be improved 
*/ 
function bw_logo( $atts ) {
  
  wp_register_script( "oik_bw_logo", plugin_dir_url( __FILE__). "oik_bw_logo.js" );  
  wp_enqueue_script( "oik_bw_logo" );
  
  $link = bw_array_get( $atts, 'link', null );
  $text = bw_array_get( $atts, 'text', null );
  $width = bw_array_get( $atts, 'width', null );
  $height = bw_array_get( $atts, 'height', null );


  $upload_dir = wp_upload_dir();
  $baseurl = $upload_dir['baseurl'];
  
  $logo_image = bw_get_option( "logo-image" );
  if ( $text )
    $company = $text;
  else   
    $company = bw_get_option( "company" );
  $image_url = $baseurl . $logo_image;
  
  $image = retimage( "bw_logo", $image_url, $company, $width, $height );
  if ( $link ) {
    alink( "bw_logo", $link, $image, $company );
  }  
  else {
    e( $image );  
  }  
  return( bw_ret());
    
}


