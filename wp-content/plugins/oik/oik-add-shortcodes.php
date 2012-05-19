<?php 

// require_once( 'bwtrace.inc' );
/* Shortcodes for each of the more useful "often included key-information" fields 
   in Bobbing Wide's Wonder of WordPress websites
*/


/**
 *
 */
function _bw_missing_shortcodefunc( $atts, $hmm, $tag ) {
  global $bw_sc_file;
  // get_last_error ? 
  $result = '&#91;' . $tag . ']';
  $result .= "<b>Unable to locate routine to expand shortcode.</b>";
  // Stop it from attempting to load an external file over and over again
  $bw_sc_file[ $tag ] = false;
}

/**
 * Wrapper to include_once to prevent Warning messages returned in JSON response
 * @param string $file - filename of file to load
 * @return bool - return code from include_once or false if file does not exist
 *
*/
function bw_include_once( $file ) {
  if ( file_exists( $file )) {
    $rc = include_once( $file );
  } else {
    $rc = false; 
    bw_trace2( $file, "File does not exist" );
  }  
  return( $rc );    
}

/**
 * load the file that implements the shortcode if necessary
 *
 * the file is expected to be the fully qualified file name
 * for oik shortcodes these can be specified using oik_path( 'shortcodes/file.php' )
 * on the call to bw_add_shortcode().
 * @return bool - false if the file does not exist
 
 */  
function bw_load_shortcodefile( $shortcode ) {
  global $bw_sc_file;
  $file = bw_array_get( $bw_sc_file, $shortcode, false );
  if ( $file ) { 
    $file = bw_include_once( $file );
  }
  return( $file ); 
}  

/** 
 * Invoke the shortcode
 */ 
function bw_load_shortcodefunc( $shortcodefunc, $shortcode ) {

  if ( is_array( $shortcodefunc ) ) {
    if ( is_callable( $shortcodefunc ) ) {
      $scfunc = $shortcodefunc;
    } else {
      // Don't know what to do here
    }    
    
  } else {
    if ( function_exists( $shortcodefunc ) ) {
      $scfunc = $shortcodefunc;
    } else {
      $scfunc = '_bw_missing_shortcodefunc';
      if ( bw_load_shortcodefile( $shortcode ) && function_exists( $shortcodefunc ) ) {
        $scfunc = $shortcodefunc;
      }   
    }
  }    
  return( $scfunc );
} 
 
/** 
 * Expand a shortcode if the function is defined for the event
 *
 * If the function is not defined then simply return the tag inside []'s
 * Note: We use the HTML symbol for [ (&#91;) to prevent the shortcode being expanded multiple times
 
 * Extract from codes... 
 
 ; NOTE on confusing regex/callback name reference: 
 The zeroeth entry of the attributes array ('''$atts[0]''') will contain the string that matched the shortcode regex, 
 but ONLY if that differs from the callback name, which otherwise appears as the third argument to the callback function.
 ; (Appears to always appear as third argument as of 2.9.2.)

  add_shortcode('foo','foo'); // two shortcodes referencing the same callback
  add_shortcode('bar','foo');
     produces this behavior:
  [foo a='b'] ==> callback to: foo(array('a'=>'b'),NULL,"foo");
  [bar a='c'] ==> callback to: foo(array(0 => 'bar', 'a'=>'c'),NULL,"");

This is confusing and perhaps reflects an underlying bug, 
but an overloaded callback routine can correctly determine what shortcode was used to call it, 
by checking BOTH the third argument to the callback and the zeroeth attribute. 
(It is NOT an error to have two shortcodes reference the same callback routine, which allows for common code.) 
 
*/ 
function bw_shortcode_event( $atts, $hmm=NULL, $tag=NULL ) {
  global $bw_sc_ev, $bw_sc_ev_pp;
  // bw_backtrace();
  
  $cf = current_filter();
  if ( empty( $cf ) ) { $cf = 'wp_footer'; }
  
  $result = '&#91;' . $tag . ']';
  
  //bw_trace( $cf, __FUNCTION__, __LINE__, __FILE__, "current_filter" );
  //bw_trace( $tag, __FUNCTION__, __LINE__, __FILE__, "tag" ); 
  if ( isset( $bw_sc_ev[ $tag ][ $cf ] ))  {
    //bw_trace( $bw_sc_ev, __FUNCTION__, __LINE__, __FILE__, "bw_sc_ev" );
    $shortcodefunc = $bw_sc_ev[ $tag ][ $cf ];
    $shortcodefunc = bw_load_shortcodefunc( $shortcodefunc, $tag ); 
    $result = call_user_func( $shortcodefunc, $atts, $hmm, $tag );   
  } 
  bw_trace( $result, __FUNCTION__, __LINE__, __FILE__, "result" );
  if ( isset( $bw_sc_ev_pp[ $tag ][ $cf ] ))  {
    $ppfunc = $bw_sc_ev_pp[ $tag ][ $cf ];
    if ( function_exists( $ppfunc ) ) {
      $result = $ppfunc( $result, $cf ); 
    }
    else {
      $result .= "<b>missing post processing function: $ppfunc</b>";
    }
       
    bw_trace( $result, __FUNCTION__, __LINE__, __FILE__, "result" );
  }
  
  return $result;  
}

