<?php // (C) Copyright Bobbing Wide 2010-2012
// bobbcomp.inc - Company Information

function bw_get_cms_type() {
  global $bw_cms_type, $bw_cms_version;
  $bw_cms_type = "unknown";
  if ( function_exists( 'is_blog_installed' )) {
    $bw_cms_type = 'WordPress';
     return( $bw_cms_type );       
    
  }   
  if ( function_exists( 'drupal_bootstrap' )) {
    $bw_cms_type = "Drupal"; 
    if (defined( VERSION ) ) {
      $bw_cms_version = VERSION;
      
    return( $bw_cms_type );       
    }  
  }
  //exit( "Unknown CMS");
  
  return( $bw_cms_type );       
}

function bw_is_wordpress() {
  return( bw_get_cms_type() == "WordPress" );
}
  
function bw_is_drupal() {
  return( bw_get_cms_type() == "Drupal" );
} 



if ( bw_is_wordpress() ) {
  function bw_get_option( $field, $set="bw_options" ) {
    //global $company;
    /* WordPress code */
    //global $bw_options;
    /* Need to put some code here to see if it's been loaded */
    $bw_options = get_option( $set );
    
    bw_trace( $bw_options, __FUNCTION__,  __LINE__, __FILE__, "bw_options" );  
    if ( isset( $bw_options[ $field ] ) )
      $option = $bw_options[ $field ] ; 
    else
      $option = NULL;  
      
    // Note: A value that appears to be blank ( == '') may actually be FALSE ( == FALSE )
    // bw_trace( '!' .$option.'!', __FUNCTION__,  __LINE__, __FILE__, "option" );  
    return( $option ); 
  }
  
  /** Set the value of an oik option field in the "bw_options" field
   *
   * @param string $field the option field to be set
   * @param mixed $value the value of the option
   * @return mixed $value
   *
   * Parms are basically the same as for update_option
   */
  function bw_set_company( $field, $value=NULL ) {
    //global $bw_options;
    $bw_options = get_option( "bw_options" );
    $bw_options[ $field ] = $value;
    bw_trace2( $bw_options );
    update_option( "bw_options", $bw_options );
    return( $value );
  }  
  
}
else {
  oik_require( "bobbnotwp.inc" );
}


function bw_get_geo( $separator, $alt=null ) {
  $geo = bw_get_option( "lat", "bw_options$alt" );
  $geo.= $separator;
  $geo.= bw_get_option( "long", "bw_options$alt" );
  return( $geo );
}  

function bw_geo( $atts=null ) {
  $alt = bw_array_get( $atts, "alt", null );
  sp("geo");
  span( "geo");
    e( "Lat." ); 
    span( "latitude" );
    e( bw_get_option( "lat", "bw_options$alt" ) );
    epan();
    // I think we should have a space between the lat. and long. values
    e( "&nbsp;");
    e( "Long." );
    span( "longitude" );
    e( bw_get_option( "long", "bw_options$alt" ) );
    epan();
  epan();
 ep(); 
 return( bw_ret());

}

/**
 * return the telephone number in desired HTML markup, if set or passed as number=
 
 * @param array atts
 *   prefix = type of number: free form e.g. Tel, Home, Work, Mob, Cell
 *   sep = separator default ': '
 *   number = telephone number override
 *   tag = 'div' or 'span' or other HTML wrapping tag - with start and end
 *   index = field to obtain ( telephone, fax, mobile, emergency )
 *   alt = 1 if alternative number are required
 *
 * Example:
 * [bw_telephone]
 * [bw_telephone number="023 92 410090" prefix="Portsmouth"]
 *
 */
function _bw_telephone( $atts = null ) {
  $prefix = bw_array_get_dcb( $atts, "prefix", "Tel" );
  $sep = bw_array_get( $atts, "sep", ": " );
  $number = bw_array_get_dcb( $atts, "number", null );
  $alt = bw_array_get( $atts, "alt", null );
  $index = bw_array_get( $atts, "index", "telephone" );
  $tag = bw_array_get( $atts, "tag", "div" );
  $class = bw_array_get( $atts, "class", null );
  
  if ( !$number ) {
    $number = bw_get_option( $index, "bw_options$alt" );
  } 
   
  if ( $number <> "" ) {
    stag( $tag,  "tel $class" );
    span( "type");
    e( $prefix );
    epan();
    e( $sep );
    span( "value" );
    e( $number );
    epan();
    etag( $tag );
  }
  return( bw_ret());
}


