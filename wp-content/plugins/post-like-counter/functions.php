<?php

/**
 * Show How Many People Like You Post
 * 
 * @author jigen.he (2009-2-13)
 * 
 * @param $objectID 
 */
function showLikeCount($objectID,$showtags = true){


        global $wpdb ;

        $table = $wpdb->prefix . "ilike" ;

        if(!$objectID)
                return ;
        
        $count = $wpdb->get_var("SELECT count(*) FROM `$table` WHERE `object_id` = $objectID");
        $out = '<span id="ilike' . $objectID . '">' . $count . '</span>' ;
        return $showtags?$out:$count;
}

/**
 * use at front page , where you want vistor to look at 
 * 
 * @author jigen.he (2009-2-14)
 * 
 * @param $objectID 
 */
function showLikeLink($objectID){
        $out = '<a href="javascript:submitILike(' . $objectID . ')" />I like it</a>';
        return $out ;
}


/**
 * deal ajax post , 
 */
if($_POST['id']){
        $file = str_replace('\\','/',__FILE__);
        $file = explode('wp-content',$file);
        $file = $file[0];
        
        require_once($file.'wp-config.php');
        require_once($file.'wp-includes/wp-db.php');
        
        $wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
        
        $ip = $_SERVER['REMOTE_ADDR'] ;
        
        $id = $_POST['id'] ;

        $table = $table_prefix . "ilike" ;

        $count = $wpdb->get_var("SELECT count(*) FROM `$table` WHERE `object_id` = $id and `address`='$ip'");
        if($count != 0){
                echo "You have submitted yet! Please don't submit again ." ;
                exit;
        }
        $wpdb->query("INSERT INTO `$table` (object_id,address)VALUES($id,'$ip')");
}


?>