/** 
 * bw_strip_tags() is equivalent to esc_attr( strip_tags() )
 * but it also gets passed the current_filter - future use
*/
if ( !function_exists( "bw_strip_tags" ) ) {
  function bw_strip_tags( $string, $current_filter=NULL ) {
    $rstring = $string;
    $rstring = strip_tags( $rstring );
    $rstring = esc_attr( $rstring );
    return $rstring;
  }
}


/** 
 * bw_admin_strip_tags() strips tags if the content is being displayed on an admin page 
 * but it also gets passed the current_filter - future use
*/
function bw_admin_strip_tags( $string, $current_filter=NULL ) {

  bw_trace( $string, __FUNCTION__, __LINE__, __FILE__, "string" );
  $rstring = $string;
  if ( is_admin() ) {
    $rstring = bw_strip_tags( $rstring, $current_filter );
  }
  return $rstring;
}

 

/**
 * Add a shortcode function for a specific set of events
 *
 * This is a wrapper API for add_shortcode() that will affect how shortcodes are expanded during do_shortcode() processing.
 * Instead of calling the shortcode expansion function directly we always invoke bw_shortcode_event()
 * bw_shortcode_event() checks to see if the shortcode should be expanded in the context.
 * The $postprocess parameter is a function name for performing post processing of the $result in certain contexts
 * Possible functions are:
 *   bw_strip_tags
 *   bw_admin_strip_tags  
 *   etcetara tbc
*/
function bw_add_shortcode_event( $shortcode, $function=NULL, $eventlist='the_content,widget_text,the_title', $postprocess=NULL ) {
  global $bw_sc_ev, $bw_sc_ev_pp;
  //bw_trace( $shortcode, __FUNCTION__, __LINE__, __FILE__, "shortcode" );
 
  
  if ( $function == NULL ) {
    $function = $shortcode;
  }
  $events = explode( ",", $eventlist );
  foreach ( $events as $event )  {
    $bw_sc_ev[ $shortcode][ $event ] = $function;
    if ( $postprocess != NULL ) {
      $bw_sc_ev_pp[ $shortcode][ $event ] = $postprocess;
    }
  }  
  // bw_trace( $bw_sc_ev, __FUNCTION__, __LINE__, __FILE__, "bw_sc_ev" );

  add_shortcode( $shortcode, "bw_shortcode_event" );
}

/** 
 * Add the location for the lazy shortcode
*/
function bw_add_shortcode_file( $shortcode, $file=NULL ) {
  global $bw_sc_file;
  if ( $file ) {
    $bw_sc_file[$shortcode] = $file;
  }
} 

/**
 * Add a shortcode that safely expands in admin page titles
 * but is properly expanded in content and widget text
 * Note: settings_page_bw_email_signature is included to allow the shortcodes to be shown on the "oik email signature" page
 * bp_screens is included to support BuddyPress
 * get_the_excerpt is to support Artisteer 3.1 beta 1
*/
function bw_add_shortcode( $shortcode, $function=NULL, $file=NULL, $the_title=TRUE ) {
  bw_add_shortcode_event( $shortcode, $function, 'the_content,widget_text,wp_footer,get_the_excerpt,settings_page_bw_email_signature,bp_screens' );
  if ( $the_title ) {
    bw_add_shortcode_event( $shortcode, $function, 'the_title', 'bw_admin_strip_tags' );
  }  
  if ( $file ) { 
    bw_add_shortcode_file( $shortcode, $file );
  }  
}  

