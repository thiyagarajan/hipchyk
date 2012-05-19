<?php // Copyright Bobbing Wide 2009-2012
// bobbfunc.inc

function bw_oik_version() {  
  return( '1.13' );
}

require_once( "oik_boot.inc" );   /* Don't use oik_require here */

/**
 * bw API partial equivalent of PHP's output buffering
 * 
 * Note: this isn't really an output buffer
 * Use bw_push() and bw_pop() if you need to stack buffers during nested processing.
 * $bwecho is used by the bw APIs to create the HTML output that is then returned from the shortcode functions  
 * 
*/
function bw_echo( $string ) {
  global $bwecho;
  $bwecho.=$string;
}

/**
 * Use bw_flush() to echo the contents of $bwecho then empty it
 * 
 * You may need to use this before calling a WordPress function that echoes its output 
 * directly rather than through a buffer. e.g. before calling settings_fields()
 */
function bw_flush() {
  global $bwecho;
  echo $bwecho;
  //bw_trace( "inside bw_flush" );
  //bw_trace( $bwecho );
  //bw_trace( "after" );
  $bwecho = NULL;
}

/**
 * Use bw_ret() to return the contents of $bwecho, leaving the global value as NULL
 */
function bw_ret() {
  global $bwecho;
  $bwret = $bwecho;
  $bwecho = NULL;
  //bw_trace( __FUNCTION__ );
  return( $bwret );
}

/**
 * Push then empty the current $bwecho buffer
*/ 
function bw_push() {
 global $bwecho, $bwecho_array;
 $bwecho_array[] = $bwecho;
 $bwecho = null;
}

/** 
 * Restore the previous $bwecho buffer
*/
function bw_pop() {
  global $bwecho, $bwecho_array;
  $bwecho = array_pop( $bwecho_array ); 
}   

/**
 * Perform nested shortcode expansion
 *
 * @param string $content - the content to be expanded
 * @return string the content after shortcode expansion
 *
 * Performance question - is there any point testing for a '[' in the string? i.e. How expensive is do_shortcode() ? 
 */
function bw_do_shortcode( $content ) {
  bw_push(); 
  $content = do_shortcode( $content );
  bw_pop(); 
  return( $content );
}



/**
 * Return an image tag
 * 
 * @param string $class  CSS classes for this image
 * @param string $jpg file name of the image (src=)
 * @param string $title value for the title= and alt= attributes
 * @param string $width width of the image
 * @param string $height height of the image
 * @return string HTML of the img tag
 
 * Note: This function does not handle an id= attribute
 */
function retimage( $class, $jpg, $title=NULL, $width=NULL, $height=NULL ) {
  $img = '<img class="' .  $class . '" ';  
  $img .= 'src="' .  bw_expand_link( $jpg ) . '" ';
  if ( !is_null( $width))
    $img .= 'width="' . $width . '" ';
  if ( !is_null( $height))
    $img .= 'height="' . $height . '" ';
  if ( !is_null( $title ) ) {
    $title = strip_tags( $title );
    $img .= 'title="' . $title . '" ';
    $img .= 'alt="' . $title . '" ';
  }  
  $img .= " />";
  return( $img );
}

 
function image( $class, $jpg, $title=NULL, $width=NULL, $height=NULL ) {
  $img = retimage( $class, $jpg, $title, $width, $height ); 
  bw_echo( $img );
}


function kv( $keyword, $value=null ) {
  if ( $value != null ) {
    $kv = ' '.$keyword . '="' . $value .'"';
  } else {
    $kv = '';
  }  
  return( $kv );  
} 



function atitle( $title = NULL ) {
  $title = wp_strip_all_tags( $title );
  return( kv( "title", $title ) );
} 


/**
 * Create an anchor tag for linking within a page
 */ 
function aname( $name ) { 
  stag( 'a' );
  e( ' name="'. $name . '"' );
  etag( 'a' );
  // return( bw_ret() );
} 



/**
 * Create a link
 * 
 * @param string $class - the classes for the anchor tag
 * @param string $url - the fully formed URL e.g. http://www.oik-plugins.com
 * @param string $linktori - is the text or image
 * @param string $alt - if NULL will use $linktori
 * @param string $id - the unique ID for the anchor tag
 * @param string $extra - anything else that needs to go in the <a> tag. e.g. 'onclick=then some javascript' 
 *
 * @uses retlink()
 * 
*/   

