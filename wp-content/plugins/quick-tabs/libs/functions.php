<?php

function wpqt_manage_tabs(){
    global $wpdb;
    
    if($_GET['task']!=''){
        call_user_func($_GET['task']);
        return;
    }
    
    $limit = 30;
    $page = $_GET['paged']?$_GET['paged']:1;
    $start = ($page-1)*$limit;
    $tabs = $wpdb->get_results("select * from {$wpdb->prefix}ahm_qt_tabs limit $start,$limit",ARRAY_A);
     
    $total = $wpdb->get_var("select count(*) from {$wpdb->prefix}ahm_qt_tabs");
    include("tpls/tabs.php");
}

function wpqt_edit_tab(){
    global $wpdb;
    $tab = $wpdb->get_row("select * from {$wpdb->prefix}ahm_qt_tabs where id='{$_GET[id]}'",ARRAY_A);    
    include("tpls/create-tab.php");
}

function wpqt_get_tab($id){
    global $wpdb;
   return $wpdb->get_row("select * from {$wpdb->prefix}ahm_qt_tabs where id='{$id}'",ARRAY_A);     
}

function wpqt_create_tab(){
    include("tpls/create-tab.php");
}

function wpqt_tab_groups(){
    global $wpdb;
    $tgs = $wpdb->get_results("select * from {$wpdb->prefix}ahm_qt_tab_groups",ARRAY_A);            
    include("tpls/tab-groups.php");
}



function wpqt_settings(){
    include("tpls/tab-settings.php");
}

function wpqt_add_tabs(){
   global $wpdb;
   $tabs = $wpdb->get_results("select * from {$wpdb->prefix}ahm_qt_tabs",ARRAY_A);         
   include("tpls/add-tabs.php"); 
   die();
}


function wpqt_tinymce()
{
  wp_enqueue_script('common');
  wp_enqueue_script('jquery-color');
  wp_admin_css('thickbox');
  wp_print_scripts('post');
  wp_print_scripts('media-upload');
  wp_print_scripts('jquery');
  wp_print_scripts('jquery-ui-core');
  wp_print_scripts('jquery-ui-tabs');
  wp_print_scripts('tiny_mce');
  wp_print_scripts('editor');
  wp_print_scripts('editor-functions');
  add_thickbox();
  wp_tiny_mce();
  wp_admin_css();
  wp_enqueue_script('utils');
  do_action("admin_print_styles-post-php");
  do_action('admin_print_styles');
  remove_all_filters('mce_external_plugins');
}    
    
function wpqt_save_new_tab(){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}ahm_qt_tabs",$_POST['tab']);   
}

function wpqt_update_tab(){
    global $wpdb;
    $wpdb->update("{$wpdb->prefix}ahm_qt_tabs",$_POST['tab'],array('id'=>$_POST['id']));   
}

function wpqt_delete_tab(){
    global $wpdb;
    $wpdb->query("delete from {$wpdb->prefix}ahm_qt_tabs where id='{$_GET['id']}'");   
}

function wpqt_save_tab_group(){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}ahm_qt_tab_groups",$_POST['tg']);   
}

function wpqt_update_tab_group(){
    global $wpdb;
    $data = array('tg_tabs'=>serialize($_POST['tg_tab']));
    $wpdb->update("{$wpdb->prefix}ahm_qt_tab_groups",$data,array('id'=>$_REQUEST['tgid']));   
    die('saved');
}

function wpqt_delete_tg(){
    global $wpdb;    
    $wpdb->query("delete from {$wpdb->prefix}ahm_qt_tab_groups where id='$_GET[id]'");       
}    

function wpqt_render_tabs($tab_group){
    $tbs = unserialize($tab_group->tg_tabs);
    foreach($tbs as $tb){
           $tabs[$tb] = wpqt_get_tab($tb);
    }
    $html = do_shortcode(tab_style_1_html($tabs,$tab_group->id));     
    return $html;
}

function wpqt_frontend_tabs($atts) {
    global $wpdb; 
    extract(shortcode_atts(array(
          'tab_group' => 0,
          'tabid' => 0,
          'title' => '0',
          'desc' => '0',
     ), $atts));
     
     if((int)$tab_group>0){
     
       $_tab_group = $wpdb->get_row("select * from {$wpdb->prefix}ahm_qt_tab_groups where id='$tab_group'");               
       return wpqt_render_tabs($_tab_group);
     }
     
}