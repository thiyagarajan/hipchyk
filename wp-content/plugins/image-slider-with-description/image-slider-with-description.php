<?php

/*
Plugin Name: Image slider with description
Plugin URI: http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/
Description: Image slider with description WordPress plugin is a Jquery based image slideshow script that incorporates some of your most requested features all rolled into one. Not only image this slideshow have images, title and description. We have option to enable/disable description in the slideshow.
Author: Gopi.R
Version: 3.0
Author URI: http://www.gopiplus.com/work/
Donate link: http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/
Tags: Image, slider, slideshow, description
*/

/**
 *     Image slider with description
 *     Copyright (C) 2012  www.gopiplus.com
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */	

global $wpdb, $wp_version;
define("WP_ImgSlider_TABLE", $wpdb->prefix . "ImgSlider_plugin");

function ImgSlider_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". WP_ImgSlider_TABLE . "'") != WP_ImgSlider_TABLE) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". WP_ImgSlider_TABLE . "` (";
		$sSql = $sSql . "`ImgSlider_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`ImgSlider_path` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_link` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_target` VARCHAR( 50 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_title` VARCHAR( 500 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_desc` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_order` INT NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_status` VARCHAR( 10 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_type` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_extra1` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_extra2` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ImgSlider_date` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "PRIMARY KEY ( `ImgSlider_id` )";
		$sSql = $sSql . ")";
		$wpdb->query($sSql);
		
		$IsSql = "INSERT INTO `". WP_ImgSlider_TABLE . "` (`ImgSlider_path`, `ImgSlider_link`, `ImgSlider_target` , `ImgSlider_title` , `ImgSlider_desc` , `ImgSlider_order` , `ImgSlider_status` , `ImgSlider_type` , `ImgSlider_date`)"; 
		
		$siteurl_link = get_option('siteurl') . "/";
		for($i=1; $i<=3; $i++)
		{
			$sSql = $IsSql . " VALUES ('$siteurl_link/wp-content/plugins/image-slider-with-description/sample/336x280_$i.jpg', '#', '_blank', 'This is image title $i', 'This is image desc $i', '$i', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
		}
		for($i=1; $i<=2; $i++)
		{
			$sSql = $IsSql . " VALUES ('$siteurl_link/wp-content/plugins/image-slider-with-description/sample/600x400_$i.jpg', '#', '_blank', 'This is image title $i', 'This is image desc $i', '$i', 'YES', 'GROUP2', '0000-00-00 00:00:00');";
			$wpdb->query($sSql);
		}
	}

	add_option('ImgSlider_option_1', "336-3-280-000000-000000-1-1-0-0-0-green-slide-600");
	add_option('ImgSlider_option_2', "600-3-400-000000-000000-1-1-0-0-0-yellow-fade-700");
}

