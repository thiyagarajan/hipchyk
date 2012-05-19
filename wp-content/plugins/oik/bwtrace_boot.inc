<?php // (C) Copyright Bobbing Wide 2012

/**
 * bwtrace_boot will initialise tracing and action logging when WordPress is not yet loaded
 *
 * This file should only be loaded when 'BW_TRACE_CONFIG_STARTUP' is true
 * 
 * When this is the case trace will be initialised using default values for trace logging and action logging
 * If the other defines are true then the trace and action log files will be reset
 *
 * Note: This can cause problems when Ajax requests are coming in
 * and we also have to deal with the UI for summarising the action log...
 * we have to take a copy of the previous log BEFORE anything else is done.
 * 
 * 
 * define( 'BW_TRACE_RESET', true );
 * define( 'BW_ACTIONS_RESET', true );
*/ 

if ( defined( 'BW_TRACE_CONFIG_STARTUP' )) {


  //require_once( ABSPATH . '/wp-content/plugins/oik/oik-bwtrace.php' );
  //bw_trace_on( true );
  //bw_trace_reset();
  //bw_action_reset();
  
  function bw_trace_config_startup( $trace_reset ) {
    require_once( ABSPATH . '/wp-content/plugins/oik/oik_boot.inc' );
    require_once( ABSPATH . '/wp-content/plugins/oik/includes/bwtrace.inc' );
    require_once( ABSPATH . '/wp-content/plugins/oik/includes/oik-bwtrace.inc' );
    bw_trace_inc_init();
    bw_trace_on( true );
    bw_action_inc_init();
    if ( $trace_reset )
      bw_action_reset();
      
  }

  
  if ( defined( 'BW_TRACE_RESET' ) )
    $trace_reset = BW_TRACE_RESET;
  else
    $trace_reset = false;
  bw_trace_config_startup( $trace_reset );

} 

