<?php
/*
Plugin Name: Hey It's Flickr!
Plugin URI: http://heyitsgeorge.com/flickr
Description: A custom and light-weight widget that displays recent images from your Flickr account. Easily set your user ID, how many pictures you want to display, and what the hif_title of your widget is. <strong>Stroll on over to the wonderful <strong><a href="http://idgettr.com/">http://idgettr.com/</a></strong> to find your Flickr user ID if you don't know how to find it.</strong> Enjoy!
Version: 1.0
Author: George Gecewicz
Author URI: http://heyitsgeorge.com/
.
original js from css-tricks
idgettr run by the cool dudes at eightface
.
*/
/*  Copyright 2011  George Gecewicz  (email : gecewicz.george [at] gmail [dot] com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/



function hey_its_flickr_enqueue_scripts_new() {wp_enqueue_script( 'jquery');} // wordpress' local version of jquery
function hey_its_flickr_enqueue_scripts_old() {wp_enqueue_script( 'hey-its-flickr-jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js', array(), 1.6, true);} // google CDN jquery

if ( version_compare( $GLOBALS['wp_version'], '3.2.1', '>=' ) ) add_action( 'wp_enqueue_scripts', 'hey_its_flickr_enqueue_scripts_new' ); // for recent versions of WP, the included version of jQuery is fine
if ( version_compare( $GLOBALS['wp_version'], '3.2.1', '<' ) ) add_action( 'wp_enqueue_scripts', 'hey_its_flickr_enqueue_scripts_old' ); // for older versions of WP, load a hosted, recent version of jQuery in the footer



class Hey_Its_Flickr extends WP_Widget {

	function Hey_Its_Flickr() {
		# this builds the basic widget where everything else will pile into.
		$widget_ops = array(
			'classname' => 'widget_flickr',
			'description' => 'A lightweight widget that grabs recent images from your Flickr account.'
		);
    $control_ops = array('width' => 200, 'height' => 350);
    $this->WP_Widget('Hey_Its_Flickr', __('Hey It\'s a Flickr Widget'), $widget_ops, $control_ops);
	}

	function form($instance) {
		# now this is right here is Sir Widget Form himself. In other, not-sleep-deprived words,
		# this generates the form that the user will see and interact with
   	$instance = wp_parse_args( (array) $instance, array( 'hif_title' => '') );
   	$hif_title = strip_tags($instance['hif_title']);
    $hif_userid = strip_tags($instance['hif_userid']);
		$hif_numpics = strip_tags($instance['hif_numpics']);
		$hif_imgWidth = strip_tags($instance['hif_imgWidth']);
		# just a quick note about _e() in Wordpress. You don't have to use it, but I see it in
		# the best plugins and I'm pretty sure it's used for localisation stuff (that means translation)
		?>
	   <p><label for="<?php echo $this->get_field_id('hif_title'); ?>"><?php _e('Title:'); ?></label>
		 <input class="widefat" id="<?php echo $this->get_field_id('hif_title'); ?>" name="<?php echo $this->get_field_name('hif_title'); ?>" type="text" value="<?php echo esc_attr($hif_title); ?>" /></p>

		 <p><label for="<?php echo $this->get_field_id('hif_userid'); ?>"><?php _e('Flickr User ID:'); ?></label>
	   <br>
		 <input class="widefat" id="<?php echo $this->get_field_id('hif_userid'); ?>" name="<?php echo $this->get_field_name('hif_userid'); ?>" type="text" value="<?php echo esc_attr($hif_userid); ?>" />
		 <br>
		 <small><em>Don't know your ID? Mozy on over to <a href="http://idgettr.com/" target="_blank">http://idgettr.com/</a> to find it.</em></small>
		 </p>

		 <p><label for="<?php echo $this->get_field_id('hif_numpics'); ?>"><?php _e('Number of Pictures to Display:'); ?></label>
	   <input maxlength="3" class="widefat" id="<?php echo $this->get_field_id('hif_numpics'); ?>" name="<?php echo $this->get_field_name('hif_numpics'); ?>" type="text" value="<?php echo esc_attr($hif_numpics); ?>" />
		 </p>

		 <p><label for="<?php echo $this->get_field_id('hif_imgWidth'); ?>"><?php _e('Image Width in Pixels:'); ?></label>
	   <input maxlength="3" class="widefat" id="<?php echo $this->get_field_id('hif_imgWidth'); ?>" name="<?php echo $this->get_field_name('hif_imgWidth'); ?>" type="text" value="<?php echo esc_attr($hif_imgWidth); ?>" />
		 </p>

<?php
	}

	function update($new_instance, $old_instance)  {
		# here, we're actually handling the updates that the user makes
		# strip_tags...strips any tags from previous inputs
		$instance = $old_instance;
		$instance['hif_title'] = strip_tags($new_instance['hif_title']);
		$instance['hif_userid'] = strip_tags($new_instance['hif_userid']);
		$instance['hif_numpics']= strip_tags($new_instance['hif_numpics']);
		$instance['hif_imgWidth']= strip_tags($new_instance['hif_imgWidth']);
		return $instance;
	}

	function widget( $args, $instance ) {
      extract($args);
			# classic widget instance stuff. very simple
      $hif_title = apply_filters('widget_hif_title', empty($instance['hif_title']) ? '' : $instance['hif_title'], $instance);
      $hif_userid = apply_filters('widget_hif_userid', empty($instance['hif_userid']) ? '' : $instance['hif_userid'], $instance);
      $hif_numpics = apply_filters('widget_hif_userid', empty($instance['hif_numpics']) ? '' : $instance['hif_numpics'], $instance);
      $hif_imgWidth = apply_filters('widget_hif_userid', empty($instance['hif_imgWidth']) ? '' : $instance['hif_imgWidth'], $instance);


			# this is mainly used by themes and such
			echo $before_widget;

				# here we grab the hif_title that was entered and throw on some sexy `<h3>` tags
     		$hif_title = $hif_title;
		 		if ( !empty( $hif_title ) ) { echo $before_title . $hif_title . $after_title; }

					?>
						<div id="flickr-images">
							<script type="text/javascript">
							/* <![CDATA[ */
								jQuery(document).ready(function($) {
								   $.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?id=<?php print $hif_userid; ?>&format=json&jsoncallback=?", function(data) {
								      var target = "#flickr-images";
								      for (i = 0; i <= <?php print $hif_numpics; ?> - 1; i = i + 1 ) {
								        var pic = data.items[i];
								        var liNumber = i + 1;
												var hifimgwidth = "<?php print $hif_imgWidth; ?>";
								        $(target).append("<li class='flickr-image no-" + liNumber + "'><a title='" + pic.title + "' href='" + pic.link + "' target='_blank'><img width='" + hifimgwidth + "' src='" + pic.media.m + "' /></a></li>");
								      }
								   });
								});
								/* ]]> */
							</script>
						</div>
					<?php
			# again, mainly for themes and such. this closes the widget
      echo $after_widget;
  }
}

function heyitsflickrinit() {register_widget('Hey_Its_Flickr');}
add_action('widgets_init', 'heyitsflickrinit');
?>