function ImgSlider_admin_options() 
{
	global $wpdb;
	echo "<div class='wrap'>";
	echo "<h2>"; 
	echo "Image slider with description";
	echo "</h2>";
	
	if (@$_POST['ImgSlider_submit']) 
	{
		$a = stripslashes($_POST['ImgSlider_sliderWidth']);
		$b = stripslashes($_POST['ImgSlider_borderWidth']);
		$c = stripslashes($_POST['ImgSlider_sliderHeight']);
		$d = stripslashes($_POST['ImgSlider_backgroundColor']);
		$e = stripslashes($_POST['ImgSlider_descColor']);
		$f = stripslashes($_POST['ImgSlider_showButtons']);
		$g = stripslashes($_POST['ImgSlider_showNames']);
		$h = stripslashes($_POST['ImgSlider_showDesc']);
		$i = stripslashes($_POST['ImgSlider_showLink']);
		@$j = stripslashes(@$_POST['ImgSlider_linkNewWindow']);
		$k = stripslashes($_POST['ImgSlider_buttonColor']);
		$l = stripslashes($_POST['ImgSlider_animation']);
		$m = stripslashes($_POST['ImgSlider_fadeSpeed']);
		
		$a1 = stripslashes($_POST['ImgSlider_sliderWidth_1']);
		$b1 = stripslashes($_POST['ImgSlider_borderWidth_1']);
		$c1 = stripslashes($_POST['ImgSlider_sliderHeight_1']);
		$d1 = stripslashes($_POST['ImgSlider_backgroundColor_1']);
		$e1 = stripslashes($_POST['ImgSlider_descColor_1']);
		$f1 = stripslashes($_POST['ImgSlider_showButtons_1']);
		$g1 = stripslashes($_POST['ImgSlider_showNames_1']);
		$h1 = stripslashes($_POST['ImgSlider_showDesc_1']);
		$i1 = stripslashes($_POST['ImgSlider_showLink_1']);
		@$j1 = stripslashes(@$_POST['ImgSlider_linkNewWindow_1']);
		$k1 = stripslashes($_POST['ImgSlider_buttonColor_1']);
		$l1 = stripslashes($_POST['ImgSlider_animation_1']);
		$m1 = stripslashes($_POST['ImgSlider_fadeSpeed_1']);

		$ImgSlider_option_1 = $a."-".$b."-".$c."-".$d."-".$e."-".$f."-".$g."-".$h."-".$i."-".$j."-".$k."-".$l."-".$m;
		$ImgSlider_option_2 = $a1."-".$b1."-".$c1."-".$d1."-".$e1."-".$f1."-".$g1."-".$h1."-".$i1."-".$j1."-".$k1."-".$l1."-".$m1;

		update_option('ImgSlider_option_1', $ImgSlider_option_1 );
		update_option('ImgSlider_option_2', $ImgSlider_option_2 );
	}
	
	$ImgSlider_option_1 = get_option('ImgSlider_option_1');
	$ImgSlider_option_2 = get_option('ImgSlider_option_2');
	
	list($ImgSlider_sliderWidth, $ImgSlider_borderWidth, $ImgSlider_sliderHeight, $ImgSlider_backgroundColor, $ImgSlider_descColor, $ImgSlider_showButtons, $ImgSlider_showNames,$ImgSlider_showDesc,$ImgSlider_showLink,$ImgSlider_linkNewWindow,$ImgSlider_buttonColor,$ImgSlider_animation,$ImgSlider_fadeSpeed) = explode("-", $ImgSlider_option_1);
	list($ImgSlider_sliderWidth_1, $ImgSlider_borderWidth_1, $ImgSlider_sliderHeight_1, $ImgSlider_backgroundColor_1, $ImgSlider_descColor_1, $ImgSlider_showButtons_1, $ImgSlider_showNames_1,$ImgSlider_showDesc_1,$ImgSlider_showLink_1,$ImgSlider_linkNewWindow_1,$ImgSlider_buttonColor_1,$ImgSlider_animation_1,$ImgSlider_fadeSpeed_1) = explode("-", $ImgSlider_option_2);

	echo '<form name="ImgSlider_form" method="post" action="">';
	
	echo '<table width="80%" border="0" cellspacing="0" cellpadding="0">';
	
	echo '<tr><td style="width:50%;"><strong>Setting 1</strong></td><td style="width:50%;"><strong>Setting 2</strong></td></tr>';
	echo '<tr><td style="width:50%;">';
	echo '<p>Slider Width :<br><input  style="width: 150px;" maxlength="3" type="text" value="';
	echo $ImgSlider_sliderWidth . '" name="ImgSlider_sliderWidth" id="ImgSlider_sliderWidth" /> (only number)</p>';

	echo '<p>Border Width :<br><input  style="width: 150px;" maxlength="2" type="text" value="';
	echo $ImgSlider_borderWidth . '" name="ImgSlider_borderWidth" id="ImgSlider_borderWidth" /> (only number)</p>';

	echo '<p>Slider Height :<br><input  style="width: 150px;" type="text" maxlength="3" value="';
	echo $ImgSlider_sliderHeight . '" name="ImgSlider_sliderHeight" id="ImgSlider_sliderHeight" /> (only number)</p>';

	echo '<p>Background Color :<br><input  style="width: 150px;" type="text" maxlength="6" value="';
	echo $ImgSlider_backgroundColor . '" name="ImgSlider_backgroundColor" id="ImgSlider_backgroundColor" /> (color code without #)</p>';
	
	echo '<p>Description Text Color :<br><input  style="width: 150px;" maxlength="6" type="text" value="';
	echo $ImgSlider_descColor . '" name="ImgSlider_descColor" id="ImgSlider_descColor" /> (color code without #)</p>';
	
	echo '<p>Show Button :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showButtons . '" name="ImgSlider_showButtons" id="ImgSlider_showButtons" /> (0/1)</p>';
	
	echo '<p>Show Name :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showNames . '" name="ImgSlider_showNames" id="ImgSlider_showNames" /> (0/1)</p>';
	
	echo '<p>Show Description :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showDesc . '" name="ImgSlider_showDesc" id="ImgSlider_showDesc" /> (0/1)</p>';
	
	echo '<p>Show Link :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showLink . '" name="ImgSlider_showLink" id="ImgSlider_showLink" /> (0/1)</p>';
	
	//echo '<p>Link New Window :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	//echo $ImgSlider_linkNewWindow . '" name="ImgSlider_linkNewWindow" id="ImgSlider_linkNewWindow" /> (0/1)</p>';
	
	echo '<p>Button Color :<br><input  style="width: 150px;" type="text" maxlength="8" value="';
	echo $ImgSlider_buttonColor . '" name="ImgSlider_buttonColor" id="ImgSlider_buttonColor" /> (green/yellow/brick/pink/purple/white)</p>';
	
	echo '<p>Plugin Animation :<br><input  style="width: 150px;" maxlength="5" type="text" value="';
	echo $ImgSlider_animation . '" name="ImgSlider_animation" id="ImgSlider_animation" /> (slide/fade)</p>';
	
	echo '<p>Fade Speed :<br><input  style="width: 150px;" maxlength="3" type="text" value="';
	echo $ImgSlider_fadeSpeed . '" name="ImgSlider_fadeSpeed" id="ImgSlider_fadeSpeed" /> (only number)</p>';
	echo '</td>';
	
	echo '<td style="width:50%;">';
	echo '<p>Slider Width :<br><input  style="width: 150px;" maxlength="3" type="text" value="';
	echo $ImgSlider_sliderWidth_1 . '" name="ImgSlider_sliderWidth_1" id="ImgSlider_sliderWidth_1" /> (only number)</p>';

	echo '<p>Border Width :<br><input  style="width: 150px;" maxlength="2" type="text" value="';
	echo $ImgSlider_borderWidth_1 . '" name="ImgSlider_borderWidth_1" id="ImgSlider_borderWidth_1" /> (only number)</p>';

	echo '<p>Slider Height :<br><input  style="width: 150px;" type="text" maxlength="3" value="';
	echo $ImgSlider_sliderHeight_1 . '" name="ImgSlider_sliderHeight_1" id="ImgSlider_sliderHeight_1" /> (only number)</p>';

	echo '<p>Background Color :<br><input  style="width: 150px;" type="text" maxlength="6" value="';
	echo $ImgSlider_backgroundColor_1 . '" name="ImgSlider_backgroundColor_1" id="ImgSlider_backgroundColor_1" /> (color code without #)</p>';
	
	echo '<p>Description Text Color :<br><input  style="width: 150px;" type="text" maxlength="6" value="';
	echo $ImgSlider_descColor_1 . '" name="ImgSlider_descColor_1" id="ImgSlider_descColor_1" /> (color code without #)</p>';
	
	echo '<p>Show Button :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showButtons_1 . '" name="ImgSlider_showButtons_1" id="ImgSlider_showButtons_1" /> (0/1)</p>';
	
	echo '<p>Show Name :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showNames_1 . '" name="ImgSlider_showNames_1" id="ImgSlider_showNames_1" /> (0/1)</p>';
	
	echo '<p>Show Description :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showDesc_1 . '" name="ImgSlider_showDesc_1" id="ImgSlider_showDesc_1" /> (0/1)</p>';
	
	echo '<p>Show Link :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	echo $ImgSlider_showLink_1 . '" name="ImgSlider_showLink_1" id="ImgSlider_showLink_1" /> (0/1)</p>';
	
	//echo '<p>Link New Window :<br><input  style="width: 150px;" maxlength="1" type="text" value="';
	//echo $ImgSlider_linkNewWindow_1 . '" name="ImgSlider_linkNewWindow_1" id="ImgSlider_linkNewWindow_1" /> (0/1)</p>';
	
	echo '<p>Button Color :<br><input  style="width: 150px;" type="text" maxlength="8" value="';
	echo $ImgSlider_buttonColor_1 . '" name="ImgSlider_buttonColor_1" id="ImgSlider_buttonColor_1" /> (green/yellow/brick/pink/purple/white)</p>';
	
	echo '<p>Plugin Animation :<br><input  style="width: 150px;" type="text" maxlength="5" value="';
	echo $ImgSlider_animation_1 . '" name="ImgSlider_animation_1" id="ImgSlider_animation_1" /> (slide/fade)</p>';
	
	echo '<p>Fade Speed :<br><input  style="width: 150px;" type="text" maxlength="3" value="';
	echo $ImgSlider_fadeSpeed_1 . '" name="ImgSlider_fadeSpeed_1" id="ImgSlider_fadeSpeed_1" /> (only number)</p>';
	echo '</td></tr>';
	
	echo '</table>';
	echo '*Note : 1 = Enable, 0 = Disable<br><br>';
	
	echo '<input name="ImgSlider_submit" id="ImgSlider_submit" class="button-primary" value="Save Both Image slider Setting" type="submit" />';

	echo '</form>';
	echo '<br>Note: Use the short code to add the gallery in to the posts and pages.<br />';
	echo '<br>Check official website <a target="_blank" href="http://www.gopiplus.com/work/2011/11/04/wordpress-plugin-image-slider-with-description/">www.gopiplus.com</a> for live demo and more info.<br>';
	echo '</div><br>';
}

