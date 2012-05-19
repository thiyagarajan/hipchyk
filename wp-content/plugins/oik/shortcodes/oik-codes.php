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
oik_require( "includes/oik-sc-help.inc" );

function bw_code_link( $shortcode ) {
  if ( is_admin() ) {
     alink( null, admin_url("admin.php?page=oik_sc_help&amp;code=$shortcode"), $shortcode );
  } else {
    e( $shortcode );
  }  
  e( " - " );
}


function bw_get_shortcode_syntax_link( $shortcode, $callback ) {

  //p( "Shortcode $shortcode, callback $callback" );
  //bw_tablerow( array( $shortcode, $link ) );
  stag( "tr" );
  stag( "td" );
  bw_code_link( $shortcode );
  
  do_action( "bw_sc_help", $shortcode );
  //do_action( "bw_sc_example", $shortcode );
  etag( "td" );
  stag( "td" );
  do_action( "bw_sc_syntax", $shortcode );
  etag( "td" );
  
  stag( "td" );
  $link = "http://www.oik-plugins.com/oik-shortcodes/$shortcode";
  alink( NULL, $link, "$shortcode help" );   
  etag( "td");
  //bw_td( $shortcode );
  //bw_td( $link );
  etag( "tr" );
}



/**
 * table header for bw_codes
 *
 * <table>
 * <tbody>
 * <tr>
 * <th>Shortcode</th>
 * <th>Help link</th>
 * <th>Syntax</th>
 * </tr>
*/
function bw_help_table( $table=true ) {
  if ( $table ) {
    stag( "table", "widefat" );   
    stag( "thead" ); 
    stag( "tr" );
    th( "Help" );
    th( "Syntax" );
    th( "more oik help" );
    etag( "tr" );
    etag( "thead" );
 
    stag( "tbody" );
  }  
}

/**
 * table footer for bw_plug 
 */
function bw_help_etable( $table=true ) { 
  if ( $table ) {
    etag( "tbody" );
    etag( "table" );
  }  
}

/**
 * Return an associative array of shortcodes and their one line descriptions (help)
 *
 * @param array $atts - attributes - currently unused
 * @return array - associative array of shortcode => description
 *
 * The array is ordered by shortcode
 * @uses _bw_lazy_shortcode_help() rather than
*/ 
function bw_shortcode_list( $atts=null ) {
  global $shortcode_tags; 
  
  foreach ( $shortcode_tags as $shortcode => $callback ) {
    $schelp = _bw_lazy_sc_help( $shortcode );
    $sc_list[$shortcode] = $schelp;
  }
  ksort( $sc_list );
  return( $sc_list );
}  


  
function bw_list_shortcodes( $atts = NULL ) {
  global $shortcode_tags;
  $ordered = bw_array_get( $atts, "ordered", "N" );
  $ordered = bw_validate_torf( $ordered ); 
  //bw_trace2( $shortcode_tags );
  //bw_trace2( $ordered, "ordered" );
  
  if ( $ordered ) {
    ksort( $shortcode_tags );
  }
  //bw_trace2( $shortcode_tags, "shortcode_tags" );
  add_action( "bw_sc_help", "bw_sc_help" );
  add_action( "bw_sc_example", "bw_sc_example" );
  add_action( "bw_sc_syntax", "bw_sc_syntax" );
  
  
  bw_help_table();
  foreach ( $shortcode_tags as $shortcode => $callback ) {
    bw_get_shortcode_syntax_link( $shortcode, $callback );
  }
  bw_help_etable();

  
}


function bw_codes( $atts = NULL ) {
  $text = "&#91;bw_codes] is intended to show you all the active shortcodes and give you some help on how to use them. ";
  $text .= "If a shortcode is not listed then it could be that the plugin that provides the shortcode is not activated. ";
  $text .= "Click on the link to find detailed help on the shortcode and its syntax. "; 
  e( $text );  
  $shortcodes = bw_list_shortcodes( $atts );

  return( bw_ret());
} 

function bw_code( $atts = NULL ) {
  $shortcode = bw_array_get( $atts, "shortcode", 'bw_code' );
  $help = bw_array_get( $atts, "help", "Y" );
  $syntax = bw_array_get(  $atts,  "syntax", "Y" );
  $example = bw_array_get( $atts, "example", "Y" );
  $live = bw_array_get( $atts, "live", "N" );
  $snippet = bw_array_get( $atts, "snippet", "N" );
  
  $help = bw_validate_torf( $help );
  if ( $help ) {
    p( "Help for shortcode: [${shortcode}]", "bw_code_help" );
    //bw_trace2( $shortcode, "before do_action" );
    do_action( "bw_sc_help", $shortcode );
  }  
  $syntax = bw_validate_torf( $syntax );
  if ( $syntax ) {
    p( "Syntax", "bw_code_syntax" ); 
    do_action( "bw_sc_syntax", $shortcode );
  }  
  $example = bw_validate_torf( $example );
  if ( $example ) {
    p( "Example", "bw_code_example");
    
    do_action( "bw_sc_example", $shortcode );
  }
  

  $live = bw_validate_torf( $live ) ;
  if ( $live ) {
    p("Live example", "bw_code_live_example" );
    $live_example = bw_do_shortcode( '['.$shortcode.']' );
    e( $live_example );
  }
  
  $snippet = bw_validate_torf( $snippet );
  if ( $snippet ) {
    p( "Snippet", "bw_code_snippet" );
    do_action( "bw_sc_snippet", $shortcode );
  }

  return( bw_trace2( bw_ret(), "bw_code_return"));
} 
   

