<?php // (C) Copyright Bobbing Wide 2012 


/**
 * Format the shortcode syntax for returning in HTML
*/
function bw_form_sc_syntax( $syntax ) {
  if ( count( $syntax ) ) { 
    foreach ( $syntax as $key => $value ) {
      br();
      span( "key" );
      e( $key );
      epan();
      e( "=" );
      span( "value");
      e( '"' );     
      e( bw_format_skv( $value ));  
      e( '"' );
      epan();
    }  
  }
}

/**
 * Attempt to find the function that will handle the additional processing for the shortcode
 * 
 * @param string $shortcode - the name of the shortcode - NOT the shortcode function
 * @param string $suffix - the additional processing required: e.g. __help, __example, __syntax
 * @return string $funcname - the function that exists to handle this request
 * 
 * If the function does not exist we see if it's in the file that implements the shortcode
 * If it's not then we return the default function name
 * which is:
 * _sc__help: for shortcode help
 * _sc__example: for shortcode example
 * _sc_syntax: for shortcode syntax    
*/
function bw_load_shortcode_suffix( $shortcode, $suffix ) {
  $testfunc = $shortcode . $suffix; 
  if ( function_exists( $testfunc ) ) {
    $funcname = $testfunc;
  } else {
    $funcname = '_sc'. $suffix;
    if ( bw_load_shortcodefile( $shortcode ) && function_exists( $testfunc ) ) {
      $funcname = $testfunc; 
    }   
  }   
  return( $funcname );
}

function _bw_lazy_sc_help( $shortcode ) { 
  $funcname = bw_load_shortcode_suffix( $shortcode, "__help" ); 
  $help = $funcname( $shortcode );
  return( $help );
}  
 
function bw_lazy_sc_help( $shortcode ) {
  e( _bw_lazy_sc_help( $shortcode )) ;
}

function bw_lazy_sc_example( $shortcode, $atts=null ) {
  $funcname = bw_load_shortcode_suffix( $shortcode, "__example" ); 
  $funcname( $shortcode, $atts ); 
}

function _bw_lazy_sc_syntax( $shortcode ) {
  $funcname = bw_load_shortcode_suffix( $shortcode, "__syntax" ); 
  $syntax = $funcname( $shortcode );
  return( $syntax );
}  
  

function bw_lazy_sc_syntax( $shortcode ) {
  stag("code");
  e( '['.$shortcode );
  $syntax = _bw_lazy_sc_syntax( $shortcode );
  bw_form_sc_syntax( $syntax );
  e( ']' );
  etag( "code" );
}

function bw_save_scripts() {
  global $wp_scripts, $bw_scripts;
  $bw_scripts = $wp_scripts;
  bw_trace2( $bw_scripts );
}

function bw_report_scripts() {
  global $wp_scripts, $bw_scripts;
  if ( $wp_scripts != $bw_scripts ) {
    p( "Some scripts were changed!" );
    
  }
}

/** 
 * Produce the HTML snippet of the shortcode output
*/
function _sc__snippet( $shortcode="bw_code", $atts=null ) {
  $example = "[$shortcode";
  if ($atts ) 
    $example .= " $atts";
  $example .= ']';

  if ( $example == null ) {    
    $example = "[$shortcode]"; 
  } 
  bw_save_scripts(); 
  bw_push();
  $formatted_example = apply_filters( 'the_content', $example ); 
  bw_pop();
  bw_trace2( $formatted_example, "formatted example" );
  bw_report_scripts();
  $escaped_example = esc_html( $formatted_example );
  stag( 'p', null, null, 'lang=HTML" escaped="true"' );
  e( $escaped_example );
  etag( "p" );
}

function bw_lazy_sc_snippet( $shortcode=null, $example=null ) {
  $funcname = bw_load_shortcode_suffix( $shortcode, "__snippet" ); 
  bw_trace2( $funcname, "funcname" );
  $funcname( $shortcode, $example ); 
}

