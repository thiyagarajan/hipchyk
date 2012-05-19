<?php
/*
Plugin Name: comments-likes
Plugin URI: http://nischalmaniar.info/2010/12/comments-likes/
Author URI: http://www.nischalmaniar.info
Version: 1.0
Author: <a href="http://www.nischalmaniar.info/">Nischal Maniar</a>
Description: Plugin to allow viewers to like comments.
*/
/*  Copyright 2010

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
error_reporting (E_ALL ^ E_NOTICE);
global $wpdb;
global $cl_table_name;
$cl_table_name = $wpdb->prefix . "comments_likes";

/* Function to create the table */
/********************************/
register_activation_hook(__FILE__,'cl_install');
function cl_install() {
    global $wpdb, $cl_table_name;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $cl_db_version = "1.0";
    if($wpdb->get_var("SHOW TABLES LIKE '$cl_table_name'") != $cl_table_name) {
        $sql = "CREATE TABLE " . $cl_table_name . " (
	  like_ID mediumint(9) NOT NULL AUTO_INCREMENT,
          like_comment_ID INT(20) NOT NULL,
	  like_IP text NOT NULL,
	  UNIQUE KEY like_ID (like_ID)
	);";
        add_option("cl_db_version", $cl_db_version);
    } else {
        $installedversion = get_option('cl_db_version');
	if($installedversion != $cl_db_version) {
	    $sql = "CREATE TABLE " . $cl_table_name . " (
		like_ID mediumint(9) NOT NULL AUTO_INCREMENT,
                like_comment_ID INT(20) NOT NULL,
                like_IP text NOT NULL,
                UNIQUE KEY like_ID (like_ID)
	      );";
	    update_option("cl_db_version",$cl_db_version);
	}
    }
    dbDelta($sql);
}

/* Function to delete table on uninstall */
/*****************************************/
register_uninstall_hook(__FILE__,'cl_uninstall');
function cl_uninstall() {
    global $wpdb, $cl_table_name;
    if(defined(WP_UNINSTALL_PLUGIN)) {
	$wpdb->query("DROP TABLE IF EXISTS $cl_table_name");
	delete_option('cl_db_version');
    }
}

/* Function to add header files */
/*******************************/
add_action('wp_head','cl_header');
function cl_header() {
    echo '<link type="text/css" rel="stylesheet" href="'. get_bloginfo('wpurl').'/wp-content/plugins/comments-likes/comments-likes.css" />'."\n";
    echo '<script type="text/javascript" src="'.get_bloginfo('wpurl').'/wp-content/plugins/comments-likes/js/jquery.js"></script>'."\n";
    echo '<script type="text/javascript" src="'.get_bloginfo('wpurl').'/wp-content/plugins/comments-likes/js/comments-likes.js"></script>'."\n";
}

/* Function to display the like button & count */
/***********************************************/
add_filter('comment_text','cl_display');
function cl_display($content) {
    $commentid = get_comment_ID();
    $ipaddr = $_SERVER['REMOTE_ADDR'];
    $ajaxurl = admin_url('admin-ajax.php');
    if(cl_check_duplicate($commentid,$ipaddr)) $like_click = ''; else $like_click = 'onclick="cl_like_this(\''.$ajaxurl.'\','.$commentid.')"';
    $likebtn = '<p class="comment-like"><img class="comment-like-btn" title="Vote" '.$like_click.' src="'.get_bloginfo('wpurl').'/wp-content/plugins/comments-likes/images/like.png" />&nbsp;&nbsp;&nbsp;<span id="comment-like-cnt-'.$commentid.'">'.cl_get_like_count($commentid).'</span> likes</p>';
    $content = $content.$likebtn;
    return $content;
}

/* Function to get number of likes */
/***********************************/
function cl_get_like_count($commentid) {
    global $wpdb, $cl_table_name;
    $likecount = intval($wpdb->get_var("SELECT COUNT(like_ID) FROM $cl_table_name WHERE like_comment_ID = $commentid"));
    return $likecount;
}

/* Function to insert new votes */
/********************************/
add_action('wp_ajax_nopriv_cl_add_like', 'cl_add_like');
add_action('wp_ajax_cl_add_like', 'cl_add_like');
function cl_add_like() {
    global $wpdb, $cl_table_name;
    $commentid = intval($_POST['commentid']);
    $ipaddr = $_SERVER['REMOTE_ADDR'];
    if(!cl_check_duplicate($commentid,$ipaddr)) $wpdb->query("INSERT INTO $cl_table_name (like_comment_ID, like_IP) VALUES ($commentid, '$ipaddr')");
    echo cl_get_like_count($commentid);
    exit;
}

/* Function to check multiple clicks */
/*************************************/
function cl_check_duplicate($commentid, $ipaddr) {
    global $wpdb, $cl_table_name;
    $duplicateip = intval($wpdb->get_var("SELECT COUNT(like_ID) FROM $cl_table_name WHERE like_comment_ID = $commentid and like_IP = '$ipaddr'"));
    if($duplicateip > 0) return true; else return false;
}

?>