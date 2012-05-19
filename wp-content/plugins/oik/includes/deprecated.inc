<?php // (C) Copyright Bobbing Wide 2012

/**
 * Deprecated variables
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use oik_require()
 * @see oik_require() 
 */
  $bobb_prefix = NULL;
  $bw_echo = NULL;
  
  $theme = "bobbingwide";     
  $art_theme = NULL;
  $bobb_theme = NULL;    



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use oik_require() 
 * @see oik_require()
 */
function bw_prefix_require( $file ) {
  _deprecated_function( __FUNCTION__, '0.11', 'wp_editor()' );
  global $bobb_prefix;
  $file = $bobb_prefix . $file ;
  require( $file );
}


/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use get_stylesheet_directory_uri().$file 
 * @see get_stylesheet_directory_uri()
 */
function bw_theme_image( $file ) {
  _deprecated_function( __FUNCTION__, '0.11', 'get_stylesheet_directory_uri()' );
  global $theme;
  $filename = bw_get_docroot_suffix();
  $filename.= "/themes/";
  $filename.= $theme;
  $filename.= "/";
  $filename.=$file;
  return( $filename) ;
} 



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use bw_get_option()
 * @see bw_get_option()
 */
  function bw_get_company( $field ) {
    _deprecated_function( __FUNCTION__, '0.11', 'bw_get_option()' );
    global $company;
    /* WordPress code */
    global $bw_options;
    /* Need to put some code here to see if it's been loaded */
    $bw_options = get_option( "bw_options" );
    
    // bw_trace( $bw_options, __FUNCTION__,  __LINE__, __FILE__, "bw_options" );  
    if ( $bw_options === FALSE )
      $option = $company[ $field ] ; 
    else
      $option = $bw_options[ $field ] ; 
      
    // Note: A value that appears to be blank ( == '') may actually be FALSE ( == FALSE )
    // bw_trace( '!' .$option.'!', __FUNCTION__,  __LINE__, __FILE__, "option" );  
    return( $option ); 
  }
  
/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use bw_set_option()
 * @see bw_set_option()
 */
  function bw_set_company( $field, $value=NULL ) {
    _deprecated_function( __FUNCTION__, '0.11', 'bw_set_option()' );
    global $bw_options;
    $bw_options = get_option( "bw_options" );
    $bw_options[ $field ] = $value;
    bw_trace2( $bw_options );
    update_option( "bw_options", $bw_options );
    return( $value );
  }  



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use bw_googlemap_v3()
 * @see bw_googlemap_v3()

*/
function bw_googlemap( $club, $lat, $lng, $postcode ) {
  _deprecated_function( __FUNCTION__, '0.11', 'bw_googlemap_v3()' );
  $latlng = $lat . ',' . $lng ;
     
  //echo '<div class="sidebar-right">';
  //echo '<h2 class="bar-green-med-light">' . $club . '</a></h2>';
  //echo '<p>Use this button for ';
  //echo '<a href="http://maps.google.co.uk/maps?f=d&hl=en&daddr=' . $latlng . '" title="Google directions">Google directions</a></p>';
  //echo '</div>';
     
     
     bw_echo( '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=');
     bw_echo( bw_get_company( "google_maps_api_key" ));
     
     // ABQIAAAAEraXBMl-kX5b-Swk0AR98BQOEsTKtT06IEwsylb7Uz31uE8TkRRL_5k4o5givS__FLcKk7M8WMou8Q"');
     bw_echo( '" type="text/javascript"></script>');
     bw_echo( "<script type=\"text/javascript\">\n");

     bw_echo( "//<![CDATA[\n");
     bw_echo( "function load() {\n");
     bw_echo( 'if (GBrowserIsCompatible()) {');
     bw_echo( 'var map = new GMap2(document.getElementById("map"));');
     bw_echo( 'var point = new GLatLng( ' .  $latlng . ');');
     bw_echo( 'map.setCenter( point , 12);');
     bw_echo( 'map.addOverlay( new GMarker( point ));');
     bw_echo( 'map.openInfoWindow( point, document.createTextNode("' . $club . ' ' .  $postcode . '" ));');
     //map.setMapType( G_HYBRID_MAP); 
     bw_echo( 'var mapControl = new GMapTypeControl();');
     bw_echo( 'map.addControl( mapControl);');
     bw_echo( 'map.addControl( new GLargeMapControl());');
     bw_echo( '}');
     bw_echo( '}');
     bw_echo( '//]]>');
     bw_echo( '</script>');
     bw_echo( '<body onload="load()" onunload="GUnload()">');
     bw_echo( '<div id="map" style="width:' .bw_get_company( "width" ). 'px; height:'.bw_get_company( "height" ).'px"></div>');
     bw_echo( '</body>');
}
 



/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use art_button
 * @see 
*/

