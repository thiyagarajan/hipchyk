<?php // (C) Copyright Bobbing Wide 2012 

/**
 * Display a message when oik-nivo-slider is not fully functional due to the dependencies not being activated or installed
 * Note: We can't use oik APIs here as we don't know if it's activated.
 * If the message is issued due to a version mismatch then there is a chance that one plugin attempts to use
 * functions that are not available in the dependent plugin. How do we manage this?
*/
function oik_nivo_inactive( $plugin=null, $dependencies=null ) {
  $dependencies = str_replace( ":", " version ", $dependencies );
  $text = "<p><b>oik-nivo-slider may not be fully functional</b>. It is dependent upon the <b>oik</b> plugin. ";
  $text.= "Please install and activate the required version of this plugin: $dependencies</p>";
  if ( current_filter() == "admin_notices" ) {
    $message = '<div class=" updated fade">';
    $message .= $text;
    $message .= '</div>'; 
    
  } else {
    $message = '<tr class="plugin-update-tr">';
    $message .= '<td colspan="3" class="plugin-update colspanchange">';
    $message .= '<div class="update-message">';
    $message .= $text;
    $message .= "</div>";
    $message .= "</td>";
    $message .= "</tr>";
  }
  echo $message; 
}

/**
 * Test if oik-nivo-slider is functional
 * 
 * Unless oik is installed and activated oik-nivo-slider won't do anything
 * Note: If oik is installed and activated then we shouldn't have any problem
 * unless there's a version number mismatch.
*/
function oik_nivo_lazy_activation( $plugin=null, $dependencies=null, $callback=null ) {
  if ( function_exists( "oik_depends" ) ) {  
    /* Good - oik appears to be activated and loaded */
    oik_depends( $plugin, $dependencies, $callback );
  } else {
    call_user_func( $callback, $plugin, $dependencies );
  }   
}

function oik_nivo_lazy_admin_menu() {
  register_setting( 'oik_nivo_options', 'bw_nivo_slider', 'oik_plugins_validate' ); // No validation for oik-money / oik-nivo-slider
  add_submenu_page( 'oik_menu', 'oik nivo slider settings', "Nivo settings", 'manage_options', 'oik_nivo', "oik_nivo_options_do_page" );
}


/**
 * Coding Nivo slider fields here just for the hell of it 
*/
function oik_nivo_options_do_page() {
  oik_menu_header( "Nivo slider options", "w50pc" );
  oik_box( NULL, NULL, "Default slider options", "oik_nivo_slider_options" );
  ecolumn();
  scolumn( "w50pc" );
  oik_box( NULL, NULL, "Usage notes", "oik_nivo_slider_usage" );
  oik_menu_footer();
  bw_flush();
}


/**
 * Settings for the jQuery Nivo slider  
 *
 * Extract from jQuery Nivo slider below
 * The setting selection field has been set for each line marked with an 'x' 

      x	effect: 'random',
      x	slices: 15,
      x	boxCols: 8,
      x	boxRows: 4,
      x animSpeed: 500,
      x	pauseTime: 3000,
      	startSlide: 0,
      x	directionNav: true,
      x	directionNavHide: true,
      x	controlNav: true,
      	controlNavThumbs: false,
        controlNavThumbsFromRel: false,
      	controlNavThumbsSearch: '.jpg',
      	controlNavThumbsReplace: '_thumb.jpg',
      	keyboardNav: true,
      x	pauseOnHover: true,
      x	manualAdvance: false,
      x	captionOpacity: 0.8,
      	prevText: 'Prev',
      	nextText: 'Next',
      	randomStart: false,
      	beforeChange: function(){},
      	afterChange: function(){},
      	slideshowEnd: function(){},
        lastSlide: function(){},
        afterLoad: function(){}
        
*/
function oik_nivo_slider_options() {
  bw_form( "options.php" );
  $option = "bw_nivo_slider"; 
  $options = get_option( $option );     
  stag( 'table class="form-table"' );
  bw_flush();
  settings_fields('oik_nivo_options');
  $effect_options = array( 'random', 'sliceDownRight','sliceDownLeft','sliceUpRight','sliceUpLeft','sliceUpDown','sliceUpDownLeft','fold','fade',
                'boxRandom','boxRain','boxRainReverse','boxRainGrow','boxRainGrowReverse' );
  $effect_options_assoc = bw_assoc( $effect_options );                
                
  bw_select_arr( $option, "Effect", $options, 'effect', array( "#options" => $effect_options_assoc ) );
  
  bw_textfield_arr( $option, "Slices", $options, 'slices', 2 );
  bw_textfield_arr( $option, "Box cols", $options, 'boxCols', 2 );
  bw_textfield_arr( $option, "Box rows", $options, 'boxRows', 2 );
  bw_textfield_arr( $option, "Anim speed", $options, 'animSpeed', 4 );
  bw_textfield_arr( $option, "Pause time", $options, 'pauseTime', 4 );
  
  bw_checkbox_arr( $option, "Control nav", $options, 'controlNav' );
  //bw_checkbox_arr( $option, "Control nav thumbs", $options, 'controlNavThumbs' );
  //bw_textfield_arr( $option, "Control nav thumbs search", $options, 'controlNavThumbsSearch', 20 );
  //bw_textfield_arr( $option, "Control nav thumbs replace", $options, 'controlNavThumbsReplace', 20 );
  bw_checkbox_arr( $option, "Direction nav", $options, 'directionNav' );
  bw_checkbox_arr( $option, "Direction nav hide", $options, 'directionNavHide' );
  bw_checkbox_arr( $option, "Pause on hover", $options, 'pauseOnHover' );
  bw_checkbox_arr( $option, "Manual advance", $options, 'manualAdvance' );
  
  bw_textfield_arr( $option, "Caption opacity", $options, 'captionOpacity', 4 );
  
  
  bw_tablerow( array( "", "<input type=\"submit\" name=\"ok\" value=\"Save changes\" class=\"button-primary\"/>") ); 
  etag( "table" ); 			
  etag( "form" );
  bw_flush();
}  

function oik_nivo_slider_usage() {
  p("These options for the jQuery Nivo slider control the default behaviour for each instance of the slider." );
  //p("You can override these using the keyword parameters on the [nivo] shortcode" );
  oik_require( "nivo.inc", "oik-nivo-slider" );
  oik_require( "includes/oik-sc-help.inc" );
  bw_flush();
  //sdiv( "bd-500" );
  nivo__example();
  //ediv();
  sediv("cleared"); 
}