/**
 * Returns the default help for a shortcode if not provided by the "shortcode__help" function
 *
 * This default array should be a large array of all the "known" shortcodes for oik and WordPress
 * and possibly the "recommended" plugins that oik works closely with.
 *
 * - Some are shortcodes from Artisteer themes
 * - Some are default WordPress shortcodes
 * - The bob, bing, wide, etc. shortcodes are optional
 * - 
 * 
 */
function _sc__help( $shortcode="bw_code" ) {
 
  $default_help = array( 
"OIK" => "Spells out the <span class=\"b1\">o<\/span><span class=\"o\">i<\/span><span class=\"b2\">k<\/span> backronym",
"WOW" => "Spells out Wonder of WordPress",
"WoW" => "Spells out Wonder of WordPress",
"abt_slider_hero" => "",
"ad" => "Show an advertisement block (Artisteer theme)",
"artisteer" => "Styled form of Artisteer",
"bing" => "Styled form of bing",
"blog-title" => "blog title (Artisteer theme)",
"bob" => "Styled form of bob",
"bong" => "Styled form of bong",
"bp" => "Styled form of BuddyPress",
"bw" => "Expand to the logo for Bobbing Wide",
"bw_abbr" => "Format an abbreviation",
"bw_acronym" => "Format an acronym",
"bw_address" => "Display the address",
"bw_admin" => "Display the Admin contact name",
"bw_alt_slogan" => "Alternative slogan",
"bw_attachments" => "List attachments with links",
"bw_block" => "Format an Artisteer block",
"bw_blockquote" => "Format a blockquote",
"bw_bookmarks" => "List bookmarks",
"bw_business" => "Display your Business name",
"bw_button" => "Show a link as a button",
"bw_cite" => "Cite a blockquote",
"bw_code" => "Display the help and syntax for a shortcode",
"bw_codes" => "Summarise active shortcodes",
"bw_company" => "Company name",
"bw_contact" => "Primary contact name",
"bw_contact_button" => "Contact form button",
"bw_copyright" => "Format a Copyright statement",
"bw_directions" => "Display a 'Google directions' button.",
"bw_domain" => "Display the domain name",
"bw_eblock" => "end a [bw_block]",
"bw_editcss" => "Edit Custom CSS file button", 
"bw_email" => "Email primary contact (formal)",
"bw_email_signature" => "Format the email signature",
"bw_emergency" => "Emergency telephone number",
"bw_facebook" => "Facebook link",
"bw_fax" => "Fax number",
"bw_flickr" => "Flickr link",
"bw_follow_me" => "Display defined social media follow me links",
"bw_formal" => "Formal company name",
"bw_geo" => "Latitude and Longitide",
"bw_google" => "Google+ link",
"bw_google-plus" => "Google+ link",
"bw_google_plus" => "Google+ link",
"bw_googleplus" => "Google+ link",
"bw_images" => "Display attached images",
"bw_linkedin" => "Follow me on LinkedIn",
"bw_list" => "Simple list of pages/posts or custom post types",
"bw_logo" => "Display the company logo",
"bw_mailto" => "Mailto (inline)",
"bw_mob" => "Mobile phone number (inline) ",
"bw_mobile" => "Mobile phone number (block)",
"bw_module" => "Information about a Drupal module",
"bw_page" => "Add page button",
"bw_pages" => "Display page thumbnails and excerpts as links",
"bw_pdf" => "Display attached PDF files",
"bw_picasa" => "Follow me on Picasa",
"bw_plug" => "Show plugin information",
"bw_portfolio" => "Display matched portfolio files",
"bw_post" => "Add Post button",
"bw_posts" => "Display posts",
"bw_power" => "Powered by WordPress", 
"bw_qrcode" => "Display an uploaded QR code image",
"bw_show_googlemap" => "Show Google map [bw_show_googlemap]",
"bw_skype" => "Skype name",
"bw_slogan" => "Primary slogan",
"bw_tel" => "Telephone number (inline)",
"bw_telephone" => "Telephone number (block)",
"bw_thumbs" => "List pages as fluid thumbnail links",
"bw_tides" => "Display tide times and heights",
"bw_tree" => "Simple tree of pages/posts or custom post types",
"bw_twitter" => "Follow me on Twitter",
"bw_wpadmin" => "Site: link to wp-admin",
"bw_wtf" => "WTF",
"bw_youtube" => "Follow me on YouTube",
"bwtrace" => "Trace facility form",
"bwtroff" => "Force trace off",
"bwtron" => "Force trace on",
"caption" => "Display the caption for an image. Standard WordPress shortcode",
"clear" => "Clear divs ",
"css" => "Show a valid CSS icon (Artisteer theme)",
"div" => "start a <div> tag",
"drupal" => "Styled form of Drupal",
"ediv" => "end a <div> with <\/div>",
"embed" => "Embed media",
"etag" => "End a tag started with [stag]",
"fob" => "Styled form of fob",
"gallery" => "Display the attached images in a gallery",
"gpslides" => "Display a Slideshow Gallery Pro slideshow",
"hide" => "Styled form of hide",
"lartisteer" => "Link to Artisteer ",
"lbp" => "Link to BuddyPress",
"lbw" => "Link to Bobbing Wide sites",
"ldrupal" => "Link to drupal.org",
"login-link" => "Login link (Artisteer themes)",
"loik" => "Link to [oik]-plugins",
"lwp" => "Link to WordPress.org",
"lwpms" => "Link to WordPress Multi Site",
"ngslideshow" => "NextGen gallery slideshow",
"oik" => "Expand to the logo for oik",
"paypal" => "Paypal shortcodes",
"rss" => "Produce an RSS feeds button (Artisteer themes)",
"sdiv" => "Start a div",
"sediv" => "Start and end a div",
"stag" => "Start a tag",
"top" => "Top of page button (Artisteer themes)",
"wide" => "Styled form of wide",
"wow" => "Styled form of WoW",
"wp" => "Display a styled form of WordPress. ",
"wp-1" => "Sample hyphenated shortcode",
"wp-2" => "Sample hyphenated shortcode",
"wp-3" => "Sample hyphenated shortcode",
"wp_caption" => "Caption (WordPress shortcode - alias)",
"wpms" => "Styled form of WordPress Multi Site",
"xhtml" => "Valid XHTML button (Artisteer theme)",
"year" => "Current year (Artisteer theme)",
);
                        
                      
  $help = bw_array_get( $default_help, $shortcode, "?" );
  return( $help );
}