function big_button( $class, $link, $text, $alt) {
   sdiv( "big_button_" .$class );
   sp();
   
   // createlink( $url, $text );
   $url = $link ; 
   
   sa();
   // No id parm 
   aclass();
   ahref( $url );
   //aalt( $alt );
   atitle( $alt );    
   ag();

   e( $text );  
   ea();
   ep(); 
   ediv();
   //ediv();
}


/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use art_button
 * @see 
*/
function button( $class, $link, $text, $alt) {
   
   // createlink( $url, $text );
   stag( "span",  "big_button_" .$class );
   $url = $link ; 
   
   sa();
   // No id parm 
   aclass();
   ahref( $url );
   atitle( $alt );    

   ag();     

   e( $text );  
   ea();
   etag( "span" );    
   stag( "span", "end_button_" . $class );
   //e( "&nbsp;*&nbsp;");
   etag( "span" );     
       

}


/**
 * @since 0.11
 * @deprecated 0.11
 * @deprecated Use art_button
 * @see 
*/

function sbutton( $class, $link, $text, $alt) {
   
   stag( "span",  "big_button_" .$class );
   // createlink( $url, $text );
   $url = $link ; 
   
   sa();
   // No id parm 
   aclass();
   ahref( $url );
   atitle( $alt );    

   ag();     

   e( $text );  
   ea();    
   etag( "span" );    
   stag( "span", "end_button_" . $class );
   e( "&nbsp;");
   etag( "span" );     

}


// *?** Need to create a link where I can add the prefix but the link text is nice

/*
function createlink( $file, $text ) {
 bw_echo( '<a href="' . $file . '">' . $text . '</a>' );
} 

function checkout( $url, $text) {
   createlink( $url, $text);
}

function checkoutid( $url, $text, $id) {
   bw_echo( '<a id="' .$id . '" href="' . $url . '" alt="' .$text . '" title="' .$text .'">' . $text . '</a>' );
}


function menulink( $file, $text ) {
  bw_echo( '<li>' );
  createlink( $file, $text );
  bw_echo( '</li>' );
}
*/




 

/**
 * This is a prototype function used to investigate what's necessary to make shortcode expansion "safe"
 
  An advanced shortcode processor needs to know the context in which 
   the shortcode is being expanded. There are times when we don't want to show the
   HTML since this may include information that would cause CSS to
   do some unexpected styling - so that needs to be stripped
   but there are other times when we do want this to happen
   This function is an experimental / exploratory function
   to find out what needs to be done, when, where and how.
*/   
function bw_clever( $atts, $hmm=NULL, $tag=NULL ) {
  /* The current_filter function lets us know why the shortcode is being expanded
     but we also need to know the purpose.
     
     e.g. the_title is used in a multitude of places
     when displayed on a post, page or widget we may want the text nicely formatted
     but in a page list we want plain text - with no shortcode expansion OR expanded but not styled
     How do we decide the best approach to this problem?
     
  */   
  $cf = current_filter();
  bw_trace( $cf, __FUNCTION__, __LINE__, __FILE__, "current_filter" );
 
  $admin = is_admin();
  // as you can see it's incomplete
  return( $tag );
}  

/** 
 * These are dummy functions to demonstrate my appalling understanding of php's OO implementation 
*/
function bw_nobbut() {
  return "";
}



function say( $text ) {
  isay ($text, "EN" );
}

function isay( $text, $lang=NULL ) {
  e( $text );  
} 


// Note: Any bw_debug's before the DOCTYPE and the page goes into quirks mode
// where's the earliest we can put these then? = perhaps in comments?
function bw_get_page() {
  $page = "";
  if ( !isset( $_GET['page'] ))
  {
     $page = $_SERVER['REQUEST_URI'];
     //bw_debug( "SERVER request uri: " . $page );
     if ( $page != $_SERVER['SCRIPT_NAME'] )
     {
        //bw_debug( "SCRIPT_NAME:" . $_SERVER['SCRIPT_NAME'] . " not the same as the REQUEST_URI:". $page . ", using SCRIPT_NAME" );
        $page = $_SERVER['SCRIPT_NAME'];  
     }   
  }   
  else
     $page = $_GET['page'];   
  //bw_debug( "bw_get_page: " . $page );   
  
  // Now we need to reduce this to the part of the page that we really want
  // on betterbyfar there may be an unwanted subdirectory
  // on real servers this subdirectory will not be present
  // how do we find out what the subdirectory is? 
  

  // bw_debug( "page before:" . $page ); 
  $docroot_suffix = bw_get_docroot_suffix();
  
  if ( $docroot_suffix <> '/' )
    $page = str_replace( $docroot_suffix, NULL, $page );
  else  
    $page = ltrim( $page, $docroot_suffix );   
  // bw_debug( "page after: " . $page );
   
  return $page;
}  