function alink( $class=NULL, $url, $linktori=NULL, $alt=NULL, $id=NULL, $extra=NULL ) {
  $link = retlink( $class, $url, $linktori, $alt, $id, $extra );
  e( $link ); 
}


/**
 * Return a well formed link
 *
 * Parameters as for alink()
*/
function retlink( $class=NULL, $url, $linktori=NULL, $alt=NULL, $id=NULL, $extra=NULL  ) {
  if ( is_null( $linktori ) )
    $linktori = $url;
  $link = "<a" ;
  $link .= kv( "class", $class ); // aclass( $class );
  $link .= kv( "id", $id ); // aid( $id );
  $link .= kv( "href", $url ); // ahref( $url );
  if ( is_null( $alt ) )
     $alt = $linktori;
  // Is alt= allowed with XHTML Strict 1.0?    
  // aalt( $alt );
  $link .= atitle( $alt );
  if ( $extra )
    $link .= $extra ;
  $link .= ">";
  if ( $linktori )
    $link .= $linktori;
  $link .= "</a>";
  return( $link );
}  
 


function retstag( $tag, $class=NULL, $id=NULL, $extra=NULL ) {   
  $stag = '<' . $tag ;
  if ( $class <> NULL )
     $stag.= ' class="' . $class. '"';
  if ( $id <> NULL )
     $stag.= ' id="' . $id. '"';
  if ( $extra <> NULL )
    $stag .= ' '. $extra;   
  $stag.= '>';
  return( $stag );
}


function nullretstag( $tag, $class=NULL ) {
   $ret = '';
   if ( $class <> NULL )
      $ret = retstag( $tag, $class );
   return( $ret );     
} 


function stag( $tag, $class=NULL, $id=NULL, $extra=NULL ) {
  bw_echo( retstag( $tag, $class, $id, $extra ));
}

function sol( $class=NULL, $id=NULL ) {
   stag( "ol", $class, $id );
}        


/* renamed uls to sul to be consistent with stag */ 
   
function sul( $class=NULL, $id=NULL ) {
   stag( "ul", $class, $id );
}        

function sdiv( $class=NULL, $id=NULL, $extra=NULL ) {
   stag( "div", $class, $id, $extra );
}

function eol() {
   bw_echo( '</ol>' );
}
   

function eul() {
   bw_echo( '</ul>' );
}

function ediv() {
   bw_echo( '</div>' );
}

/* sediv creates a dummy div which is used for placing graphics using backhground images in CSS */
function sediv( $class=NULL, $id=NULL, $extra=NULL ) {
   sdiv( $class, $id, $extra );
   ediv();
}   

function ep() {
  bw_echo( '</p>' );
}

function nullretetag( $tag, $class=NULL ) {
  $ret = '';
  if ( $class <> NULL )
     $ret = retetag( $tag );
  return( $ret );   
}

function retetag( $tag ) {
   return( '</'.$tag.'>');
}  

function etag( $tag ) {
  //  bw_echo( '</'.$tag.'>'."\n";
  bw_echo( '</'.$tag.'>' );
}    

function sp( $class=NULL, $id=NULL ) {
   stag( "p", $class, $id );
} 

function p( $text=NULL, $class=NULL, $id=NULL ) {
   sp( $class, $id );
   if ( !is_null( $text ))
     bwt( $text );
   etag( "p" );
}

function hn( $text, $level, $class, $id ) {
   stag( "h".$level, $class, $id );
   bwt( $text );
   etag( "h".$level );
}

function h1( $text, $class=NULL, $id=NULL ) {
   hn( $text, "1", $class, $id ); 
}

function h2( $text, $class=NULL, $id=NULL ) {
   hn( $text, "2", $class, $id );  
}

function h3( $text, $class=NULL, $id=NULL ) {
   hn( $text, "3", $class, $id ); 
}

function h4( $text, $class=NULL, $id=NULL ) {
   hn( $text, "4", $class, $id ); 
}

function h5( $text, $class=NULL, $id=NULL ) {
   hn( $text, "5", $class, $id ); 
}

function h6( $text, $class=NULL, $id=NULL ) {
   hn( $text, "6", $class, $id ); 
}


/** Function e() replaces the original t() function used in Bobbing Wide custom code
 *   since for Drupal t() is already defined for translatable text
 *  Function bwt() does a similar job but also performs some strange filtering if required.
 */  
function bwt( $text = NULL ) {
  global $bbboing;  

  if ( !is_null( $text ))
  {
    if ( $bbboing )
       bw_echo( $bbboing( $text ));
    else
      bw_echo( $text ) ;
  }    
}