add_filter('the_content','ImgSlider_Show_Filter');

function ImgSlider_Show_Filter($content)
{
	return 	preg_replace_callback('/\[IMG-SLIDER-DESC:(.*?)\]/sim','ImgSlider_Show_Filter_Callback',$content);
}

function ImgSlider_Show_Filter_Callback($matches) 
{
	global $wpdb;
	
	$Slider_Img = "";
	$Slider_Desc = "";
	$Slider = "";
	
	$scode = $matches[1];
	//[IMG-SLIDER-DESC:SETTING=1:GROUP=GROUP1]
	//echo $scode;
	list($setting_main, $group_main) = split("[:.-]", $scode);
	
	list($setting_cap, $setting) = split('[=.-]', $setting_main);
	list($group_cap, $group) = split('[=.-]', $group_main);
	
	if($setting == "1")
	{
		$ImgSlider_Setting = get_option('ImgSlider_option_1');
	}
	else
	{
		$ImgSlider_Setting = get_option('ImgSlider_option_2');
	}
	
	$set = explode("-", $ImgSlider_Setting);
	
	if($group == "")
	{
		$group = "GROUP1";	
	}
	
	$sSql = "select * from ".WP_ImgSlider_TABLE." where 1=1";
	if($group <> ""){ $sSql = $sSql . " and ImgSlider_type='".$group."'"; }
	if(@$Slider_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY ImgSlider_order"; }
	
	$data = $wpdb->get_results($sSql);
	
	$Slider_count = 1;
	$Slider_Img = "";
	if ( ! empty($data) ) 
	{
		foreach ( $data as $data ) 
		{ 
			 $ImgSlider_path = $data->ImgSlider_path;
			 $ImgSlider_link = $data->ImgSlider_link;
			 $ImgSlider_target = $data->ImgSlider_target;
			 $ImgSlider_path = $data->ImgSlider_path;
			 $ImgSlider_title = $data->ImgSlider_title;
			 @$ImgSlider_desc = @$data->ImgSlider_desc;
			 
			 if($ImgSlider_link <> "")
			 {
				 $Slider_Img = $Slider_Img . '<a href="'.$ImgSlider_link.'" target="'.$ImgSlider_target.'">';
			 }
			 
			 $Slider_Img = $Slider_Img . '<img id="slide-img-'.$Slider_count.'" src="'.$ImgSlider_path.'" class="slide" alt="'.$ImgSlider_title.'">';
			 
			 if($ImgSlider_link <> "")
			 {
			 	$Slider_Img = $Slider_Img . '</a>';
			 }
			 
			 $Slider_Desc = $Slider_Desc . '{"id":"slide-img-'.$Slider_count.'","client":"'.$ImgSlider_title.'","desc":"'.$ImgSlider_desc.'"},';
			 
			 $Slider_count = $Slider_count + 1;
		}	
		$Slider_Desc = substr($Slider_Desc,0,(strlen($Slider_Desc)-1));
	}
	
	$sliderWidth = $set[0];
	$borderWidth = $set[1];
	$sliderWidth2 = $sliderWidth - $borderWidth;
	$borderWidth2 = $borderWidth/2;
	$sliderHeight = $set[2];
	$sliderHeight2 = $sliderHeight - $borderWidth;
	$backgroundColor = $set[3];
	$descColor = $set[4];
	$showButtons = $set[5];
	$showNames = $set[6];
	$showDesc = $set[7];
	$showLink = $set[8];
	$linkNewWindow = $set[9];
	$buttonColor = $set[10];
	$animation   = $set[11];
	$fadeSpeed   = $set[12];
	
	if ($showButtons==0) { $showButtonsDisplay = 'display:none;'; }else{ $showButtonsDisplay = ''; }
	if ($buttonColor=="white") { $buttonColorValue = "666"; }else{ $buttonColorValue = "fff"; }

$ssg_pluginurl = get_option('siteurl') . "/wp-content/plugins/image-slider-with-description/";

$Slider = $Slider .'<link rel="stylesheet" href="'.$ssg_pluginurl.'style/style.css" type="text/css" />';
	
$Slider = $Slider . '<style type="text/css"> ';
$Slider = $Slider . 'div.wrap { ';
$Slider = $Slider . 'width:'.$sliderWidth.'px; ';
$Slider = $Slider . 'margin:0 auto; ';
$Slider = $Slider . 'text-align:left; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#top div#nav { ';
$Slider = $Slider . 'float:left; ';
$Slider = $Slider . 'clear:both; ';
$Slider = $Slider . 'width:'.$sliderWidth.'px; ';
$Slider = $Slider . 'height:52px; ';
$Slider = $Slider . 'margin:22px 0 0; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div.wrap { ';
$Slider = $Slider . 'height:'.$sliderHeight.'px; ';
$Slider = $Slider . 'background:#'.$backgroundColor.'; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder { ';
$Slider = $Slider . 'width:'.$sliderWidth.'px; ';
$Slider = $Slider . 'height:'.$sliderHeight.'px; ';
$Slider = $Slider . 'position:absolute; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-runner { ';
$Slider = $Slider . 'top:'.$borderWidth2.'px; ';
$Slider = $Slider . 'left:'.$borderWidth2.'px; ';
$Slider = $Slider . 'width:'.$sliderWidth2.'px; ';
$Slider = $Slider . 'height:'.$sliderHeight2.'px; ';
$Slider = $Slider . 'overflow:hidden; ';
$Slider = $Slider . 'position:absolute; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-controls { ';
$Slider = $Slider . 'left:0; ';
$Slider = $Slider . 'top:10px; ';
$Slider = $Slider . 'width:'.$sliderWidth2.'px; ';
$Slider = $Slider . 'height:46px; ';
$Slider = $Slider . 'display:none; ';
$Slider = $Slider . 'position:absolute; ';
$Slider = $Slider . 'background:url('.$ssg_pluginurl.'images/slide-bg.png) 0 0; ';
$Slider = $Slider . '}';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-controls div#slide-nav { ';
$Slider = $Slider . 'float:right; ';
$Slider = $Slider . $showButtonsDisplay;
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'p.textdesc { ';
$Slider = $Slider . 'float:left; ';
$Slider = $Slider . 'color:#fff; ';
$Slider = $Slider . 'display:inline; ';
$Slider = $Slider . 'font-size:10px; ';
$Slider = $Slider . 'line-height:16px; ';
$Slider = $Slider . 'margin:15px 0 0 20px; ';
$Slider = $Slider . 'text-transform:uppercase; ';
$Slider = $Slider . 'overflow:hidden; ';
$Slider = $Slider . 'color:#'.$descColor.'; ';
$Slider = $Slider . '} ';
	
$Slider = $Slider . 'div#header_hotslider div#slide-holder div#slide-controls div#slide-nav a { ';
$Slider = $Slider . 'background-image:url('.$ssg_pluginurl.'images/slide-nav-'.$buttonColor.'.png); ';
$Slider = $Slider . 'color:#'.$buttonColorValue.'; ';
$Slider = $Slider . 'top:11px; ';
$Slider = $Slider . 'position:relative; ';
$Slider = $Slider . '} ';
$Slider = $Slider . '</style> ';

$Slider = $Slider . '<div id="header_hotslider">';
    $Slider = $Slider . '<div class="wrap">';
      $Slider = $Slider . '<div id="slide-holder">';
        $Slider = $Slider . '<div id="slide-runner">'; 
		$Slider = $Slider . $Slider_Img;
          $Slider = $Slider . '<div id="slide-controls">';
            $Slider = $Slider . '<div id="slide-nav"></div>';
			if ($showNames!=0) 
			{
            	$Slider = $Slider . '<p id="slide-client" class="text"><span></span></p>';
			}
			if ($showDesc!=0) 
			{
            	$Slider = $Slider . '<div style="clear:both"></div>';
     			$Slider = $Slider . '<p id="slide-desc" class="textdesc"></p>';
			}
          $Slider = $Slider . '</div>';
        $Slider = $Slider . '</div>';
      $Slider = $Slider . '</div>';
      $Slider = $Slider . '<script type="text/javascript">';
    $Slider = $Slider . 'if(!window.slider) var slider={};slider.anim="'.$animation.'";slider.fade_speed='.$fadeSpeed.';slider.data=['.$Slider_Desc.'];';
   $Slider = $Slider . '</script> ';
    $Slider = $Slider . '</div>';
  $Slider = $Slider . '</div>';

return $Slider;
}

function ImgSlider_deactivation() 
{

}

function ImgSlider_image_management() 
{
	global $wpdb;
	include_once("image-management.php");
}


function FadeIn_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'jquery.min', get_option('siteurl').'/wp-content/plugins/image-slider-with-description/js/jquery.min.js');
		wp_enqueue_script( 'ImgSlider.scripts', get_option('siteurl').'/wp-content/plugins/image-slider-with-description/js/scripts.js');
	}	
}

add_action('init', 'FadeIn_add_javascript_files');

function add_admin_menu_option() 
{
	add_menu_page( __( 'Image Slider', 'ImgSlider' ), __( 'Image Slider', 'ImgSlider' ), 'administrator', 'ImgSlider', 'ImgSlider_admin_options' );
	add_submenu_page( 'ImgSlider', __( 'Slider Setting', 'ImgSlider' ), __( 'Slider Setting', 'ImgSlider' ),'administrator', 'ImgSlider', 'ImgSlider_admin_options' );
	add_submenu_page( 'ImgSlider', __( 'Image Management', 'ImgSlider' ), __( 'Image Management', 'ImgSlider' ),'administrator', 'ImgSlider_image_management', 'ImgSlider_image_management' );
}

add_action('admin_menu', 'add_admin_menu_option');
register_activation_hook(__FILE__, 'ImgSlider_install');
register_deactivation_hook(__FILE__, 'ImgSlider_deactivation');
?>
