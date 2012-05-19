<?php

/**
 * Return an associative version of the active plugins array  
 *
 * $active_plugins is an array that looks like this.
 * bw_plug() is able to handle it so we can do the same here
 * EXCEPT bw_plug works at the "top level" not the child plugins
 * We convert this array into an associative array keyed on the sub-plugin name
 * $active_plugins                                      $names (keys only - 
 * ---------------------------------------------------  -------------------------------
 * (                                                    (
    [0] => abt-featured-heroes/abt-featured-heroes.php    ['abt-featured-heros'] 
    [1] => effort/tasks.php                               ['tasks'] 
    [2] => fancybox-for-wordpress/fancybox.php            ['fancybox']
    [3] => oik/oik-bob-bing-wide.php                      ['oik-bob-bing-wide']
    [4] => oik/oik-bwtrace.php                            ['oik-bwtrace'] 
    etcetera                                              ...
   )                                                    ) 
 * We haven't tested what happens if TWO or more plugins offer the same plugin name!
 * One of the first problems we get is if the two plugins share the same function names.
 *                                                    
*/
function bw_get_all_plugin_names( $active_plugins ) {
  $names = array();
  if ( count( $active_plugins ) ) {
    foreach ( $active_plugins as $key => $value ) {
      $name = basename( $value, '.php' );
      $names[$name] = $value;
    } 
  }
  return( $names ); 
} 


/**
 * Return an associative version of the sitewide active plugins array
 *
 * The MS active plugins array is structured like this 
 * (  $key                                     $value
 
    [oik-nivo-slider/oik-nivo-slider.php] => 1335804038
    [oik/oik.php] => 1335804114
   )
 * we need to make it the same as the array returned by bw_get_all_plugin_names()  
*/
function bw_ms_get_all_plugin_names( $active_plugins ) {
  $names = array();
  if ( count( $active_plugins ) ) {
    foreach ( $active_plugins as $key => $value ) {
      $name = basename( $key, '.php' );
      $names[$name] = $key;
    }  
  }
  return( $names );
}

/** 
 * Produce a message when a dependent plugin is inactive or the wrong version
 *
 * @param string $plugin - the plugin we're checking 
 * @param string $dependencies - format plug_name:version e.g. oik:1.12.1, where :version is optional
 * 
 */
function oik_plugin_inactive( $plugin, $dependencies ) {
  $plug_name = basename( $plugin, '.php' );
  $dependencies = str_replace( ":", " version ", $dependencies );
  $text = "<p><b>$plug_name may not be fully functional</b>.  ";
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
 * Checks that the version of the plugin is at least the value we specify
 * Notes:
 *  If there is no version function then we assume it's OK
 *  If no version is specified then we assume it's OK
 *  We perform string compares on the version - allowing for 1.0.995a etc
 *
*/ 
function oik_check_version( $depend, $version ) {
  $active = true;
  $version_func = "${depend}_version";
  if ( is_callable( $version_func )) {
    $active_version = $version_func();
    if ( $active_version < $version ) { 
      $active = false; 
    }  
  }
  return( $active );    
}

/**
 * Check that the plugins that this plugin is dependent upon are active
 *
 * @param string $plugin - name of the plugin being activated
 * @param string $dependencies - list of plugin dependencies - in whatever order you care
 * @param mixed $callback the callback to invoke when the dependency is not satisfied
 * Notes: 
 * The list of plugins could include oik - which should be loaded UNLESS this file is being
 * loaded by some other mechanism.
 */
function oik_lazy_depends( $plugin=null, $dependencies, $callback="oik_plugin_inactive" ) {
  //bw_backtrace();
  if ( is_multisite() ) {
    $active_plugins = get_site_option( 'active_sitewide_plugins');
    $names = bw_ms_get_all_plugin_names( $active_plugins );
  } else {
    $active_plugins = get_option( 'active_plugins' );
    $names = bw_get_all_plugin_names( $active_plugins );
  }  
  bw_trace2( $active_plugins, "active plugins" );
  
  $depends = explode( ",", $dependencies );
  foreach ( $depends as $dependcolver ) {
    list( $depend, $version ) = explode( ":", $dependcolver. ":" );
    //bw_trace2( $dependcolver, "dependcolver" );
    //bw_trace2( $depend, "depend" );
    //bw_trace2( $version, "version" );
    
    $active = bw_array_get( $names, $depend, null );
    if ( $active ) {
      $active = oik_check_version( $depend, $version );
    } 
    
    if ( !$active ) {
      bw_trace2( $depend, "$plugin is dependent upon $depend, which is not active or is the wrong version" );
      
      if ( !is_callable( $callback ) )
        $callback = "oik_plugin_inactive" ;
      call_user_func( $callback, $plugin, $dependcolver );
      //deactivate_plugins( array( $plugin ) );
    }
  }
}

   
