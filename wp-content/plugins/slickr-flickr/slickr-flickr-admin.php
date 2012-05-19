<?php
/*
Author: Russell Jamieson
Author URI: http://www.russelljamieson.com
Copyright &copy; 2010-2011 &nbsp; Russell Jamieson
*/

define('SLICKR_FLICKR_ADMIN', 'slickr-flickr-admin');
$slickr_flickr_admin = new slickr_flickr_admin();

//class that reperesent the complete plugin
class slickr_flickr_admin {

    private $pagehook;

	function __construct() {
		add_filter('screen_layout_columns', array(&$this, 'screen_layout_columns'), 10, 2);
		add_action('admin_menu', array(&$this, 'admin_menu')); 
		add_filter( 'plugin_action_links',array(&$this, 'plugin_action_links'), 10, 2 );
	}

	function screen_layout_columns($columns, $screen) {
		if (!defined( 'WP_NETWORK_ADMIN' ) && !defined( 'WP_USER_ADMIN' )) {
			if ($screen == $this->pagehook) {
				$columns[$this->pagehook] = 2;
			}
		}
		return $columns;
	}

	function admin_menu() {
		$this->pagehook = add_options_page('Slickr Flickr', 'Slickr Flickr', 'manage_options', SLICKR_FLICKR_ADMIN, array(&$this, 'options_panel'));
		add_action('load-'.$this->pagehook, array(&$this, 'load_page'));
		add_action('admin_head-'.$this->pagehook, array(&$this, 'load_style'));
		add_action('admin_footer-'.$this->pagehook, array(&$this, 'load_script'));		
		add_action('admin_footer-'.$this->pagehook, array(&$this, 'toggle_postboxes'));
	}

	function load_style() {
    	echo ('<link rel="stylesheet" id="slickr-flickr-admin" href="'.SLICKR_FLICKR_PLUGIN_URL.'/slickr-flickr-admin.css?ver='.SLICKR_FLICKR_VERSION.'" type="text/css" media="all" />');
 	}

	function load_script() {
    	echo('<script type="text/javascript" src="'.SLICKR_FLICKR_PLUGIN_URL.'/slickr-flickr-admin.js?ver='.SLICKR_FLICKR_VERSION.'"></script>');    
	}	