bw_add_shortcode_event( "bw_wtf");
bw_add_shortcode_event( "bw_wtf", NULL, "the_title", "bw_strip_tags" );
bw_add_shortcode_file( "bw_wtf", oik_path( "shortcodes/oik-wtf.php" ) );


bw_add_shortcode_event( 'bw_directions', 'bw_directions', 'the_content,widget_text' );

bw_add_shortcode( 'bw', 'bw' );
//bw_add_shortcode_event( "bw", "bw" );
//bw_add_shortcode_event( "bw", "bw", 'the_title', 'bw_admin_strip_tags' );

bw_add_shortcode_event( 'oik', 'bw_oik' );
bw_add_shortcode_event( "oik", "bw_oik", 'the_title', 'bw_admin_strip_tags' );


bw_add_shortcode( 'bw_address', 'bw_address');
bw_add_shortcode( 'bw_mailto', 'bw_mailto' );
bw_add_shortcode( 'bw_email', 'bw_email' );
bw_add_shortcode( 'bw_geo', 'bw_geo' );
bw_add_shortcode( 'bw_telephone', 'bw_telephone' );
bw_add_shortcode( 'bw_fax', 'bw_fax' );
bw_add_shortcode( 'bw_mobile', 'bw_mobile' );
bw_add_shortcode( 'bw_wpadmin', 'bw_wpadmin' );
bw_add_shortcode( 'bw_show_googlemap', 'bw_show_googlemap', oik_path("shortcodes/oik-googlemap.php"), false );
bw_add_shortcode( 'bw_contact', 'bw_contact' );

bw_add_shortcode( 'bw_twitter', 'bw_twitter' );
bw_add_shortcode( 'bw_facebook', 'bw_facebook' );
bw_add_shortcode( 'bw_linkedin', 'bw_linkedin' );
bw_add_shortcode( 'bw_youtube', 'bw_youtube' );
bw_add_shortcode( 'bw_flickr', 'bw_flickr' );
bw_add_shortcode( 'bw_picasa', 'bw_picasa' );
bw_add_shortcode( 'bw_skype', 'bw_skype' );

bw_add_shortcode( 'bw_googleplus', 'bw_google_plus' );
bw_add_shortcode( 'bw_google_plus', 'bw_google_plus' );
bw_add_shortcode( 'bw_google-plus', 'bw_google_plus' );
bw_add_shortcode( 'bw_google', 'bw_google_plus' );


bw_add_shortcode( 'bw_company', 'bw_company' );
bw_add_shortcode( 'bw_business', 'bw_business' );
bw_add_shortcode( 'bw_formal', 'bw_formal' );
bw_add_shortcode( 'bw_slogan', 'bw_slogan' );
bw_add_shortcode( 'bw_alt_slogan', 'bw_alt_slogan' );
bw_add_shortcode( 'bw_admin', 'bw_admin' );
bw_add_shortcode( 'bw_domain', 'bw_domain' );


bw_add_shortcode( 'clear', 'bw_clear' );
bw_add_shortcode( 'bw_tel', 'bw_tel' );
bw_add_shortcode( 'bw_mob', 'bw_mob' );


//add_shortcode( 'clever', 'bw_clever' );
bw_add_shortcode( 'bw_follow_me', 'bw_follow_me' );


//bw_add_shortcode( 'bw_logo', 'bw_logo' );
bw_add_shortcode_event( 'bw_logo', 'bw_logo', 'the_content,widget_text,settings_page_bw_email_signature' );
bw_add_shortcode_file( "bw_logo", oik_path( "shortcodes/oik-logo.php" ) );


bw_add_shortcode_event( 'bw_qrcode', 'bw_qrcode', 'the_content,widget_text,settings_page_bw_email_signature');

// Include [div]/[sdiv], [ediv] and [sediv] 
bw_add_shortcode( 'div', 'bw_sdiv' );
bw_add_shortcode( 'sdiv', 'bw_sdiv' );
bw_add_shortcode( 'ediv', 'bw_ediv' );
bw_add_shortcode( 'sediv', 'bw_sediv' );

bw_add_shortcode( 'bw_emergency', 'bw_emergency' );
bw_add_shortcode( 'bw_abbr', 'bw_abbr' );
bw_add_shortcode( 'bw_acronym', 'bw_acronym' );