function _sc__syntax( $shortcode="bw_code" ) {
  //return( "oik doesn't know about this shortcode's syntax: ". $shortcode );
  return( null );
}

function _sc__example( $shortcode="bw_code", $atts=null ) {
  //p( "oik doesn't know how to give an example for this shortcode: ". $shortcode );
  bw_invoke_shortcode( $shortcode, $atts, "oik generated example." );
}

/**
 * Helper functions for shortcodes that support these atts
*/
function _sc_classes() {
 return( array( "class"=> bw_skv( "", "<i>class names</i>", "CSS class names" )
              , "id" => bw_skv( "", "<i>id</i>", "unique identifier" )
              ));
              
}

 
/**
 * Helper function for shortcodes that use get_posts() 
 *
 *   post_mime_type=
 *     application/pdf
 *      image/gif
 * 	image/jpeg
 * 	image/png
 * 	text/css
 *      video/mp4
 *  
 *               , 'block'           => bw_skv( "true", "false", "Enclose in an Artisteer block" )
 *               , 'posts_per_page'  => bw_skv( -1, "", "Not supported in OIK" )

*/
function _sc_posts() {
  return( array( 'numberposts'  => bw_skv( "5", "numeric", "number to return" )
               , 'offset'          => bw_skv( 0, "numeric", "offset from which to start" )
               , 'category'        => bw_skv( null, "<i>category-slug</i>", "category slugs (comma separated)" )
               , 'customcategoryname' => bw_skv( null, "category-slug", "custom category slug" )
               , 'orderby'         => bw_skv( 'date', "ID|title|parent|rand|menu_order", "Sort sequence" )
               , 'order'           => bw_skv( 'DESC', "ASC", "Sort order." )
               , 'include'         => bw_skv( null, "<i>id1,id2</i>", "IDs to include" )
               , 'exclude'         => bw_skv( null, "<i>id1,id2</i>", "IDs to exclude" )
               , 'meta_key'        => bw_skv( null, "meta key", "post metadata key" )
               , 'meta_value'      => bw_skv( null, "meta value", "post metadata value" )
               , 'post_type'       => bw_skv( "page", "post|attachment|custom post type", "Content type to display" )
               , 'post_mime_type'  => bw_skv( null, "image|application|text|video|mime type", "Attached media MIME type" )
               , 'post_parent'     => bw_skv( null, "ID", "Parent ID to use if not current post" )
               , 'post_status'     => bw_skv( 'publish', "pending|draft|auto-draft|future|private|trash|any", "Post status" )
               ));
} 

