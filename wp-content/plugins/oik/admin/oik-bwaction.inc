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


if ( !defined('ABSPATH') ) {
  define('ABSPATH', dirname(__FILE__) . '/');
  function oik_path( $file = NULL, $plugin='oik') {
    return( $file );
  }

  require_once( '../oik_boot.inc' ); 
  require_once( '../bobbfunc.inc' );
  require_once( '../bobbforms.inc' );
}

function bw_average( $elapsed, $count ) { 
  if ( $count ) {
    $average = $elapsed / $count ;
    $av = sprintf( "%1.6F", $average );
    //c( $average );
    //c( $av );
  } else {
    $av = $elapsed;
  }     
  return( $av );
} 

if ( !function_exists( "bw_array_add" ) ) { 

function bw_array_add( &$array, $index, $amount ) {
  if ( ! isset($array[$index]) ) {
    $value = $amount;
  } else {
    $value = $array[$index] + $amount;
  }
  return( $value );  
}
}

function _bw_time( $ts, $msec ) {
  $time = strtotime( $ts );
  return( "$time $msec" );
}

function _bw_time_diff( $endtime, $starttime ) {

  list( $su, $ss ) = explode(" ", $starttime );
  list( $eu, $es ) = explode(" ", $endtime );
  $elapsedsec  = $es - $ss;
  $elapsedu = $eu - $su;
  
  $elapsed = $elapsedsec + $elapsedu;
  
  //e( $elapsedsec );
  //e( $elapsedu );
  //e( $elapsed );
  return( $elapsed );  
}

function bw_action_start_time( $action, $ts, $msec ) {
  global $actions_start;
  $actions_start[ $action ] = _bw_time( $ts, $msec ); 
}

function bw_action_end_time( $action, $ts, $msec ) {
  $endtime = _bw_time( $ts, $msec );
  global $actions_start, $actions_elapsed;
  $starttime = bw_array_get( $actions_start, $action, $endtime );
  $elapsed = _bw_time_diff( $endtime, $starttime );
  //echo "$endtime $starttime $elapsed \n";
  $actions_elapsed[ $action ] = bw_array_add( $actions_elapsed, $action, $elapsed );
  
}


/** 
 * Handle a start or end action
 * 
 * The input data is expected to be in the following format:
 * $ts - made from data[0] and data[1] - it doesn't matter if there's no space between dd and hh
 * $msec - microsecs 
 * $storend = start '<S' or end 'E>'
 * $count = trace action count ( it may get reset )
 * $action = action name - extracted from cf=action name
 * where cf stands for "current filter"
   
 
2012-03-27 11:00:37 0.49518200 <S 607 cf=shutdown
2012-03-27 11:00:37 0.49676500 E> 608 cf=shutdown

*/ 

function bw_handle_action( $data ) {
  global $actions;
  $ts = $data[0] . $data[1];
  $msec = $data[2];
  $storend = $data[3];
  $count = $data[4];
  $action = substr( $data[5], 3 ) ;
  if ( $storend == "<S" ) {
    bw_array_inc( $actions, $action );
    bw_action_start_time( $action, $ts, $msec );
  } else {
    bw_action_end_time( $action, $ts, $msec );
  } 
}


function bw_format_action_row( $action, $count, $elapsed, $average ) {
  $elapsed = sprintf( "%.6f", $elapsed );
  $average = sprintf( "%.6f", $average );
  $tr = array( $action, $count, $elapsed, $average );
  bw_tablerow( $tr ); 
  //echo $action;
}


function bw_summarise_actions( $file="bwaction.loh" ) {
  $target = "bwaction.cpy" ;
  //$res = copy( $file, $target ); 
  //echo "copy $res\n";
  
  $handle = fopen( $target, "r" );
  // echo $handle;
  while (($data = fgetcsv( $handle, 1000, " ")) !== FALSE) {
    bw_handle_action( $data );
  }
  fclose($handle);
  
  global $actions, $actions_elapsed;
  if ( count( $actions ) ) {
     
     foreach ( $actions as $action => $count ) {
       $elapsed = bw_array_get( $actions_elapsed, $action, 0 );
       $average = bw_average( $elapsed, $count );
       bw_format_action_row( $action, $count, $elapsed, $average ); 
       
     }  
  }
}

/** 
 * Display a summary of actions from the most recent action file 
 *
*/ 
function bw_action_summary() {
  $file = bw_action_file();
  if ( $file ) {
    stag( "table", "form-table" );
    stag( "thead" ); 
    stag( "tr" );
    th( "Action" );
    th( "Invocations" );
    th( "Elapsed" );
    th( "Average" );
    etag( "tr" );
    etag( "thead" );
    stag( "tbody" );
    
    bw_summarise_actions( $file );
    etag( "tbody" );
    etag( "table" );
    bw_action_reset_form();
  }
}



/**
 * Create the Action reset button for use somewhere in any page
 */
function bw_action_reset_form() {
  oik_require( "bobbforms.inc" );
  e( '<form method="post" action="" class="inline">' ); 
  e( "<input type=\"submit\" name=\"_bw_action_reset\" value=\"Action reset\" />" ); 
  etag( "form" );
}


