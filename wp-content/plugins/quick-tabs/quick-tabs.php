<?php
/*
Plugin Name: Quick Tabs
Plugin URI: http://www.wpdownloadmanager.com
Description: Create tabs easily inside your page or post
Author: Shaon
Version: 1.0.1
Author URI: http://www.wpdownloadmanager.com
*/

$qt_pages = array('quick-tabs','quick-tabs/add-new','quick-tabs/tab-groups');

set_include_path(dirname(__FILE__).'/');

include("libs/functions.php");
include("libs/wpqt-mce-button.php");

 function wpqt_Install(){
    global $wpdb;
    global $jal_db_version;

      $sqls[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_qt_tabs` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tab_title` varchar(255) NOT NULL,
              `tab_content` text NOT NULL,              
              `tab_options` text NOT NULL,  
              `tab_content_type` enum('html','url','php','post','page','module') NOT NULL DEFAULT 'html',            
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

      $sqls[] = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ahm_qt_tab_groups` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `tg_name` int(11) NOT NULL,
              `tg_content` int(11) NOT NULL,              
              `tg_tabs` text NOT NULL,
              `tg_options` text NOT NULL,             
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8";
                   
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      foreach($sqls as $sql){
      dbDelta($sql); 
      }

   update_option('access_level','level_10');
      
}    



function wpqt_menu(){
    add_menu_page("Quick Tabs","Quick Tabs",get_option('access_level'),'quick-tabs','wpqt_manage_tabs',plugins_url().'/quick-tabs/images/tabs16.png');
    add_submenu_page( 'quick-tabs', 'Quick Tabs', 'Manage', get_option('access_level'), 'quick-tabs', 'wpqt_manage_tabs');    
    add_submenu_page( 'quick-tabs', 'Create Tab &lsaquo; Quick Tabs', 'Create Tab', get_option('access_level'), 'quick-tabs/add-new', 'wpqt_create_tab');        
    add_submenu_page( 'quick-tabs', 'Tab Groups &lsaquo; Quick Tabs', 'Tab Groups', get_option('access_level'), 'quick-tabs/tab-groups', 'wpqt_tab_groups');        
    //add_submenu_page( 'quick-tabs', 'Settings &lsaquo; Quick Tabs', 'Settings', 'administrator', 'quick-tabs/settings', 'wpqt_settings');        
}




function wpqt_execute(){
    if($_GET['execute']) {
    call_user_func($_GET['execute']);   
    $red = $_REQUEST['redirect']?$_REQUEST['redirect']:$_SERVER['HTTP_REFERER'];
    header("location: ".$red);
    die(); 
    }
}

add_action("admin_menu","wpqt_menu");
register_activation_hook(__FILE__,'wpqt_Install');
add_shortcode('quick-tabs', 'wpqt_frontend_tabs');

if(is_admin()&&in_array($_GET['page'],$qt_pages)){    
    wp_enqueue_style('qt-admin',plugins_url().'/quick-tabs/css/qt-admin.css');
    wp_enqueue_style('widgets');
    wp_enqueue_style('global');
    wp_enqueue_style('wp-admin');    
    wp_enqueue_script('jquery');
    wp_enqueue_script('utils');
    wp_enqueue_script('jquery-form',plugins_url().'/quick-tabs/js/jquery.form.js');
    add_filter('admin_head','wpqt_tinymce');
    add_filter('init','wpqt_execute');
}

include("tab-styles/tab-style-1.php");

if(!is_admin()){
    wp_enqueue_script('jquery');
    add_action("wp_head","tab_style_1_css");
    add_action("wp_head","tab_style_1_js");
}


                                      
?>