/**
 * @func e for bw_echo( if not NULL
 */
function e( $text = NULL ) {
  if ( !is_null( $text ))
    bw_echo( $text );
}

function br( $text=NULL ) {
   bw_echo( '<br />' );
   bwt( $text ); 
}   

function hr() {
  bw_echo( '<hr />' );
}  
  

// Instead of function li( use function lit( - List Item Text

function lit( $text, $class=NULL, $id=NULL ) {
  stag( "li", $class, $id );
  bwt( $text );
  etag( "li" );
}

/* decided to write a helper function */
function li( $text ) {
   lit( $text );   
} 

// Note: here we omit the 's' of span to make it easier to type
function span( $class=NULL, $id=NULL ) {
  stag( "span", $class, $id );
}

function epan() {
  etag( "span" );
}  

function sepan( $class=NULL, $text=NULL ) {
   span( $class );
   e( $text );
   epan();
}


/* table data */

function td( $data, $class=NULL, $id=NULL ) {
  stag( "td", $class, $id );
  e( $data );
  etag( "td" );
} 

/* table heading */
function th( $data, $class=NULL, $id=NULL ) {
  stag( "th", $class, $id );
  e( $data );
  etag( "th" );
}         
        
  




// This routine finds the subdirectory under which this local version of the website is installed
// we need to remove this from index lookups but add it to links! 
// Example: on betterbfar in the twentyte directory it's set:
// $docroot_suffix = "/twentyte/" ;

function bw_get_docroot_suffix() {
  bw_backtrace();
  //gobang();
  $docroot_suffix = "/";
  if ( $_SERVER['SERVER_NAME'] == bw_get_option( "betterbyfar") )
  {
     $exdr = explode( '/', $_SERVER["DOCUMENT_ROOT"] );
     $exsf = explode( '/', $_SERVER['SCRIPT_FILENAME'] );
     
     $docroot_suffix = '/' . $exsf[ count( $exdr) ] . '/';
     
     // bw_debug( "_SERVER[DOCUMENT_ROOT]: " . $_SERVER["DOCUMENT_ROOT"] );
     // bw_debug( "_SERVER[REQUEST_URI]: " .  $_SERVER['REQUEST_URI'] );  
     // bw_debug( "_SERVER[SCRIPT_FILENAME]: " . $_SERVER['SCRIPT_FILENAME'] );
  
     // bw_debug( "docroot_suffix: " . $docroot_suffix );
  }
  return( $docroot_suffix );
}

/* This gets us to the right place when the link is from a sub-directory
   but it doesn't add anthing when the link is of form
     http:
     https:
     ftp:
     mailto: 
*/     
function bw_expand_link( $linkurl ) {
   if ( strpos( $linkurl, ':' ) == 0  )
      $linkurl = bw_get_docroot_suffix() . $linkurl;
   return( $linkurl) ;   
}


function bw_prefix_require( $file ) {
  global $bobb_prefix;
  $file = $bobb_prefix . $file ;
  require( $file );
}


function strong( $text, $class=NULL, $id=NULL ) {
   stag( "strong", $class, $id ) ;
   e( $text );
   etag( "strong" );    
}

function em( $text, $class=NULL, $id=NULL ) {
   stag( "em", $class, $id ) ;
   e( $text );
   etag( "em" );    
}

function blockquote( $text, $class=NULL, $id=NULL ) {
   stag( "blockquote", $class, $id ) ;
   e( $text );
   etag( "blockquote" );    
}

function quote( $text, $class=NULL, $id=NULL ) {
   stag( "quote", $class, $id ) ;
   e( $text );
   etag( "quote" );    
}

function cite( $text, $class=NULL, $id=NULL ) {
   stag( "cite", $class, $id ) ;
   e( $text );
   etag( "cite" );    
}

/** 
 * Create an <abbr> tag 
 */
function abbr( $title="Often Included Key-information wide", $abbrev="oik" ) {
  if ( $abbrev ) {
    $abbr = '<abbr title="';
    $abbr .= $title;
    $abbr .= '">';
    $abbr .= $abbrev; 
    $abbr .= '</abbr>';
    e( $abbr );
  }  
}
 
/** 
 * Create an <acronym> tag 
 * Note: acronym becomes obsolete in HTML5
 */
function acronym( $title="Often Included Key-information wide", $acronym="oik" ) {
  if ( $acronym ) {
    $acro = '<acronym title="';
    $acro .= $title;
    $acro .= '">';
    $acro .= $acronym; 
    $acro .= '</acronym>';
    e( $acro );
  }  
} 


  
function c( $text ) {
  bw_echo( '<!--' . $text . '-->' );
} 

