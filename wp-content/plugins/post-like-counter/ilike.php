<?php
/*
Plugin Name: I Like
Plugin URI: http://www.sealedbox.cn/
Description: Post Like Counter will tell you how many people like your post.When one post have Both 'showLikeCount' and 'showLikeLink' , this plugins will ajax update how many people like your post, also , vistor just like one post once , because this plugins count vistor by ip .
Version: 1.0
Author: jigen.he
Author URI: http://www.sealedbox.cn/


*/


// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

if (!class_exists('Ilike')) {


    class Ilike {

            /**
             * constructor
             * 
             * @author Administrator (2009-2-7)
             */
            function Ilike(){

                    $this->define_constant();

                    register_activation_hook( dirname(__FILE__) . '/ilike.php', array(&$this, 'activate') );
                    register_deactivation_hook( dirname(__FILE__) . '/ilike.php', array(&$this, 'deactivate') );   

                    // Start this plugin once all other plugins are fully loaded
                    add_action( 'plugins_loaded', array(&$this, 'start_plugin') );
            }

            /**
             * Start this plugin
             * 
             * @author Administrator (2009-2-7)
             */
            function start_plugin(){
                if ( is_admin() ) { 
                    // to do something backend
                }else{
                    // rebuild post content  media_url:url to  <object>...</object>
                    wp_enqueue_script('prototype', ILIKE_URLPATH .'prototype.js');
                    wp_enqueue_script('ilikefunction', ILIKE_URLPATH .'function.js.php');
                    require_once (dirname (__FILE__) . '/functions.php');        // 242.016
                }
            }

            function define_constant() {

                // define URL
                define('ILIKE_FOLDER', plugin_basename( dirname(__FILE__)) );
                
                define('ILIKE_ABSPATH', str_replace("\\","/", WP_PLUGIN_DIR . '/' . plugin_basename( dirname(__FILE__) ) . '/' ));
                define('ILIKE_URLPATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
        
                // get value for safe mode
                if ( (gettype( ini_get('safe_mode') ) == 'string') ) {
                    // if sever did in in a other way
                    if ( ini_get('safe_mode') == 'off' ) define('SAFE_MODE', FALSE);
                    else define( 'SAFE_MODE', ini_get('safe_mode') );
                } else
                define( 'SAFE_MODE', ini_get('safe_mode') );
                
            }

            /**
             * do something when plugins activate
             * 
             * @author Administrator (2009-2-7)
             */
            function activate() {
                    global $wpdb ;

                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                    $table = $wpdb->prefix . "ilike" ;
                    // do activate
                    if($wpdb->get_var("show tables like '$table'") != $table){
                            $q = "CREATE TABLE `$table` (
                                `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                `object_id` INT NOT NULL ,
                                `address` VARCHAR( 32 ) NOT NULL
                                ) ENGINE = MYISAM ;" ;
                            dbDelta($q);
                    }

            }
            
            /**
             * do something when plugins deactivate
             * 
             * @author Administrator (2009-2-7)
             */
            function deactivate() {
                    // do deactivate
            }

            

            

    }
    // Let's start the plugin
    global $ilike;
    $ilike = new Ilike();
}
?>