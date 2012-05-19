<?php

/*
Plugin Name: oik email signature 
Plugin URI: http://www.oik-plugins.com/oik-plugins/oik-email-signature
Description: Generate an email signature file for your email client
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
require_once( 'bwtrace.inc');
require_once( 'bobbfunc.inc' );
require_once( 'oik-add-shortcodes.php' );

function oik_email_signature_version() {
  return oik_version();
  
}


  add_action('admin_init', 'bw_email_signature_init' );
  add_action('admin_menu', 'bw_email_signature_add_page');
  bw_add_shortcode( 'bw_email_signature', 'bw_email_signature' );


// Init plugin options to white list our options
function bw_email_signature_init(){
  bw_trace2();
	register_setting( 'bw_email_signature_options', 'bw_email_signature', 'bw_email_signature_validate' );
}


// Add menu page
function bw_email_signature_add_page() {
	add_options_page('oik email signature', 'oik email signature', 'manage_options', 'bw_email_signature', 'bw_email_signature_do_page');
}



// Draw the menu page itself
function bw_email_signature_do_page() { 
  require_once( "bobbforms.inc" );

  sdiv( "wrap" );
  h2( bw_oik(). " email signature" );
  e( '<form method="post" action="options.php">' ); 
  $options = get_option('bw_email_signature');     
  stag( 'table class="form-table"' );
  bw_flush();
  settings_fields('bw_email_signature_options'); 
  
  $instructions = "<br />Type the HTML and oik shortcodes you want to use in your email signature and click on Save changes"; 
  $instructions .= "<br />You may also need to add some CSS style information.";
  p( $instructions );
  textarea( "bw_email_signature[text]" , 80, "Email signature [bw_email_signature]", $options['text']  );
  
    
  tablerow( "", "<input type=\"submit\" name=\"ok\" value=\"Save changes\" />" ); 

  etag( "table" ); 			
  etag( "form" );
  
  ediv(); 
  sediv( "clear" );
  bw_flush();
  
  sdiv("wrap");
  
  h2( bw_oik() . " email signature preview" );
  p("This is a preview of the HTML for your email signature");
  //hr();
  
  // Note: We haven't yet created a [bw_email_signature] shortcode.
  // It's not needed for this code. Here we expand the shortcodes in the text field
  bw_flush();
  $text = $options['text'];    
  $formatted_text = do_shortcode( $text );
  
  e( $formatted_text );
  bw_flush();
  
  // Now create the version that the user can copy and paste into a file
  ediv();
  sdiv( "wrap" );
  h2(bw_oik() . " email signature HTML");
  p( "This is the HTML to copy and paste into your email signature file.");
  p( "Note: you may also need to include some CSS styling in order to make your email signature match what you see above");
  p( "This will be part of a future version of " . bw_oik() );
  
  
  $escaped_text = esc_html( $formatted_text );
  e( $escaped_text );
  
 
  h2("More about " . bw_oik());
  p("For more information:" );
  art_button( "http://www.oik-plugins.com/oik", bw_oik() . " documentation", "Read the documentation for the oik plugin" );
  ediv();      
  bw_flush();
}


// Sanitize and validate input. Accepts an array, return a sanitized array.
function bw_email_signature_validate($input) {
	// There is no validation at present
	return $input;
}

function bw_email_signature( $atts ) {

  $options = get_option('bw_email_signature'); 
   
  $text = $options['text'];    
  $formatted_text = do_shortcode( $text );
     
  return( $formatted_text );
}