function _sc_thumbnail() {   
  return( array( 'thumbnail'       => bw_skv( "thumbnail", "medium|large|full|nnn|wxh", "image size" )   
               ));
}        

              


/**  
 * These functions return the help and syntax for the shortcodes that are activated
*/


function div__help() {
  return( "start a &lt;div&gt; tag" );
}

function _div__syntax( ) {
  $syntax = _sc_classes();
  return( $syntax );
} 
 
function ediv__help() { 
  return( esc_html( "end a <div> with </div>") );
}

function ediv__syntax() {
  return( null );
}

function caption__help() {
  return( "Display the caption for an image. Standard WordPress shortcode" );
  
}

function caption__example() {    
  br( "e.g:");
  // **?** The caption should surround an image - so we need to include an image in this
  $ics = img_caption_shortcode( array( 'width' => 70, 'caption' => 'This is a caption') );
  e( $ics );
}



function wp_caption__help() {
  return( caption__help() );
}

function caption__syntax() {
  $syntax = array( 'id' => "value for CSS id= keyword"
                 , 'align' => "CSS alignment class (default=alignnone)"
                 , 'width' => "width in pixels (Note: 10 is added to this number)"
                 , 'caption' => "caption text"
                 );
  return( $syntax );               
}

function wp_caption__syntax() {
  return( caption__syntax() );
} 

function gallery__help() {
  return( "Display the attached images in a gallery" );
}

function gallery__syntax() {
  $syntax = array( 'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => 'Post ID. Default: Current post ID',
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => '' );
  return( $syntax );
} 
 
function embed__help( $shortcode='embed' ) {
  return( "Embed media" );
}

function embed__example( $shortcode='embed' ) {
  p( "Embed example" );
}

function embed__syntax( $shortcode='embed' ) {
  $syntax = array( "width" => bw_skv( "width", "500|embed_size_w|content_width", "Required width" )
                 , "height" => bw_skv( "height", "700|embed_size_h", "Required height" )
                 );
  return( $syntax );
}


function wp__help() {
  return( "Display a styled form of WordPress. " );
}

function wp__example() {  
  oik_require( "shortcodes/oik-bob-bing-wide.php" );
  e( "e.g.". bw_wp());
}



function bw_code__help() {
  return( "Display the help and syntax for a shortcode" );
}

function bw_code__syntax() {
  $syntax = array( "shortcode" => bw_skv( "bw_code", "<i>shortcode</i>", "The shortcode you want explained" )  
                 , "help" => bw_skv( "Y", "N", "Display help for the shortcode" )
                 , "syntax" => bw_skv( "Y", "N", "Display the syntax" )
                 , "example" => bw_skv( "Y", "N", "Display an example, if possible" )
                 , "live" => bw_skv( "N", "Y", "Display the live results using default values" )
                 , "snippet" => bw_skv( "N", "Y", "Display the generated HTML" )
                 );
  return( $syntax );
}


