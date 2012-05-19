<?php


 
add_filter('mce_external_plugins', "wpqt_tinyplugin_register");
add_filter('mce_buttons', 'wpqt_tinyplugin_add_button', 0);
 
function wpqt_tinyplugin_add_button($buttons)
{
    array_push($buttons, "separator", "wpqt_tinyplugin");
    return $buttons;
}

function wpqt_tinyplugin_register($plugin_array)
{
    $url = plugins_url()."/quick-tabs/js/editor_plugin.js";

    $plugin_array['wpqt_tinyplugin'] = $url;
    return $plugin_array;
}


function wpqt_tinymce_button(){
    global $wpdb;
    if($_GET['execute']!='wpqt_tinymce_button') return false;
    ?>
<html>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title>Download Manager &#187; Insert Package or Category</title>
<style type="text/css">
*{font-family: Tahoma !important; font-size: 9pt; letter-spacing: 1px;}
select,input{padding:5px;font-size: 9pt !important;font-family: Tahoma !important; letter-spacing: 1px;margin:5px;}
.button{
    background: #7abcff; /* old browsers */

background: -moz-linear-gradient(top, #7abcff 0%, #60abf8 44%, #4096ee 100%); /* firefox */

background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#7abcff), color-stop(44%,#60abf8), color-stop(100%,#4096ee)); /* webkit */

filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7abcff', endColorstr='#4096ee',GradientType=0 ); /* ie */
-webkit-border-radius: 4px;
-moz-border-radius: 4px;
border-radius: 4px;
border:1px solid #FFF;
color: #FFF;
}
 
.input{
 width: 340px;   
 background: #EDEDED; /* old browsers */

background: -moz-linear-gradient(top, #EDEDED 24%, #fefefe 81%); /* firefox */

background: -webkit-gradient(linear, left top, left bottom, color-stop(24%,#EDEDED), color-stop(81%,#fefefe)); /* webkit */

filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#EDEDED', endColorstr='#fefefe',GradientType=0 ); /* ie */
border:1px solid #aaa; 
color: #000;
}
.button-primary{cursor: pointer;}
fieldset{padding: 10px;}
</style> 
</head>
<body>    <br>

<fieldset><legend>Insert Tabs</legend>
    <select class="button input" id="fl">
    <?php
    $res = $wpdb->get_results("select * from {$wpdb->prefix}ahm_qt_tab_groups", ARRAY_A); 
    foreach($res as $row){
    ?>
    
    <option value="<?php echo $row['id']; ?>"><?php echo $row['tg_name']; ?></option>
    
    
    <?php    
        
    }
?>
    </select>
    <input type="submit" id="addtopost" class="button button-primary" name="addtopost" value="Insert into post" />
</fieldset>   <br>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo home_url('/wp-includes/js/tinymce/tiny_mce_popup.js'); ?>"></script>
                <script type="text/javascript">
                    /* <![CDATA[ */                    
                    jQuery('#addtopost').click(function(){
                    var win = window.dialogArguments || opener || parent || top;                
                    win.send_to_editor('[quick-tabs tab_group='+$('#fl').val()+']');
                    tinyMCEPopup.close();
                    return false;                   
                    });
                      
                              
                    /* ]]> */
                </script>

</body>    
</html>
    
    <?php
    
    die();
}
 

add_action('init', 'wpqt_tinymce_button');