/**
 * Display the telephone number using microformats
 * See ISBN: 978-1-59059-814-6 p. 140
 */
function bw_telephone( $atts = null ) { 
  $atts['index'] = bw_array_get( $atts, "index", "telephone" );
  return( _bw_telephone( $atts ) );
}  

/**
 * Display the fax number, if set
 */
function bw_fax( $atts = null ) {
  $atts['index'] = bw_array_get( $atts, "index", "fax" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Fax" );
  return( _bw_telephone( $atts ) );
}  

function bw_mobile( $atts = null ) {
  $atts['index'] = bw_array_get( $atts, "index", "mobile" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Mobile" );
  return( _bw_telephone( $atts ) );
}

function bw_emergency( $atts = null ) {
  $atts['index'] = bw_array_get( $atts, "index", "emergency" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Emergency" );
  $atts['class'] = bw_array_get_dcb( $atts, "class", "emergency" );
  return( _bw_telephone( $atts ) );
}  

// Notes: Using class=email for Microformat

/**
 *
 *
 *  use [bw_email] - for an inline mailto link 
 *  or [bw_mailto] for a more formal mailto link
 * I'm sure I used antispambot() once upon a time. Reintroduced but it needs more work
 
*/   

function _bw_email( $atts = null ) {
  $prefix = bw_array_get_dcb( $atts, "prefix", "Email" );
  $sep = bw_array_get( $atts, "sep", ": " );
  $index = bw_array_get( $atts, "index", 'email' );
  $alt = bw_array_get( $atts, "alt", null );
  $title = bw_array_get_dcb( $atts, "title", "Send email to: " );
  $tag = bw_array_get( $atts, "tag", "span" );
   
  stag( $tag, "email");
  
  e( $prefix );
  e( $sep );
  $email = bw_get_option( $index, "bw_options$alt" ); 
  $emailasb = antispambot( $email, 1 ); 
  alink( NULL, "mailto:". $emailasb, $email, $title . $email );
  etag( $tag );
  
  return( bw_ret() );
}

function bw_email( $atts = null ) {
  $atts['tag'] = bw_array_get( $atts, "tag", "span" );
  return( _bw_email( $atts) ); 
}  

/* [bw_mailto] is for a mailto link in a paragraph or div
*/
function bw_mailto( $atts=null ) {
  $atts['tag'] = bw_array_get( $atts, "tag", "p" );
  return( _bw_email( $atts ) );
}  


/**
 * Return address using Microformats
 *
 * 
 */
function bw_address( $atts = NULL ) {
  $type = bw_array_get( $atts, "type", "Work" );
  $alt = bw_array_get( $atts, "alt", null );
  
  sdiv("adr" );
  
    sdiv("type");
    e( $type );
    ediv();
    
    sdiv("extended-address");
    e( bw_get_option( "extended-address", "bw_options$alt" ) );
    ediv();
    
    sdiv("street-address");
    e( bw_get_option( "street-address", "bw_options$alt"  ) );
    ediv();  
    
    sdiv("locality");
    e( bw_get_option( "locality", "bw_options$alt" ) );
    ediv();      
    
    sdiv("region");
    e( bw_get_option( "region", "bw_options$alt" ) );
    ediv();      
    
    sdiv("postal-code");
    e( bw_get_option( "postal-code", "bw_options$alt" ) );
    ediv();
       
    span("country-name");
    e( bw_get_option( "country-name", "bw_options$alt" ) );
    epan();
  
  ediv();
  return( bw_ret() );
  
  
}

function bw_address__syntax( $shortcode="bw_address" ) {
  $syntax = array( "type" => bw_skv( "Work", "<i>type</i>", "Address type." )
                 , "alt" => bw_skv( "", "1", "suffix for alternative address" )
                 );
  return( $syntax );
}

function bw_address__example( $shortcode="bw_address" ) {
 p( "Display the address defined in oik options" );
 e( bw_address() );
} 

/* Return a link to the site's wp-admin 
*/
function bw_wpadmin() {
  $site = bw_get_option( "domain" );
  
  e( "Site:&nbsp; ");
  alink( NULL, "http://". $site . "/wp-admin", $site, "Website: " . $site );
  return( bw_ret() );
  
}

/**
 * Format the contact name using hCard Microformat
*/ 
function bw_contact( $atts=null ) {
  $alt = bw_array_get( $atts, "alt", null );
  $contact = bw_get_option( "contact", "bw_options$alt" );
  span( "vcard" );
  span( "fn" );
  e( $contact );
  epan();
  epan();
  return( bw_ret() );
} 





/**
 * create a styled follow me button
 * 
 * @param array atts - array of shortcode attributes
 * 
 * network= the name of the social network - this gets lowercased to choose the button class and the oik option field
 * url = override of who to follow. value defaults to the oik option for the network
 * me = who to follow - defaults to "me"
 * text = parameter that gets ignored at present! 
 */
function bw_follow( $atts = NULL ) {
  $social_network = bw_array_get( $atts, 'network', 'Facebook' );
  $text = bw_array_get( $atts, 'text', null );
  
  $lc_social = strtolower( $social_network );
  $social = bw_array_get_dcb( $atts, 'url', $lc_social, "bw_get_option" );
  $me = bw_array_get( $atts, "me", "me" );
  // bw_trace( $text, __FUNCTION__, __LINE__, __FILE__ , "text" );
  //if ( $text ) {
  //  $text = $social_network;
  //}
    
  $imagefile = plugins_url( 'images/'. $lc_social . '_48.png', __FILE__ );
    
  $image = retimage( NULL, $imagefile, $social_network );
  alink( NULL, $social, $image, "Follow $me on ".$social_network );
  //  alink( $social_network, $social, $text, "Follow $me on ".$social_network );
       
  return( bw_ret());
}  

function bw_twitter( $atts ) {
  $atts['network'] = "Twitter" ;
  return( bw_follow( $atts ));  
}

function bw_facebook( $atts ) {
  $atts['network'] = "Facebook" ;
  return( bw_follow( $atts ));
}

function bw_linkedin( $atts ) { 
  $atts['network'] = "LinkedIn";  
  return( bw_follow( $atts ));
} 
   
function bw_youtube( $atts ) { 
  $atts['network'] = "YouTube";  
  return( bw_follow( $atts ));
}

function bw_picasa( $atts ) { 
  $atts['network'] = "Picasa";  
  return( bw_follow( $atts ));
}
    
function bw_flickr( $atts) {
  $atts['network'] = "Flickr";  
  return( bw_follow( $atts ));
}  
  
function bw_output( $field ) {
  $fieldvalue = bw_get_option( $field );
  span( $field );
  e( $fieldvalue );
  epan();
  return( bw_ret() );
}

function bw_company() {
  return( bw_output( "company" ));
} 

function bw_business() {
  return( bw_output( "business" ));
} 

function bw_formal() {
  return( bw_output( "formal" ));
} 

function bw_slogan() {
  return( bw_output( "main-slogan" ));
}
 
function bw_alt_slogan() {
  return( bw_output( "alt-slogan" ));
}
 
function bw_admin() {
  return( bw_output( "admin" ));
}
 
function bw_domain() {
  return( bw_output( "domain" ));
} 


/**
 * Create a div to clear the floats
 * class cleared is used for Artisteer themes
 * class clear is the simpler version in oik
 */
function bw_clear() {
  sediv( "cleared clear" );
  return( bw_ret());
}  


/**
 * Skype Online Material: the Skype buttons and widgets available for download on the 
 * Skype Website at http://www.skype.com/share/buttons/ 
 * as such may be changed from time to time by Skype in its sole discretion.  
*/
function bw_skype( $atts ) {
  $atts['index'] = bw_array_get( $atts, "index", "skype" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Skype name" );
  $atts['class'] = bw_array_get( $atts, "class", "skype" );
  return( _bw_telephone( $atts ) );
}  

/**
 * Inline telephone number, using span
*/ 
function bw_tel( $atts=null ) {
  $atts['tag'] = 'span';
  return _bw_telephone( $atts );
}

/**
 * Inline mobile number, using span
*/ 
function bw_mob( $atts=null ) {
  $atts['tag'] = 'span';
  $atts['index'] = bw_array_get( $atts, "index", "mobile" );
  $atts['prefix'] = bw_array_get_dcb( $atts, "prefix", "Mobile" );
  return _bw_telephone( $atts );
}


/**
 * Generate a button to get directions from Google Maps e.g.
 * http://maps.google.co.uk/maps?f=d&hl=en&daddr=50.887856,-0.965113
 *
 */
function bw_directions( $atts=null ) {
  $alt = bw_array_get( $atts, "alt", null );
  
  $lat = bw_get_option( "lat", "bw_options$alt" );
  $long = bw_get_option( "long", "bw_options$alt" );
  $company = bw_get_option( "company" );
  $extended = bw_get_option( "extended-address", "bw_options$alt" );
  $postcode = bw_get_option( "postal-code", "bw_options$alt" );
  $link = "http://maps.google.co.uk/maps?f=d&hl=en&daddr=" . $lat . "," . $long;  
  $text = "Google directions";
  $title = "Get directions to " . $company;
  if ( $extended && ($company <> $extended) )
    $title .= " - " . $extended;
  if ( $postcode )
    $title .= " - " . $postcode;
  $class = NULL;
  art_button( $link, $text, $title, $class ); 
  return( bw_ret());
}


function bw_google_plus( $atts ) { 
  $atts['network'] = "GooglePlus";  
  return( bw_follow( $atts ));
}

/**
 * Only produce a follow me button if there is a value for the selected social network
*/ 
function bw_follow_e( $atts ) {
  $social_network = $atts['network'];
  $text = bw_array_get( $atts, 'text', NULL );
  
  $lc_social = strtolower( $social_network );
  $social = bw_get_option( $lc_social );
  if ( $social ) {
    bw_trace( $text, __FUNCTION__, __LINE__, __FILE__ , "text" );
    $imagefile = plugins_url( 'images/'. $lc_social . '_48.png', __FILE__ );
    
    $image = retimage( NULL, $imagefile, $social_network );
    alink( NULL, $social, $image, "Follow me on ".$social_network );
  }     
} 

/**
 * Produce a Follow me button for each of these social networks:  Twitter, Facebook, LinkedIn, GooglePlus, YouTube, Flickr
*/
function bw_follow_me( $atts = NULL ) {
  $networks = array( 'Twitter', 'Facebook', 'LinkedIn', 'GooglePlus', 'YouTube', 'Flickr' );
  foreach ( $networks as $network ) {
    $atts['network'] = $network;
    bw_follow_e( $atts );
  }
  return( bw_ret());
}

/** 
 * bw_oik() is needed here since it's used in the oik admin pages
 *   
 */
function bw_oik ( $class = NULL ) {
  $bw = nullretstag( "span", $class ); 
  $bw .= '<span class="b1">o</span>';
  $bw .= '<span class="o">i</span>';
  $bw .= '<span class="b2">k</span>';
  $bw .= nullretetag( "span", $class ); 
  return( $bw );
}

/** 
 * bw_oik_long - spells out Often Included Key-information
 *   
 */
function bw_oik_long( $class = NULL ) {
  $bw = nullretstag( "span", $class ); 
  $bw .= '<span class="B1">O</span>';
  $bw .= '<span class="b1">ften </span>';
  $bw .= '<span class="o">Included </span>';
  $bw .= '<span class="b2">Key-information</span>';
  $bw .= nullretetag( "span", $class ); 
  return( $bw );
}



/**
 * Display the QR code file with a link if required
 * Notes: the attribute defaulting needs to be improved 
*/ 
function bw_qrcode( $atts ) {
  $link = bw_array_get( $atts, 'link', null );
  $text = bw_array_get_dcb( $atts, 'text', 'company', 'bw_get_option' );
  $width = bw_array_get( $atts, 'width', null );
  $height = bw_array_get( $atts, 'height', null );


  $upload_dir = wp_upload_dir();
  $baseurl = $upload_dir['baseurl'];
  
  $logo_image = bw_get_option( "qrcode-image" );
  $company = bw_get_option( "company" );
  $image_url = $baseurl . $logo_image;
  
  $image = retimage( NULL, $image_url, "QR code for " . $text , $width, $height );
  if ( $link ) {
    alink( NULL, $link, $image, $company );
  }  
  else {
    e( $image );  
  }  
  return( bw_ret());
    
}

/**
 * Start a div tag
 * Use in conjunction with ediv to add custom divs in pages, posts or blocks
 * e.g. [div class="blah"]blah goes here[ediv]
 */
function bw_sdiv( $atts ) {
  $class = bw_array_get( $atts, 'class', null );
  $id = bw_array_get( $atts, 'id', null );
  sdiv( $class, $id );
  return( bw_ret());
}

/**
 * End a div tag 
 */ 
function bw_ediv( $atts ) {
  ediv();
  return( bw_ret());
}

/**  
 * Create an empty div for a particular set of classes, id or both
 * e.g. [sediv class="bd-100"] 
 */
function bw_sediv( $atts ) {
  $class = $atts['class'];
  $id = $atts['id'];
  sediv( $class, $id );
  return( bw_ret());
}

 
/** 
 * Display the company abbreviation using an abbr tag
 */
function bw_abbr( $atts = NULL ) {
  $abbr = bw_get_option( "abbr" );
  $company = bw_get_option( "company" );
  abbr( $company, $abbr );
  return( bw_ret());
}
  
/** 
 * Display the company abbreviation using an acronym tag
 * there is a subtle difference between the two: abbr and acronym
 * see (for example) http://www.benmeadowcroft.com/webdev/articles/abbr-vs-acronym/   
 */
function bw_acronym( $atts = NULL ) {
  $abbr = bw_get_option( "abbr" );
  $company = bw_get_option( "company" );
  acronym( $company, $abbr );
  return( bw_ret());
}


/**
 * Set a default value for an empty attribute value from the oik options or a hardcoded value
 * @param string $bw_value - value passed... if not set then
 * @param string $bw_field - get the oik option value - this is the field name of the oik option - e.g. 'company'
 * @param string $bw_default - the (hardcoded) default value if the oik option is not set
 * @param string $set - options set from which the field should be returned 
 *
 * e.g. 
 * $width = bw_default_empty_att( $width, "width", "100%" );
 * 
*/
function bw_default_empty_att( $bw_value=NULL, $bw_field=NULL, $bw_default=NULL, $set="bw_options" ) {
  $val = $bw_value;
  if ( empty( $val )) {
    $val = bw_get_option( $bw_field, $set );
    if ( empty( $val ))
      $val = $bw_default;
  } 
  return( $val );
}




/** 
 * Return the array[index] or build the result by calling $callback, passing the $default as the arg.
 *
 * @param array $array array from which to obtain the value
 * @param string $index - index of value to obtain]
 * @param mixed $default - parameter to the $callback function 
 * @param string $callback - function name to invoke - defaults to invoking __()
 *
 * Notes: dcb = deferred callback
 * Use this function when the applying the default might take some time but would be unnecessary
 * if the $array[$index] is already set.
 * You can also use this function when the default value is a string that you want to be translated.
 */
function bw_array_get_dcb( $array = array(), $index, $default = NULL, $callback='__' ) {
  $value = bw_array_get( $array, $index, NULL );
  if ( $value == NULL ) {
    $value = $callback( $default ); 
  }  
  return( $value );  
}


/**
 * Set a default value for an empty array[ index]
 * @param bw_array - array containing the value
 * @param bw_index - index to check... if not set then 
 * @param bw_field - get the oik option value - this is the field name of the oik option - e.g. 'company'
 * @param bw_default - the (hardcoded) default value if the oik option is not set
 *
 * e.g. 
 * $width = bw_default_empty_arr( $atts, "width", "width", "100%" );
 * 
*/
function bw_default_empty_arr( $bw_array=NULL, $bw_index=NULL, $bw_field=NULL, $bw_default=NULL ) {
  $val = bw_array_get( $bw_array, $bw_index, NULL );
  if ( empty( $val )) {
    bw_trace( $bw_field, __FUNCTION__, __LINE__, __FILE__, "bw_field" );
    $val = bw_get_option( $bw_field );
    bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value" );
    if ( empty( $val ))
      $val = $bw_default;
  } 
  bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value" );
  return( $val );
}


/**
 * return a nice SEO title
 * taking into account which plugins are being used
 */
if (!function_exists( 'bw_wp_title' )) {
  function bw_wp_title() {
    if ( class_exists( 'WPSEO_Frontend' )) {
      $title = wp_title('', false );
    }
    else {
      $title = wp_title( '|', false, 'right' ); 
      $title .= get_bloginfo( 'name' );
    }
    return $title;
  }
}



/** 
 * Display a blockquote unaffected by wpautop
 */
function bw_blockquote( $atts = NULL ) {
  $text = bw_array_get( $atts, "text", NULL ); 
  $class = bw_array_get( $atts, "class", NULL );
  blockquote( $text, $class );
  return( bw_ret());
}

/**
 * Display a <cite> unaffected by wpautop 
 */
 
function bw_cite( $atts = NULL ) {
  $text = bw_array_get( $atts, "text", NULL ); 
  $class = bw_array_get( $atts, "class", NULL );
  cite( $text, $class );
  return( bw_ret());
}

/**
 *  Format a range of years
 *
 *  $yearfrom $yearto result
 *  x         x       x
 *  x         y       x,y
 *  x         z       x-z
 *
 *  where: z >= (y + 1) = (x + 1)
 *  if $atts['sep'] is set we use that
 */
function bw_year_range( $yearfrom, $yearto, $atts=NULL ) {
  if ( !$yearfrom ) {
    $yearfrom = $yearto;
  }  
  $diff = $yearto - $yearfrom;
      
  switch ( $diff ) {
    case 0:
      $years = $yearfrom;
      break;
    case 1:
      $sep = bw_array_get( $atts, "sep", "," );
      $years = "$yearfrom$sep$yearto";
      break;
    default: /* more than one OR negative - which is a bit of a boo boo */
      $sep = bw_array_get( $atts, "sep", "-" );
      $years = "$yearfrom$sep$yearto";
  }
  return( $years );    
} 

/**
 * Determine the date of the blog from the date of the earliest registered user
  
  (
    [0] => stdClass Object
        (
            [ID] => 1
            [user_login] => admin
            [user_pass] => $P$BijsY7/BdZ9AzR8YdJwYVVt68FBovk0
            [user_nicename] => admin
            [user_email] => info@bobbingwide.com
            [user_url] => 
            [user_registered] => 2010-12-23 12:22:39
            [user_activation_key] => qLc3INyEWwBOsfFDnZeV
            [user_status] => 0
            [display_name] => admin
        )

)
 */
function bw_get_yearfrom() {
  $users = bw_get_users();
  bw_trace2( $users );
  $yearfrom = bw_format_date( $users[0]->user_registered, 'Y' );

  return $yearfrom;
}


/** 
 * Simple wrapper to get_users
 
				'blog_id' => $GLOBALS['blog_id'],
				'role' => '',
				'meta_key' => '',
				'meta_value' => '',
				'meta_compare' => '',
				'include' => array(),
				'exclude' => array(),
				'search' => '',
				'orderby' => 'login',
				'order' => 'ASC',
				'offset' => '',
				'number' => '',
				'count_total' => true,
				'fields' => 'all',
				'who' => ''
    
 *
 */
function bw_get_users( $atts = NULL ) {
  $atts['orderby'] = bw_array_get( $atts, "orderby", "registered" );
  $atts['number'] = bw_array_get( $atts, "number", 1 );

  $users = get_users( $atts );
  return( $users );

}

/** 
 * Display the copyright statement for the company
 * showing start and end years
 * e.g. (C) Copyright [bw_company] [bw_from]&sep[year] 
 * where [bw_from] is the first year of the site
 * &sep is the separator ( defaults to ',' for one year apart and '-' for a range 
 * [year] represents the current year
 *
 */ 
function bw_copyright( $atts = NULL ) {
  $copyright = bw_array_get_dcb( $atts, "prefix", "&copy; Copyright" );
  $company = bw_array_get_dcb( $atts, "company", "company", "bw_get_option" );
  $suffix = bw_array_get_dcb( $atts, "suffix", ". All rights reserved." );
  $yearto = bw_format_date( null, 'Y');
  // bw_trace( $yearto, __FUNCTION__, __LINE__, __FILE__, "yearto!" );
  $yearfrom = bw_array_get_dcb( $atts, "from", "yearfrom", "bw_get_option" );
  
  $years = bw_year_range( $yearfrom, $yearto, $atts );
  span( "copyright" );
  e( "$copyright " );
  e( bw_do_shortcode( "$company ") );
  e( "$years" );
  e( "$suffix" );
  epan();
  return( bw_ret());
}

function bw_stag( $atts = NULL ) {
  $tag = bw_array_get( $atts, "name", NULL );
  $class = bw_array_get( $atts, "class", NULL );
  $id = bw_array_get( $atts, "id", NULL );
  stag( $tag, $class, $id );
  return( bw_ret());
}

function bw_etag( $atts = NULL ) {
  $tag = bw_array_get( $atts, "name", NULL );
  etag( $tag );
  return( bw_ret());
}