function bw_debug_on() {
  global $bw_debug_on;
  $bw_debug_on = TRUE;
}

function bw_debug_off() {
  global $bw_debug_on;
  $bw_debug_on = FALSE;
}     
     

function bw_debug( $text ) {
  global $oktop, $bw_debug_on;
  if ($bw_debug_on)
  {
    if ( $oktop )
      p( $bw_debug_on . $text );
    else
      c( $text ); 
  }     
}


/* Function: bw_image_link
   Purpose: To display an image as a link 
*/   
function bw_image_link( $link 
                      , $image
                      , $imagetext
                      , $width=302
                      , $height=227
                      ) {
  $linktext = bw_get_linktext( $link );
  $a = '<a title="';
  $a.= $linktext;
  $a.= '" href="';
  $a.= bw_expand_link( $link );
  $a.= '">';
  bw_echo( $a . "\n" );
  
  image( ""
       , $image
       , $imagetext
       , $width
       , $height
       );
  etag( "a" );
 
} 


function bw( $bw_class_prefix=NULL ) {  

      if ( is_null( $bw_class_prefix )) 
         $cp = ' class="';
      else
         $cp = ' class="' . $bw_class_prefix;
           
      $bw  = '<em' .$cp .'b1">B</em>';
      $bw .= '<em' .$cp .'o">o</em>'; 
      $bw .= '<em' .$cp .'b2">b</em>';
      $bw .= '<em' .$cp .'b3">b</em>';
      $bw .= '<em' .$cp .'i1">i</em>';
      $bw .= '<em' .$cp .'n">n</em>';
      $bw .= '<em' .$cp .'g">g</em>';
      $bw .= '<em' .$cp .'space">&nbsp;</em>';
      $bw .= '<em' .$cp .'W">W</em>';
      $bw .= '<em' .$cp .'i2">i</em>';
      $bw .= '<em' .$cp .'d">d</em>';
      $bw .= '<em' .$cp .'e">e</em>';
      
         
      return $bw;
}
  
  

  
//function ebw( $bw_class_prefix=NULL ) {
//     bw_echo( bw( $bw_class_prefix ) );
//}

/** 
 * echo Bobbing Wide inside a theme's template file
 */
function ebw( $bw_class_prefix=NULL )
{
   echo bw( $bw_class_prefix );
}

require_once( "bobbcomp.inc" );

if ( bw_is_wordpress() ) {
  /**
    * In WordPress Artisteer provides code to deal with buttons
    * but in Drupal it needs more wrapping.
    */
  function art_button( $linkurl, $text, $title=NULL, $class=NULL  ) {
    alink( "button " . $class , $linkurl, $text, $title ) ;
  }
}
else {
  function art_button( $linkurl, $text, $title=NULL, $class=NULL  ) {
    span("art-button-wrapper" );
    sepan("art-button-l l");
    sepan("art-button-r r");
    alink( "button art-button " . $class , $linkurl, $text, $title ) ;
    epan();
  }
}



/*  default logic
  set a default value if the value is not set
*/
function bw_default( $value, $default=NULL ) {
  $val = $value; 
  bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value before" );
  if ( empty( $val ))
    $val = $default;
  bw_trace( $val, __FUNCTION__, __LINE__, __FILE__, "value returned" );
  return( $val );
}    
  
/* 
  Gallery logic
  
  The gallery shortcode allows media to be displayed
  Here we take values from the page's metadata
   gallery-columns = 1,2, etc
   gallery-size = 'thumbnail', 'medium', 'large', 'full'
  and apply them to the gallery shortcode
  If the meta data is not set in the post the default values are:
  
  
  [gallery columns="1" size="medium"] 
  
  For more information on gallery see http://codex.wordpress.org/Gallery_Shortcode
  For more information on sizes see WordPress admin panel under Settings > Media
  
*/
function bw_gallery() {
  c( "gallery processing");
  global $post;
  bw_trace( $post, __FUNCTION__, __LINE__, __FILE__, "post" );
 
  $columns = get_post_meta( $post->ID, "gallery-columns", true );
  $columns = bw_default( $columns, "1");
  $size = get_post_meta( $post->ID, "gallery-size", true );
  $size = bw_default( $size, "medium" );
   
  $gallery_options = array( 'columns' => $columns, 'size' => $size );
  e( gallery_shortcode( $gallery_options ));
}