	function plugin_action_links( $links, $file ) {
		if ( is_array($links) && (SLICKR_FLICKR_PATH == $file )) {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page='.SLICKR_FLICKR_ADMIN) . '">Settings</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	function load_page() {
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');	
		add_meta_box('slickr-flickr-identity', __('Flickr Identity',SLICKR_FLICKR), array(&$this, 'id_panel'), $this->pagehook, 'normal', 'core');
		add_meta_box('slickr-flickr-general', __('Display Options',SLICKR_FLICKR), array(&$this, 'general_panel'), $this->pagehook, 'normal', 'core');
		add_meta_box('slickr-flickr-lightbox', __('LightBox Options',SLICKR_FLICKR), array(&$this, 'lightbox_panel'), $this->pagehook, 'normal', 'core');
		add_meta_box('slickr-flickr-galleria', __('Galleria Options',SLICKR_FLICKR), array(&$this, 'galleria_panel'), $this->pagehook, 'normal', 'core');
		add_meta_box('slickr-flickr-advanced', __('Advanced Options',SLICKR_FLICKR), array(&$this, 'advanced_panel'), $this->pagehook, 'normal', 'core');
		add_meta_box('slickr-flickr-pro', __('Pro Options',SLICKR_FLICKR), array(&$this, 'pro_panel'), $this->pagehook, 'normal', 'core');
		add_meta_box('slickr-flickr-help', __('Help',SLICKR_FLICKR), array(&$this, 'help_panel'), $this->pagehook, 'side', 'core');
		add_meta_box('slickr-flickr-cache', __('Caching',SLICKR_FLICKR), array(&$this, 'cache_panel'), $this->pagehook, 'side', 'core');
		add_meta_box('slickr-flickr-lightboxes', __('Compatible LightBoxes',SLICKR_FLICKR), array(&$this, 'lightboxes_panel'), $this->pagehook, 'side', 'core');
		$current_screen = get_current_screen();
		if (method_exists($current_screen,'add_help_tab')) {
			$current_screen->add_help_tab( array( 'id' => 'slickr_flickr_overview', 'title' => 'Overview', 		
				'content' => '<p>This admin screen is used to configure your Flickr settings, set display defaults, and choose which LightBox and version of the Galleria /theme you wish to use with Slickr Flickr.</p>'));	
			$current_screen->add_help_tab( array( 'id' => 'slickr_flickr_troubleshooting', 'title' => 'Troubleshooting', 		
				'content' => '<p>Make sure you only have one version of jQuery installed, and have a single LightBox activated otherwise you may have conflicts. For best operation your page should not have any JavaScript errors. Some Javascript conflicts are removed by loading Slickr Flickr in the footer (see Advanced Options)</p>
				<p>For help go to <a href="http://www.slickrflickr.com/slickr-flickr-help/">Slickr Flickr Help</a> or for priority support upgrade to <a href="http://www.slickrflickr.com/upgrade/">Slickr Flickr Pro</a></p>'));	
		}
	}

   	function clear_cache() {
   		slickr_flickr_clear_cache();
   		$class = "updated fade";
   		$message = __("WordPress RSS cache has been cleared successfully",SLICKR_FLICKR);
   		return '<div id="message" class="' . $class .' "><p>' . $message. '</p></div>';
   }

	function save() {
		check_admin_referer(SLICKR_FLICKR_ADMIN);
  		$options = explode(',', stripslashes($_POST['page_options']));
  		if ($options) {
  			$flickr_options = array();
  			$slickr_options = array();
  			$updateslic = false; $updates = false; $updatespro = false;
			$pro_options = slickr_flickr_pro_get_options(false);  
    		// retrieve option values from POST variables
    		foreach ($options as $option) {
       			$option = trim($option);
       			$val = array_key_exists($option, $_POST) ? trim(stripslashes($_POST[$option])) : '';
       			if (substr($option,0,7) == 'flickr_')
    				$flickr_options[$option] = $val;
       			else {
 					if ($option == 'slickr_licence') { 
 			  			$updateslic = SlickrFlickrUpdater::save_licence($val);
 			  		} else {
          				$old_value = $pro_options[substr($option,7)];
          				$slickr_options[$option] = $val;
              			if ($slickr_options[$option] == md5($old_value)) $slickr_options[$option] = $old_value;
					}
	    		}
    		} //end for

   			$updates =  update_option("slickr_flickr_options", $flickr_options) ;
   			$updatespro = update_option("slickr_flickr_pro_options", $slickr_options);
  		    $class="updated fade";
   			if ($updates || $updatespro || $updateslic)  {
				slickr_flickr_get_options(false); //update cache
				slickr_flickr_pro_get_options(false); //update cache
       			$message = __("Slickr Flickr Settings saved.",SLICKR_FLICKR_ADMIN);
   			} else
       			$message = __("No Slickr Flickr settings were changed since last update.",SLICKR_FLICKR_ADMIN);
  		} else {
  		    $class="error";
       		$message= "Slickr Flickr settings not found!";
  		}
  		return '<div id="message" class="' . $class .' "><p>' . $message. '</p></div>';
	}

	function pro_panel ($post, $metabox) {
		$options = slickr_flickr_get_options();		
		$pro_options = slickr_flickr_pro_get_options();
		$licence = SlickrFlickrUpdater::get_licence();
		$is_pro = false;
		$key_status_indicator ='';
		$notice ='';
		if (! empty($licence)) {
   			$is_pro = SlickrFlickrUpdater::check_validity();
   			$flag = $is_pro ? 'tick' : 'cross';
    		$expiry = SlickrFlickrUpdater::get_expiry(); 
    		if (!empty($expiry)) $expiry = 'License Expiry : '.$expiry;   			
    		$key_status_indicator = '<img src="' . SLICKR_FLICKR_PLUGIN_URL .'/images/'.$flag.'.png" alt="a '.$flag.'" />&nbsp;'.$expiry;
   			$notice = $is_pro ? '': ('<p>'.SlickrFlickrUpdater::get_notice().'</p>');
  		}
		$consumer_secret = md5($pro_options['consumer_secret']);
		$token = md5($pro_options['token']);
		$token_secret = md5($pro_options['token_secret']);
        $readonly = $is_pro ? '' : 'readonly="readonly" class="readonly"';
        $home = SLICKR_FLICKR_HOME;
        $pro = SLICKR_FLICKR_PRO;
		print <<< PRO_PANEL
<h4>Slickr Flickr Pro Licence Key: <input name="slickr_licence" id="slickr_licence" type="password" style="width:320px" value="{$licence}" />&nbsp;{$key_status_indicator}</h4>
{$notice}
<p>The Slickr Flickr Pro Licence Key is required if you want to get support through the <a href="{$pro}/forum/">Slickr Flickr Pro Forums</a> and 
also use some of the <a href="{$home}/pro/">Slickr Flickr Pro Bonus features</a>.</p>
<h4>Flickr API Secret: <input name="slickr_consumer_secret" id="slickr_consumer_secret" type="password" style="width:200px" {$readonly} value="{$consumer_secret}" /></h4>
<p>The Flickr API Secret is required if you want to make authenticated requests to Flickr.</p>
<p>This is the secret key that is paired with your API key and can be found by logging in to Flickr and then visiting <a href="http://www.flickr.com/services/api/keys/">Flickr API Keys</a>.</p>
<h4> Flickr Authentication Token: <input name="slickr_token" id="slickr_token" type="password" style="width:480px" {$readonly} value="{$token}" /></h4>
<p>The Flickr Authentication Token is required if you want to be able to fetch private photos.</p>
<p>The token must be set up with READ permission to access your private photos.</p>
<p>Please log in to your <a href="{$pro}">Slickr Flickr Pro Dashboard</a> for instructions on requesting a Flickr Authentication Token.</p>
<p>In the next version of Slickr Flickr I will add a "Verify Token" on this page so you can easily check that all the secrets codes have been copied correctly.</p>
<h4>Flickr Authentication Token Secret: <input name="slickr_token_secret" id="slickr_token_secret" type="password" style="width:200px" {$readonly} value="{$token_secret}" /></h4>
<p>The Flickr Authentication Token Secret is required if you want to be able to fetch private photos. Follow the instructions above to obtain your token secret</p>
<h4>Thumbnail Border Color: <input name="flickr_thumbnail_border" id="flickr_thumbnail_border" type="text" {$readonly} value="{$options['thumbnail_border']}" /></h4>
<p>If you want to set a default thumbnail border color then supply the color as a hex code preceded by a #. e.g Red is #FF0000</p>
<h4>Slideshow Transition Time: <input name="flickr_transition" id="flickr_transition" type="text" {$readonly} value="{$options['transition']}" /></h4>
<p>If you leave this blank then the plugin will take half a second to fade one slide into the next.</p>
<p>If you supply a number it here, the plugin will remember it so you do not need to supply it for every slideshow.</p>
<p>You are still able to supply a different delay for individual slideshow by specifying it in the post</p>
<p>For example [slickr-flickr tag="bahamas" transition="2"] displays a slideshow with a 2 second fade transition between slides</p>
PRO_PANEL;
	}

	function id_panel($post, $metabox) {		
		$options = slickr_flickr_get_options();		
		$is_user = $options['group']!='y'?'selected="selected"':"";
		$is_group = $options['group']=='y'?'selected="selected"':"";
		print <<< ID_PANEL
<h4>Flickr ID</h4>
<p>The Flickr ID is required for you to be able to access your photos.</p>
<p>If you supply it here, the plugin will remember it so you do not need to supply it for every gallery and every slideshow.</p>
<p>You are still able to supply a Flickr ID for an individual slideshow perhaps where you want to display photos from a friends Flickr account</p>
<p>A Flickr ID looks something like this : 12345678@N00 and you can find your Flickr ID by entering the URL of your Flickr photostream at <a href="http://idgettr.com/" rel="external">idgettr.com</a></p>
<label for="flickr_id">Flickr ID: </label><input name="flickr_id" type="text" id="flickr_id" value="{$options['id']}" />
<h4>Flickr User or Group</h4>
<p>If you leave this blank then the plugin will assume your default Flickr ID is a user ID</p>
<p>If you make a selection here, the plugin will remember it so you do not need to supply it for each photo display.</p>
<p>You are still able to override the type of Flickr Id by specifying it in the post</p>
<p>For example [slickr-flickr tag="bahamas" id="12345678@N01" group="y"] looks up photos assuming that 12345678@N01 is the Flickr ID of a group</p>
<label for="flickr_group">The Flickr ID above belongs to a : </label><select name="flickr_group" id="flickr_group">
<option value="n" {$is_user}>user</option>
<option value="y" {$is_group}>group</option>
</select>
<h4>Flickr API Key</h4>
<p>The Flickr API Key is used if you want to be able to get more than 20 photos at a time.</p>
<p>If you supply it here, the plugin will remember it so you do not need to supply it for every gallery and every slideshow.</p>
<p>A Flickr API key looks something like this : 5aa7aax73kljlkffkf2348904582b9cc and you can find your Flickr API Key by logging in to Flickr
and then visiting <a href="http://www.flickr.com/services/api/keys/" rel="external">Flickr API Keys</a></p>
<label for="flickr_api_key">Flickr API Key: </label><input name="flickr_api_key" type="text" id="flickr_api_key" style="width:320px" value="{$options['api_key']}" />		
ID_PANEL;
	}

	function general_panel($post, $metabox) {		
		$options = slickr_flickr_get_options();		
		$is_slideshow = $options['type']=="slideshow"?'selected="selected"':'';
		$is_galleria = $options['type']=="galleria"?'selected="selected"':'';
		$is_gallery = $options['type']=="gallery"?'selected="selected"':'';
		$is_medium = $options['size']=="medium"?'selected="selected"':'';
		$is_m640 = $options['size']=="m640"?'selected="selected"':'';
		$is_large = $options['size']=="large"?'selected="selected"':'';
		$is_original = $options['size']=="original"?'selected="selected"':'';		
		$captions_on = $options['captions']!="off"?'selected="selected"':'';
		$captions_off = $options['captions']=="off"?'selected="selected"':'';
		$autoplay_on = $options['autoplay']!="off"?'selected="selected"':'';
		$autoplay_off = $options['autoplay']=="off"?'selected="selected"':'';
		$upgrade = SLICKR_FLICKR_HOME . '/upgrade';
		print <<< GENERAL_PANEL
<h4>Number Of Photos To Display: <input name="flickr_items" type="text" id="flickr_items" value="{$options['items']}" /></h4>
<i>Maximum is 20 for fetching photos when using your Flickr ID, 50 for your Flickr API Key and unlimited numbers of photos when using <a href="{$upgrade}">Slickr Flickr Pro</a></i>
<p>If you supply a number it here, the plugin will remember it so you do not need to supply it for every gallery and every slideshow. You 
are still able to supply the number of photos to display for individual slideshow by specifying it in the post. For example,
[slickr-flickr tag="bahamas" items="10"] displays up to ten photos tagged with bahamas</p>
<h4>Type of Display: <select name="flickr_type" id="flickr_type">
<option {$is_gallery} value="gallery">a gallery of thumbnail images</option>
<option {$is_galleria} value="galleria">a galleria slideshow with thumbnail images below</option>
<option {$is_slideshow} value="slideshow">a slideshow of medium size images</option>
</select></h4>
<p>If you make a selection here, the plugin will remember it so you do not need to supply it for each photo display. You are 
still able to supply the type of display by specifying it in the post. For example, 
[slickr-flickr tag="bahamas" type="gallery"] displays a gallery even if you have set the default display type as slideshow</p>
<h4>Photo Size: <select name="flickr_size" id="flickr_size">
<option {$is_medium} value="medium">Medium (500px by 375px)</option>
<option {$is_m640} value="m640">Medium 640 (640px by 480px)</option>
<option {$is_large} value="large">Large (1024px by 768px)</option>
<option {$is_original} value="original">Original Size (typically 1920px by 1440px)</option>
</select></h4>
<p>If you make a selection here, the plugin will remember it so you do not need to supply it for each photo display. You are still 
able to supply the photo size by specifying it in the post. For example, [slickr-flickr tag="bahamas" size="medium"] displays medium size photos even if you have set the default size as m640</p>
<h4>Captions: <select name="flickr_captions" id="flickr_captions">
<option {$captions_on} value="on">on</option>
<option {$captions_off} value="off">off</option>
</select></h4>
<p>If you make a selection here, the plugin will remember it so you do not need to supply it for each display. You are still able to control captions on individual slideshows by specifying it in the post. 
For example [slickr-flickr tag="bahamas" captions="off"] switches off captions for that slideshow even if you have set the default captioning here to be on</p>
<h4>Autoplay: <select name="flickr_autoplay" id="flickr_autoplay">
<option {$autoplay_on} value="on">on</option>
<option {$autoplay_off} value="off">off</option>
</select></h4>
<p>If you make a selection here, the plugin will remember it so you do not need to supply it for each display. You are still able to control autoplay on individual displays by specifying it in the post. 
For example [slickr-flickr tag="bahamas" autoplay="off"] switches off autoplay for that slideshow even if you have set the default autoplay here to be on</p>
<h4>Delay Between Images: <input name="flickr_delay" type="text" id="flickr_delay" value="{$options['delay']}" />
</h4>
<p>If you supply a number it here, the plugin will remember it so you do not need to supply it for every slideshow/gallery/galleria. You are still able to supply a different delay for individual display of images by specifying it in the post. 
For example [slickr-flickr tag="bahamas" type="slideshow" delay="10"] displays a slideshow with a ten second delay between images</p>
GENERAL_PANEL;
	}

	function advanced_panel($post, $metabox) {		
		$options = slickr_flickr_get_options();			
		$scripts_in_footer = $options['scripts_in_footer']=="1"?'checked="checked"':'';
		$home = SLICKR_FLICKR_HOME;
		print <<< ADVANCED_PANEL
<h4>Load JavaScript In Footer: <input type="checkbox" name="flickr_scripts_in_footer" id="flickr_scripts_in_footer" {$scripts_in_footer} value="1" /></h4>
<p>This option allows you to load Javascript in the footer instead of the header. This can be useful as it may reduce potential jQuery conflicts with other plugins.</p>
<p>However, it will not work for all WordPress themes, specifically those that do not support loading of scripts in the footer using standard WordPress hooks and filters.</p>
<p>Click for more on <a href="{$home}/2328/load-javascript-in-footer-for-earlier-page-display/">loading Slickr Flickr scripts in the footer</a>.</p>
ADVANCED_PANEL;
	}

	function lightbox_panel($post, $metabox) {		
		$options = slickr_flickr_get_options();			
		$lightbox_auto = $options['lightbox']=="sf-lbox-auto"?'selected="selected"':'';
		$lightbox_manual = $options['lightbox']=="sf-lbox-manual"?'selected="selected"':'';
		$thickbox = $options['lightbox']=="thickbox"?'selected="selected"':'';
		$colorbox = $options['lightbox']=="colorbox"?'selected="selected"':'';
		$evolution = $options['lightbox']=="evolution"?'selected="selected"':'';
		$fancybox = $options['lightbox']=="fancybox"?'selected="selected"':'';
		$highslide = $options['lightbox']=="highslide"?'selected="selected"':'';
		$prettyphoto = $options['lightbox']=="prettyphoto"?'selected="selected"':'';
		$prettyphotos = $options['lightbox']=="prettyphotos"?'selected="selected"':'';
		$shadowbox = $options['lightbox']=="shadowbox"?'selected="selected"':'';
		$slimbox = $options['lightbox']=="slimbox"?'selected="selected"':'';
		$shutter = $options['lightbox']=="shutter"?'selected="selected"':'';
		$norel = $options['lightbox']=="norel"?'selected="selected"':'';
		$home = SLICKR_FLICKR_HOME;
		print <<< LIGHTBOX_PANEL
<p>By default the plugin will use the standard LightBox.</p>
<p>If you select LightBox slideshow then when a photo is clicked the overlaid lightbox will automatically play the slideshow.</p>
<p>If you select ThickBox then it will use the standard WordPress lightbox plugin which is pre-installed with Wordpress.</p>
<p><b>If you select one of the other lightboxes then you need to install that lightbox plugin independently from Slickr Flickr.</b></p>
<p><b>Please read this post about <a href="{$home}/1717/using-slickr-flickr-with-other-lightboxes">using Slickr Flickr with other lightboxes</a> before choosing, as not all the third party lightbox plugins support photo descriptions and links to Flickr in the photo title.</b></p> 
<label for="flickr_lightbox">Lightbox</label><select name="flickr_lightbox" id="flickr_lightbox">
<option {$lightbox_manual} value="sf-lbox-manual">LightBox with manual slideshow (pre-installed)</option>
<option {$lightbox_auto} value="sf-lbox-auto">LightBox with autoplay slideshow option (pre-installed)</option>
<option {$thickbox} value="thickbox">Thickbox (pre-installed with Wordpress)</option>
<option {$evolution} value="evolution">Evolution LightBox for Wordpress (requires separate installation)</option>
<option {$fancybox} value="fancybox">FancyBox for Wordpress (requires separate installation)</option>
<option {$highslide} value="highslide">Highslide for Wordpress Reloaded (requires separate installation)</option>
<option {$colorbox} value="colorbox">LightBox Plus for Wordpress (requires separate installation)</option>
<option {$shadowbox} value="shadowbox">Shadowbox (requires separate installation)</option>
<option {$shutter} value="shutter">Shutter Reloaded for Wordpress (requires separate installation)</option>
<option {$slimbox} value="slimbox">SlimBox for Wordpress (requires separate installation)</option>
<option {$prettyphoto} value="prettyphoto">WP Pretty Photo - single photos only(requires separate installation)</option>
<option {$prettyphotos} value="prettyphotos">WP Pretty Photo - with gallery (as above and requires setting to use bundled jQuery)</option>
<option {$norel} value="norel">Some Other LightBox(requires separate installation)</option>
</select>
LIGHTBOX_PANEL;
	}

	function galleria_panel($post, $metabox) {		
		$options = slickr_flickr_get_options();			
		$galleria_10 = $options['galleria']=="galleria-1.0"?'selected="selected"':'';
		$galleria_12 = $options['galleria']=="galleria-1.2"?'selected="selected"':'';
		$galleria_123 = $options['galleria']=="galleria-1.2.3"?'selected="selected"':'';
		$galleria_125 = $options['galleria']=="galleria-1.2.5"?'selected="selected"':'';
		$galleria_126 = $options['galleria']=="galleria-1.2.6"?'selected="selected"':'';
		$galleria_127 = $options['galleria']=="galleria-1.2.7"?'selected="selected"':'';
		$galleria_none = $options['galleria']=="galleria-none"?'selected="selected"':'';
		$home = SLICKR_FLICKR_HOME;
		print <<< GALLERIA_PANEL
<h4>Galleria Version: <select name="flickr_galleria" id="flickr_galleria">
<option {$galleria_10} value="galleria-1.0">Galleria 1.0 - original version</option>
<option {$galleria_12} value="galleria-1.2">Galleria 1.2 - with carousel and skins</option>
<option {$galleria_123} value="galleria-1.2.3">Galleria 1.2.3</option>
<option {$galleria_125} value="galleria-1.2.5">Galleria 1.2.5</option>
<option {$galleria_126} value="galleria-1.2.6">Galleria 1.2.6</option>
<option {$galleria_127} value="galleria-1.2.7">Galleria 1.2.7 - latest version</option>
<option {$galleria_none} value="galleria-none">Galleria not required so do not load the script</option>
</select></h4>
<p>Choose which version of the galleria you want to use. We recommend you use the latest version of the galleria as this has the most features.</p>
<h4>Galleria Theme: <input name="flickr_galleria_theme" type="text" id="flickr_galleria_theme" value="{$options['galleria_theme']}" /></h4>
<p>The default theme is "classic". Only change this value is you have purchased a <a href="http://galleria.aino.se/themes/">premium Galleria theme</a> or written one and placed the theme folder in the ./wp-content/plugins/slickr-flickr/galleria-x.y.z/themes folder for the version of the galleria you have selected above</p>
<h4>Galleria Options</h4>
<textarea name="flickr_galleria_options"  id="flickr_galleria_options" cols="80" rows="4">{$options['galleria_options']}</textarea>
<p>Here you can set default options for the galleria 1.2 and later versions.</p>
<p>The correct format is like CSS with colons to separate the parameter name from the value and semi-colons to separate each pair: param1:value1;param2:value2;</p>
<p>For example, transition:fadeslide;transitionSpeed:1000; sets a one second fade and slide transition. See an example of using <a href="{$home}/2270/flickr-galleria-slide-transitions-now-supported-by-slickr-flickr/">Galleria Options</a></p>
<br/>
GALLERIA_PANEL;
	}
	
	function help_panel($post, $metabox) {
		$options = slickr_flickr_get_options();		
		$home = SLICKR_FLICKR_HOME;
		print <<< HELP_PANEL
<ul>
<li><a href="{$home}" rel="external">Plugin Home Page</a></li>
<li><a href="{$home}/40/how-to-use-slickr-flickr-admin-settings/" rel="external">How To Use Admin Settings</a></li>
<li><a href="{$home}/56/how-to-use-slickr-flickr-to-create-a-slideshow-or-gallery/" rel="external">How To Use The Plugin</a></li>
<li><a href="{$home}/slickr-flickr-help/" rel="external">Get Help</a></li>
<li><a href="{$home}/slickr-flickr-videos/" rel="external">Get FREE Video Tutorials</a></li>
</ul>
<p><img src="http://images.slickrflickr.com/pages/slickr-flickr-tutorials.png" alt="Slickr Flickr Tutorials Signup" /></p>
<form id="slickr_flickr_signup" method="post" action="{$home}"
onsubmit="return slickr_flickr_validate_form(this)">
<fieldset>
<input type="hidden" name="form_storm" value="submit"/>
<input type="hidden" name="destination" value="slickr-flickr"/>
<label for="firstname">First Name
<input id="firstname" name="firstname" type="text" value="" /></label><br/>
<label for="email">Email
<input id="email" name="email" type="text" /></label><br/>
<label id="lsubject" for="subject">Subject
<input id="subject" name="subject" type="text" /></label>
<input type="submit" value="" />
</fieldset>
</form>
HELP_PANEL;
	}	
	
	function lightboxes_panel($post, $metabox) {	
		$options = slickr_flickr_get_options();		
		print <<< COMPAT_LIGHTBOX_PANEL
<ul>
<li><a href="http://wordpress.org/extend/plugins/fancybox-for-wordpress/" rel="external">FancyBox Lightbox for WordPress</a></li>
<li><a href="http://wordpress.org/extend/plugins/highslide-4-wordpress-reloaded/" rel="external">Highslide for WordPress Reloaded</a></li>
<li><a href="http://s3.envato.com/files/1099520/index.html" rel="external">Lightbox Evolution</a></li>
<li><a href="http://wordpress.org/extend/plugins/lightbox-plus/" rel="external">Lightbox Plus (ColorBox) for WordPress</a></li>
<li><a href="http://wordpress.org/extend/plugins/shadowbox-js/" rel="external">ShadowBox JS</a></li>
<li><a href="http://wordpress.org/extend/plugins/shutter-reloaded/" rel="external">Shutter Lightbox for WordPress</a></li>
<li><a href="http://wordpress.org/extend/plugins/slimbox-plugin/" rel="external">SlimBox for WordPress</a></li>
<li><a href="http://wordpress.org/extend/plugins/wp-prettyphoto/" rel="external">WP Pretty Photo</a></li>
</ul>
COMPAT_LIGHTBOX_PANEL;
	}

	function cache_panel($post, $metabox) {
		$options = slickr_flickr_get_options();		
 		$this_url = $_SERVER['REQUEST_URI'];	
		print <<< CACHE_PANEL
<h4>Clear RSS Cache</h4>
<p>If you have a RSS caching issue where your Flickr updates have not yet appeared on Wordpress then click the button below to clear the RSS cache</p>
<form id="slickr_flickr_cache" method="post" action="{$this_url}" >
<fieldset>
<input type="hidden" name="cache" value="clear" />
<input type="submit"  class="button-primary" name="clear" value="Clear Cache" />
</fieldset>
</form>
CACHE_PANEL;
	}

    function toggle_postboxes() {
    ?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
		});
		//]]>
	</script>
	<?php
    }
    
	function options_panel() {
 		global $screen_layout_columns;
		if (isset($_POST['cache'])) echo $this->clear_cache();  		
 		if (isset($_POST['options_update'])) echo $this->save();
 		$this_url = $_SERVER['REQUEST_URI'];
?>
<div class="wrap">
    <?php screen_icon(); ?><h2>Slickr Flickr Options</h2>
	<p>For help on gettting the best from Slickr Flickr visit the <a href="<?php echo SLICKR_FLICKR_HOME; ?>">Slickr Flickr Plugin Home Page</a></p>
	<p><b>We recommend you fill in your Flickr ID in the Flickr Identity section. All the other fields are optional.</b></p>
    <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div id="side-info-column" class="inner-sidebar">
		<?php do_meta_boxes($this->pagehook, 'side', null); ?>
        </div>
        <div id="post-body" class="has-sidebar">
            <div id="post-body-content" class="has-sidebar-content">
			<form id="slickr_flickr_options" method="post" action="<?php echo $this_url; ?>">
			<?php do_meta_boxes($this->pagehook, 'normal', null); ?>
			<p class="submit">
			<input type="submit"  class="button-primary" name="options_update" value="Save Changes" />
			<input type="hidden" name="page_options" value="flickr_id,flickr_group,flickr_api_key,slickr_licence,slickr_consumer_secret,slickr_token,slickr_token_secret,flickr_items,flickr_type,flickr_size,flickr_captions,flickr_autoplay,flickr_delay,flickr_scripts_in_footer,flickr_transition,flickr_thumbnail_border,flickr_lightbox,flickr_galleria,flickr_galleria_theme,flickr_galleria_options" />
			<?php wp_nonce_field(SLICKR_FLICKR_ADMIN); ?>
			<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
			<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
			</p>
			</form>
 			</div>
        </div>
        <br class="clear"/>
    </div>
</div>
<?php
	}    
    
}
?>