bw_add_shortcode( 'bw_blockquote', 'bw_blockquote' );
bw_add_shortcode( 'bw_cite', 'bw_cite' );

// bw_backtrace();

bw_add_shortcode( 'bw_copyright', 'bw_copyright' );

bw_add_shortcode( 'stag', 'bw_stag' ); 
bw_add_shortcode( 'etag', 'bw_etag' );


/* We shouldn't let any of these expand in titles */

bw_add_shortcode( "bw_tree", "bw_tree", oik_path("shortcodes/oik-tree.php"), false );
bw_add_shortcode( "bw_posts", "bw_posts", oik_path("shortcodes/oik-posts.php"), false );


bw_add_shortcode( 'bw_pages', 'bw_pages', oik_path("shortcodes/oik-pages.php"), false );
bw_add_shortcode( 'bw_list', 'bw_list', oik_path("shortcodes/oik-list.php"), false );
bw_add_shortcode( 'bw_bookmarks', 'bw_bookmarks', oik_path("shortcodes/oik-bookmarks.php"), false );
bw_add_shortcode( 'bw_attachments', 'bw_attachments', oik_path("shortcodes/oik-attachments.php"), false );
bw_add_shortcode( 'bw_pdf', 'bw_pdf', oik_path("shortcodes/oik-attachments.php"), false );
bw_add_shortcode( 'bw_images', 'bw_images', oik_path("shortcodes/oik-attachments.php"), false );
bw_add_shortcode( 'bw_portfolio', 'bw_portfolio', oik_path("shortcodes/oik-attachments.php"), false );
bw_add_shortcode( 'bw_thumbs', 'bw_thumbs', oik_path("shortcodes/oik-thumbs.php"), false );
 


bw_add_shortcode( 'bw_button', 'bw_button_shortcodes', oik_path("shortcodes/oik-button.php"), false );
bw_add_shortcode( 'bw_contact_button', 'bw_contact_button', oik_path("shortcodes/oik-button.php"), false );


bw_add_shortcode( 'bw_block', 'bw_block', oik_path("shortcodes/oik-blocks.php"), false );
bw_add_shortcode( 'bw_eblock', 'bw_eblock', oik_path("shortcodes/oik-blocks.php"), false );
                                           
bw_add_shortcode( 'paypal', 'bw_pp_shortcodes', oik_path( "shortcodes/oik-paypal.php"), false );


//if ( $bw_plugins['oik-tides'] ) {
//  bw_add_shortcode( 'bw_tides', 'bw_tides', oik_path( "shortcodes/oik-tides.php"), false );
//}


/* Allow the NextGEN slideshow to be used in widgets as well as in context 
*/

bw_add_shortcode_event( 'ngslideshow', 'NextGEN_shortcodes::show_slideshow', 'the_content,widget_text' );
// bw_add_shortcode_file ( 'ngslideshow', oik_path( "shortcodes/oik-slideshows.php") );

bw_add_shortcode( 'gpslides', 'bw_gp_slideshow', oik_path( "shortcodes/oik-slideshows.php"), false  );

/* Shortcodes for each of the more useful APIs */
bw_add_shortcode( 'bwtron', 'bw_trace_on', oik_path( "shortcodes/oik-trace.php") , false );
bw_add_shortcode( 'bwtroff', 'bw_trace_off', oik_path( "shortcodes/oik-trace.php") , false );
bw_add_shortcode( 'bwtrace', 'bw_trace_button', oik_path( "shortcodes/oik-trace.php") , false );

add_action( "bw_sc_help", "bw_sc_help" );
add_action( "bw_sc_syntax", "bw_sc_syntax" );
add_action( "bw_sc_example", "bw_sc_example");
add_action( "bw_sc_snippet", "bw_sc_snippet" );


bw_add_shortcode_file( 'portfolio_slideshow', oik_path( "shortcodes/oik-slideshows.php") );
bw_add_shortcode_file( 'nggallery', oik_path( "shortcodes/oik-galleries.php" ) );

bw_add_shortcode( "bw_power", "bw_power", oik_path( "shortcodes/oik-bob-bing-wide.php" ) );
bw_add_shortcode( 'bw_editcss', 'bw_editcss', oik_path("shortcodes/oik-bob-bing-wide.php"), false );