/**
 * Format a date with the specified format
 *
 * @param string $date - either a number - representing the UNIX date or a recognisable date string or NULL - for the current date
 *  e.g. 1293840000 => 2011-01-01
 *       2011-12-31 => 2011-12-31
 * @param string $format - date formatting string
 * @returns string $date - the date formatted according to the $format string 
 *
 * A value of NULL will be returned as NULL
 *
 * strtotime() works for dates NOT in format yyyy-mm-dd e.g. '5-10-1955' or '30 Jul 1958'
 * Note: Dates before 1970-01-01 are stored as negative values.
 *
 */
function bw_format_date( $date=NULL, $format="Y-m-d" ) {
  if ( $date != NULL ) {
    $date = trim( $date );
    if ( !is_numeric( $date ) ) {
      $date = strtotime( $date );
    } 
    $date = date( $format, $date );
    // bw_trace2( $date, "date" );
  } else {
    $date = date( $format );
  }  
  return( $date );
}

/** Simple function to validate a field as TRUE or FALSE given a big list of strings that represent TRUE 
    Note: No need for the $falses parameter at present
 */
function bw_validate_torf( $field, $trues=",true,yes,1", $falses=NULL ) {
  $torf = FALSE;
  $field = ",".strtolower( $field );
  bw_trace( $field, __FUNCTION__, __LINE__, __FILE__, "field" );
  bw_trace( $trues, __FUNCTION__, __LINE__, __FILE__, "trues" );
  $pos = strpos( $trues, $field );
  bw_trace( $pos, __FUNCTION__, __LINE__, __FILE__, "pos" );
  if ( $pos )
    $torf = TRUE;
  return( $torf );
}

/** 
 * Return what might be the plugin name with hyphens and lowercase chars
 * 
 * Strip any tags - the WordPress UI likes adding <br />'s where we don't want them
 */
function bw_plugin_namify( $name ) {
  $name = trim( $name );
  $name = str_replace( ' ', '-', $name );
  $name = str_replace( '_', '-', $name );
  $name = strtolower( $name );
  return( bw_trace2( $name ) ); 
}
  
/**
 * Return the function name of the function to invoke built from parms
 *
 * @param string $prefix - the function name prefix e.g. "_bw_create_content"
 * @param string $key - the multi-word key e.g. About oik-plugins"
 * @return string $funcname - the function name to invoke
 * 
 * Example: Return the function name to create content for an about us page
 * $funcname = bw_funcname( "_bw_create_content", "About oik-plugins" );
 * 
 * Would return the most detailed function that exists
 *  _bw_create_content
 *  _bw_create_content_about
 *  _bw_create_content_about_oik
 *  _bw_create_content_about_oik_plugins
 *
 * Note: In the original version of this code it happily produced
 * _sc_help_caption
 * for wp_caption
 * So check what happens in the setup plugin **?**
*/
function bw_funcname( $prefix, $key, $value=NULL ) {
  $funcname = $prefix; 
  $testname = $funcname;
  $keys = explode( "-", bw_plugin_namify( $key ));
  foreach ( $keys as $keyword ) {
    $testname .= '_'.$keyword;
    if ( function_exists( $testname ) ) {
      $funcname = $testname;
    }  
  }
  return( $funcname );
} 

/**
 * Return the URL for a resource file
 * 
 * @uses plugin_dir_url() to find the plugin base directory then appends the plugin folder and file name
*/
function oik_url( $file = null, $plugin='oik' ) {
  $url = plugin_dir_url( null );
  $url .= "$plugin/$file" ;
  return( $url ); 
}

/**
 * Need to find out more about other shortcodes and 
 * then find a way of capturing the registration information for a shortcode
 * it should be possible to find the plugin that implements the shortcode
 * but we might not know the location of the function that we can call 
 * if the shortcode is a lazy shortcode.
 */
function bw_sc_help( $shortcode="oik" ) {
  oik_require( "includes/oik-sc-help.inc" );
  bw_lazy_sc_help( $shortcode );
}

function bw_sc_syntax( $shortcode="oik" ) {
  oik_require( "includes/oik-sc-help.inc" );
  bw_lazy_sc_syntax( $shortcode );
}

function bw_sc_example( $shortcode="oik", $atts=null ) {
  oik_require( "includes/oik-sc-help.inc" );
  bw_lazy_sc_example( $shortcode, $atts );
}

function bw_sc_snippet( $shortcode="oik" ) {
  oik_require( "includes/oik-sc-help.inc" );
  bw_lazy_sc_snippet( $shortcode );
}

