<?php
    
function tab_style_1_css(){
?>    
<style type="text/css">
.container {width: 100%; margin: 10px auto;}
ul.tabs {
    margin: 0px !important;
    padding: 0px !important;
    float: left;
    list-style: none;
    height: 32px;
    border-bottom: 1px solid #999;
    border-left: 1px solid #999;
    width: 100%;        
}
ul.tabs li {
    float: left;
    margin: 0;
    padding: 0;
    height: 31px;
    line-height: 31px;
    border: 1px solid #999;
    border-left: none;
    margin-bottom: -1px;
    background: #e0e0e0;
    overflow: hidden;
    position: relative;
}
ul.tabs li a {
    text-decoration: none;
    color: #000;
    display: block;
    font-size: 1.2em;
    padding: 0 20px;
    border: 1px solid #fff;
    outline: none;
}
ul.tabs li a:hover {
    background: #ccc;
}    
html ul.tabs li.active, html ul.tabs li.active a:hover  {
    background: #fff;
    border-bottom: 1px solid #fff;
}
.tab_container {
    border: 1px solid #999;
    border-top: none;
    clear: both;
    float: left; 
    width: 100%;
    background: #fff;
    -moz-border-radius-bottomright: 5px;
    -khtml-border-radius-bottomright: 5px;
    -webkit-border-bottom-right-radius: 5px;
    -moz-border-radius-bottomleft: 5px;
    -khtml-border-radius-bottomleft: 5px;
    -webkit-border-bottom-left-radius: 5px;
}
.tab_content {
    padding: 20px;
    font-size: 1.2em;
}
.tab_content h2 {
    font-weight: normal;
    padding-bottom: 10px;
    border-bottom: 1px dashed #ddd;
    font-size: 1.8em;
}
.tab_content h3 a{
    color: #254588;
}
.tab_content img {
    float: left;
    margin: 0 20px 20px 0;
    border: 1px solid #ddd;
    padding: 5px;
}
</style>
<?php
}

function tab_style_1_js(){
?>
<script type="text/javascript">

jQuery(document).ready(function() {

    //Default Action
    jQuery(".tab_content").hide(); //Hide all content
    jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
    jQuery(".tab_content:first").show(); //Show first tab content
    
    //On Click Event
    jQuery("ul.tabs li").click(function() {
        jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
        jQuery(this).addClass("active"); //Add "active" class to selected tab
        jQuery(".tab_content").hide(); //Hide all tab content
        var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        jQuery(activeTab).fadeIn(); //Fade in the active content
        return false;
    });

});
</script>
<?php
}

function tab_style_1_html($tabs, $group_id){
    $html = '<div class="container">    
    <ul class="tabs">        
';

  foreach($tabs as $tab){
      $thtml .= '<li><a href="#tab'.$group_id.'_'.$tab['id'].'">'.stripcslashes($tab['tab_title']).'</a></li>';
      $chtml .= '<div id="tab'.$group_id.'_'.$tab['id'].'" class="tab_content">'.stripcslashes($tab['tab_content']).'</div>';
  }
  
  $html .= $thtml . '</ul><div class="tab_container">'. $chtml . '</div></div>';
 
 return $html;

}
