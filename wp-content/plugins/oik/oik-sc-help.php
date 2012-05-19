<?php

/*
Plugin Name: oik shortcode help shortcodes
Plugin URI: http://www.oik-plugins.com/oik
Description: [bw_code] and [bw_codes] shortcodes
Version: 1.13
Author: bobbingwide
Author URI: http://www.bobbingwide.com
License: GPL2

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
add_action( "oik_loaded", "oik_sc_help_init" );

function oik_sc_help_init() { 
  bw_add_shortcode( "bw_codes", "bw_codes", oik_path( "shortcodes/oik-codes.php") , false );
  bw_add_shortcode( "bw_code", "bw_code", oik_path( "shortcodes/oik-codes.php") , false ); 
}