function bw_code__example() {
  br();
  e( "e.g. [bw_code shortcode=\"oik\"]" );
  br();
  e( "Display information about the [oik] shortcode" );
  br();
  oik__help();
  br();
  bw_lazy_sc_syntax( "oik" );
  oik__example();
}

/** 
 * Return the default, values and notes for a shortcode parameter
 *  bw_skv is an abbreviation of bw_sc_key_values  
 */
function bw_skv( $default, $values, $notes ) {
  return( array( "default" => $default
               , "values" => $values
               , "notes" => $notes
               )  );
}


/**
 * Return the choices and notes for a keywords values
 *
 * @param mixed $value - array or string containing attribute values 
 * @return string - HTML showing the default value, other values and notes
 */
function bw_format_skv( $value ) {  
  if ( is_array( $value ) ) {    
    $default = bw_array_get( $value, "default", null );
    $values = bw_array_get( $value, "values", null );
    $notes = bw_array_get( $value, "notes", null );
    return( "<b>$default</b>|$values - $notes" );
  } else {
    return( $value );
  }    
}

function bw_codes__help() {
  return( "Display the currently available shortcodes" );
}

function bw_codes__example() {
  e( "The currently available shortcodes are displayed in a table with the known syntax a brief description and an example" );
  e( "For some shortcodes it's not that easy to give an inline example. In these cases there should be a link to an external page." );
  alink( null, "http://www.oik-plugins.com/oik-shortcodes/bw_codes-shortcode/", "[bw_codes] - list shortcodes" );
  
  // br( "Note: The default display lists the order in which the shortcodes are <i>evaluated</i>" );
  // e( "If you have a problem with hyphenated shortcodes not being chosen then it could be due to the order in which the shortcodes were registered using add_shortcode();" );
  // because the shortest shortcode has been added before the longer ones. 
  // See wp-1, wp-2, wp and wp-3 ");
}


function bw_directions__help() {
  return( "Display a 'Google directions' button.");
}

function bw_directions__example() {
  br( "e.g." );
  e( "The Google directions button will enable the user to get directions to you." );
  e( bw_directions() );
}

function bw__help( $shortcode=NULL ) {
  if ( $shortcode == "bw" ) 
     return( "Expand to the logo for Bobbing Wide" );
}

function bw__example( $shortcode = NULL ) {
  if ( $shortcode == "bw" ) {
    br( "e.g. " );
    e( bw() );
  }
}

function oik__help( $shortcode=NULL ) {
 if ( $shortcode == "OIK" ) {
    return( "Spells out the ". bw_oik(). " backronym" );
 } else {
    return( "Expand to the logo for oik" );
 }  
}

function oik__example( $shortcode=NULL ) {
 br( "e.g." );
 if ( $shortcode == "OIK" ) {
   e( bw_oik_long() ); 
 }else {
   e( bw_oik() );
 }  
} 

/**
 * Function to call a shortcode for a simple example
 * 
 * @param string $shortcode - name of shortcode
 * @param string $atts - attribute parameters to the shortcode
 * @param string $text - brief description of the shortcode 
 *
 * @uses apply_filters() rather than do_shortcode() since the shortcodes that get invoked
 * may not support the current_filter() - which on an admin page could be oik-options_page_oik_options-1
 * Nearly ALL shortcodes support 'the_content' so we will apply that filter
 * 
 
 * Now I need to convert all unnecessary calls to bw_add_shortcode_event (or current_filter) to use this function instead **?**
*/
function bw_invoke_shortcode( $shortcode, $atts=null, $text ) {
  p( $text );
  $example = "[$shortcode";
  if ($atts ) 
    $example .= " $atts";
  $example .= ']';
  sp();
  stag( "code" );
  e( $example ); 
  etag( "code" ); 
  ep();
  //p( $example );
  e( apply_filters( 'the_content', $example ) );
}  
