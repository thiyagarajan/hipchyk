<?php // (C) Copyright Bobbing Wide 2011-2012

if ( !isset( $bw_trace_on )) {
  $bw_trace_on = false;
}

function bw_trace( $text, $function=__FUNCTION__, $lineno=__LINE__, $file=__FILE__, $text_label=NULL) {
  global $bw_trace_on;
  if ( $bw_trace_on  )
    bw_lazy_trace( $text, $function, $lineno, $file, $text_label );  
}

function bw_trace2( $value=NULL, $text=NULL ) { 
  global $bw_trace_on;
  if ( $bw_trace_on ) 
    return( bw_lazy_trace2( $value, $text ));
  else  
    return( $value );
} 

function bw_backtrace() {
  global $bw_trace_on;
  if ( $bw_trace_on ) {
    bw_lazy_backtrace();
  }
}    


if ( defined('BW_TRACE_CONFIG_STARTUP' ) && BW_TRACE_CONFIG_STARTUP == true )
  require_once( ABSPATH . '/wp-content/plugins/oik/bwtrace_boot.inc' );