/**
  * Dynamic jQuery setting the selector, function and option parameters
  * When should we use?
  * 
  * jQuery(window).load(function() - when you need to wait for images to load?
  * jQuery(function()
  * jQuery(document).ready(function()
  *
*/  
if ( !function_exists( "bw_jquery" ) ) {
function bw_jquery( $selector, $method, $parms=null, $windowload=false ) {
  stag( "script type=\"text/javascript\"" );
  if ( $windowload ) {
    $jqfn = "jQuery(window).load(function()";
  } else {
    $jqfn = "jQuery(document).ready(function()"; 
  }    
  $function = "$jqfn { jQuery( \"$selector\" ).$method( $parms ); });";
  e( $function );
  etag( "script" );
} 
}

/**
 * Format an array of parms for jQuery 
 * 
 * If it's not an array we treat it as an already formatted string
 * OR a single parameter and value
 *
*/
if ( !function_exists( "bw_jkv" ) ) {

function bw_jkv( $parms, $value=null ) {
  if ( is_array( $parms ) ) {
    bw_jtorf( $parms );
    $jqoption = json_encode( $parms );
  } else {
    $jqoption = "{";
    if ( $value ) { 
      $jqoption .= "$parms : $value";
    } else {
      $jqoption .= $parms;
    }  
    $jqoption .= "}";
  }
  return( $jqoption );
}
}

/**
 * Pre-processing for json_encode to convert
 * "on" == "true" to 1 == true
 * "off" == "0" to 0 == false
 * torf = true or false
 * This will allow WordPress options which are checkboxes to be passed as jQuery boolean parameters
 * where the default is true so we need to pass false.
 * Used in conjunction with a hidden field for checkboxes.
 *
 * This code also removes any entries where the option value is blank...
 * allowing the jQuery code to use its hardcoded default.
 * 
*/
if ( !function_exists( "bw_jtorf" ) ) {

function bw_jtorf( &$parms ) {
  foreach ( $parms as $parm => $value ) {
    if ( $value == "on" ) {
      $parms[$parm] = 1;
    } elseif ( $value == "0" ) {
      $parms[$parm] = 0;
    } elseif ( $value == "" ) { 
      unset( $parms[$parm] );  
    }    
  }
  //bw_trace2();
}
}

/**
 * Convert a simple array into an associative array keyed on the value
 * @param array $array - a simple array - with at least one entry
 * @return array $assoc_array
*/ 
function bw_assoc( $array ) {
  $assoc_array = array();
  foreach ( $array as $key => $value  ) {
    $assoc_array[$value] = $value;
  }
  return( $assoc_array );
}    

/**
 *  Convert array of file names to array of urls
 *
 * @param array $files - array of file names 
 * @param array $atts - attributes for get_post
 * @return array $urls
 * 
 * We filter the results to take into account SOME of the possible parameters:
 * 
 * 'numberposts' - we accept ALL by default 
 *   'offset' - start from the first by default 
*/ 
function bw_file_to_url( $files, $atts=null ) {
  $offset = bw_array_get( $atts, 'offset', 0 );
  $numberposts = bw_array_get( $atts, "numberposts", count( $files ) );
  $urls = array();
  if ( count( $files ) ) {
    foreach( $files as $file ) {
      if ( ( $offset <= 0 ) && ( $numberposts > 0 ) ) {
        $urls[] = plugin_dir_url( $file ) . basename( $file );
      }  
      $offset--;
      $numberposts--;
    }
  }  
  bw_trace2( $urls );
  return( $urls );
}    

/** 
 * Return the URL that this image links to
 * @param int $postid - ID of the attachment
 * @return string $permalink URL for the link
 */
function bw_get_image_link( $postid ) {
  $permalink = get_post_meta( $postid, "_bw_image_link", true );    
  if ( !$permalink ) {
    $permalink = get_permalink( $postid );
  }
  return( $permalink );
}

/**
 * Recreate a WordPress option with the defined autoload value 
 * @param string $option name
 * @param string $autoload value: 'yes'|'no'. default='no' 
 * @return $options 
 * 
 * See 978-0-470-91622 p. 168 for the inspiration
 */
function bw_recreate_options( $option, $autoload="no" ) {
  $options = get_option( $option );   
  if ( $options !== FALSE ) {    
    delete_option( $option );
  }
  add_option( $option, $options, null, $autoload );
  bw_trace2( $options );
  return( $options );
}  
