<?php

/*
Plugin Name: oik BuddyPress signup email
Plugin URI: http://www.oik-plugins.com/oik
Description: Send sign up verification emails via site admin for verification before accepting a user
Version: 1.13
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

    Copyright 2011, 2012 Bobbing Wide (email : herb@bobbingwide.com )

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

/** 
 * The default BuddyPress behaviour, when user registration is enabled, is to send an email to the email address given for the user registration
   The email contains the activation link.
   When the user clicks on the link then their id is activated.
   If the user is just a spammer then off they jolly well go.
   
   Some people have implemented recaptcha algorithm's to catch the spamming during the initial attempted registration
   This solution sends the sign up message to the site admin... who should then verify the attempted registration
   before forwarding the email.
   
   There are two routes... one for WPMS implementations the other for single site implementations.
   This solution is coded for BuddyPress version 1.5.n
   
   It has NOT been tested with Welcome Pack.
*/

// multi site   

add_filter( "wpmu_signup_user_notification", 'bw_signup_user_notification', 1, 4 );


// single site
add_filter( "bp_core_signup_send_validation_email_message", "bw_core_signup_send_validation_email_message", 1, 3 );
add_filter( "bp_core_signup_send_validation_email_to", "bw_core_signup_send_validation_email_to", 1, 2 );

/**
 * Create the body of the message to be sent to the admin user
 *
 */
function bw_activate_email_message( $admin_email, $user, $user_email, $key, $meta ) {
  $message  = "New user registration: $user_email\n";
  // $message .= "$meta\n";
  $message .= "To activate your user please click on the following link\n";
  $message .= site_url( "wp-activate.php?key=$key" );
  return $message;
}

function bw_activate_email_subject( $admin_email, $user, $user_email, $key, $meta ) {
  $subject .= get_site_option( 'site_name' );
  $subject .= " new user registration:";
  $subject .= " $user_email\n";
  return $subject;
}

function bw_signup_user_notification( $user, $user_email, $key, $meta ) {
  // bw_trace2();
  $admin_email = bw_send_validation_email_to();
    
  $from_name = get_site_option( 'site_name' ) == '' ? 'WordPress' : esc_html( get_site_option( 'site_name' ) );
  

  $message_headers = "From: \"{$from_name}\" <{$admin_email}>\n";
  $message_headers .= "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
  
  $message = bw_activate_email_message( $admin_email, $user, $user_email, $key, $meta );
  $subject = bw_activate_email_subject( $admin_email, $user, $user_email, $key, $meta );
    
  wp_mail( $admin_email, $subject, $message, $message_headers);
  return false;
}

function bw_send_validation_email_to() {
  $admin_email = get_site_option( 'admin_email' );
  if ( $admin_email == '' )
    $admin_email = 'support@' . $_SERVER['SERVER_NAME'];
  return $admin_email;
}

function bw_core_signup_send_validation_email_message( $message, $user, $activate_url ) {
  
  // bw_trace2();
  global $bw_user_email;
  
  $message .= "User: $user\n";
  $message .= "Email: $bw_user_email\n";
  $message .= "URL: $activate_url\n";
  return( $message );
}

function bw_core_signup_send_validation_email_to( $user_email, $user_id ) {
  //bw_trace2();
  global $bw_user_email;
  $bw_user_email = $user_email;
  
  return( bw_send_validation_email_to() );
}


