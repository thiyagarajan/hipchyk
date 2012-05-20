<div class="wrap">
	
	<h2><?php _e('Promotion Slider Options', 'promotion-slider'); ?></h2>
	<p><?php _e('The options below will change the settings for all of the Promotion Sliders running on your website.  If you want individual sliders to behave differently, please <a href="http://wordpress.org/extend/plugins/promotion-slider/faq/" target="_blank">read our documentation</a>.', 'promotion-slider'); ?></p>
	
	<div class="postbox-container" style="width:70%;">
	
		<div class="metabox-holder">
		
			<div class="meta-box-sortables ui-sortable">
			
				<form method="post" action="options.php">
					<?php settings_fields( 'promoslider-settings-group' ); ?>
					<?php $options = get_option('promotion_slider_options'); ?>

					<div id="slider-settings" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						
						<h3 class="hndle"><span><?php _e('Slider Settings', 'promotion-slider'); ?></span></h3>
						
						<div class="inside">
						
							<p>
								<strong><?php _e('Start Slider On:', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[start_on]" value="first" <?php if( $options['start_on'] == 'first' ) echo 'checked="checked"'; ?> />
								<label><?php _e('First Slide', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[start_on]" value="random" <?php if( $options['start_on'] == 'random' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Random Slide', 'promotion-slider'); ?></label>
							</p>
						
							<p>
								<strong><?php _e('Automatic Slide Advancement:', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[auto_advance]" value="auto_advance" <?php if( $options['auto_advance'] == 'auto_advance' ) echo 'checked="checked"'; ?> />
								<label><?php _e('On', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[auto_advance]" value="no_auto_advance" <?php if( 	$options['auto_advance'] == 'no_auto_advance' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Off', 'promotion-slider'); ?></label>
							</p>
						
							<p>
								<strong><?php _e('Set Slider Time Delay:', 'promotion-slider'); ?> </strong>
								<select name="promotion_slider_options[time_delay]">
									<?php for($i=3; $i<=15; $i++): ?>
										<option value="<?php echo $i; ?>"<?php if( $options['time_delay'] == $i ) echo 'selected="selected"'; ?>>
										<?php echo $i; ?> <?php _e('Seconds', 'promotion-slider'); ?>
										</option>
									<?php endfor; ?>
								</select>
							</p>
							
							<p>
								<strong><?php _e('Pause Slider on Mouse Hover:', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[pause_on_hover]" value="pause" <?php if( $options['pause_on_hover'] == 'pause' ) echo 'checked="checked"'; ?> />
								<label><?php _e('On', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[pause_on_hover]" value="no_pause" <?php if( $options['pause_on_hover'] == 'no_pause' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Off', 'promotion-slider'); ?></label>
							</p>
							
							<p>
								<strong><?php _e('Default Image Size:', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[default_img_size]" value="thumbnail" <?php if( $options['default_img_size'] == 'thumbnail' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Thumbnail', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[default_img_size]" value="medium" <?php if( $options['default_img_size'] == 'medium' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Medium', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[default_img_size]" value="large" <?php if( $options['default_img_size'] == 'large' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Large', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[default_img_size]" value="full" <?php if( $options['default_img_size'] == 'full' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Full Size', 'promotion-slider'); ?></label><br />
							</p>
							
						</div>
							
					</div>
							
					<div id="display-settings" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						
						<h3 class="hndle"><span><?php _e('Display Settings', 'promotion-slider'); ?></span></h3>
						
						<div class="inside">
						
							<p>
								<strong><?php _e('Choose a navigation display for your slider:', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[display_nav]" value="none" <?php if( $options['display_nav'] == 'none' ) echo 'checked="checked"'; ?> />
								<label><?php _e('No Slider Navigation', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[display_nav]" value="default" <?php if( $options['display_nav'] == 'default' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Default Slider Navigation', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[display_nav]" value="fancy" <?php if( $options['display_nav'] == 'fancy' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Fancy Slider Navigation', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[display_nav]" value="links" <?php if( $options['display_nav'] == 'links' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Slider Navigation Links', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[display_nav]" value="thumb" <?php if( $options['display_nav'] == 'thumb' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Thumbnail Navigation', 'promotion-slider'); ?></label>
							</p>
								
							<p>
								<strong><?php _e('Choose a title display for your slider:', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[display_title]" value="none" <?php if( $options['display_title'] == 'none' ) echo 'checked="checked"'; ?> />
								<label><?php _e('No Title Display', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[display_title]" value="default" <?php if( $options['display_title'] == 'default' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Default Title Display', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[display_title]" value="fancy" <?php if( $options['display_title'] == 'fancy' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Fancy Title Display', 'promotion-slider'); ?></label>
							</p>
						
							<p>
								<strong><?php _e('Should we display the excerpt in your slider?', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[display_excerpt]" value="excerpt" <?php if( $options['display_excerpt'] == 'excerpt' ) echo 'checked="checked"'; ?> />
								<label><?php _e('On', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[display_excerpt]" value="none" <?php if( $options['display_excerpt'] == 'none' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Off', 'promotion-slider'); ?></label>
							</p>
							
						</div>
						
					</div>
						
					<div id="advanced-settings" class="postbox">
						<div class="handlediv" title="Click to toggle"><br /></div>
						
						<h3 class="hndle"><span><?php _e('Advanced Settings', 'promotion-slider'); ?></span></h3>
						
						<div class="inside">		
						
							<p>
								<strong><?php _e('Load Javascript in:', 'promotion-slider'); ?></strong><br />
								<input type="radio" name="promotion_slider_options[load_js_in]" value="head" <?php if( $options['load_js_in'] == 'head' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Header', 'promotion-slider'); ?></label><br />
								
								<input type="radio" name="promotion_slider_options[load_js_in]" value="footer" <?php if( $options['load_js_in'] == 'footer' ) echo 'checked="checked"'; ?> />
								<label><?php _e('Footer', 'promotion-slider'); ?></label>
							</p>
					
						</div>
						
					</div>
					
					<div class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'promotion-slider') ?>" />
					</div>
			  
				</form>
			
			</div> 
		
		</div>
  
	</div>
	
	<div class="postbox-container" style="width:20%;">
	
		<div class="metabox-holder">
		
			<div class="meta-box-sortables ui-sortable">
				
				<div id="donate" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div> 
					<h3 class="hndle"><span><?php _e('Donate $10, $25 or $50!', 'promotion-slider'); ?></span></h3>
					<div class="inside">
						<p><?php _e('This plugin has cost me countless hours of work. If you use it, please donate a token of your appreciation!', 'promotion-slider'); ?></p>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="ZWYWE2TQLXBJY">
							<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
					</div> 
				</div>
				
				<div id="like" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div> 
					<h3 class="hndle"><span><?php _e('Like this plugin?', 'promotion-slider'); ?></span></h3>
					<div class="inside">
						<p><?php _e('Why not do any or all of the following:', 'promotion-slider'); ?>
							<ul>
								<li><?php printf(__('%sLink to it%s so others can find out about it', 'promotion-slider'), '<a href="http://www.orderofbusiness.net/plugins/promotion-slider/" target="_blank">', '</a>'); ?></li>
								<li><?php printf(__('%sGive it a 5 star rating%s at WordPress.org', 'promotion-slider'), '<a href="http://wordpress.org/extend/plugins/promotion-slider/" target="_blank">', '</a>'); ?></li>
								<li><?php printf(__('%sDonate%s a token of your appreciation', 'promotion-slider'), '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZWYWE2TQLXBJY" target="_blank">', '</a>'); ?></li>
							</ul>
						</p>
					</div> 
				</div>
				
				<div id="recommendations" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div> 
					<h3 class="hndle"><span><?php _e('Highly Recommended Plugins', 'promotion-slider'); ?></span></h3>
					<div class="inside">
						<a href="https://www.e-junkie.com/ecom/gb.php?cl=54585&amp;c=ib&amp;aff=155486" target="_blank" alt="Gravity Forms" class="alignleft"><img src="<?php echo WP_PLUGIN_URL.'/promotion-slider/css/images/gravity_forms.gif'; ?>" alt="Gravity Forms"></a>
						<div style="clear:both;"></div>
					</div> 
				</div>
				
				<div id="support" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div> 
					<h3 class="hndle"><span><?php _e('Need support?', 'promotion-slider'); ?></span></h3>
					<div class="inside">
						<p>
							<ul>
								<li><strong><?php _e('Read our documentation', 'promotion-slider'); ?></strong> - <?php _e('Before you do anything else, read through our <a href="http://wordpress.org/extend/plugins/promotion-slider/faq/" target="_blank">documentation</a> to see if you can find the answer to your question.', 'promotion-slider'); ?></li>
								<li><strong><?php _e('Visit the support forums', 'promotion-slider'); ?></strong> - <?php _e('Before posting in the <a href="http://wordpress.org/tags/promotion-slider" target"_blank">support forums</a>, check if someone has already answered a similar question.  <em>Help someone else while you are there!</em>', 'promotion-slider'); ?></li>
								<li><strong><?php _e('Get in touch', 'promotion-slider'); ?></strong> - <?php _e('We are happy to provide <a href="http://www.orderofbusiness.net/request-quote/" target="_blank">support</a> for any issues you may be having, but we do charge for our services.', 'promotion-slider'); ?></li>
							</ul>
						</p>
					</div> 
				</div>
				
				<div id="plugin-feedback" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div> 
					<h3 class="hndle"><span><?php _e('Plugin Feedback', 'promotion-slider'); ?></span></h3>
					<div class="inside">
						<p><?php _e('We take <a href="http://www.orderofbusiness.net/contact-us/plugin-feedback/" target="_blank">feedback from our users</a> very seriously.', 'promotion-slider'); ?></p>
						<p><strong><?php _e('Please get in touch if you have:', 'promotion-slider'); ?></strong>
							<ul>
								<li><?php _e('Feature Requests', 'promotion-slider'); ?></li>
								<li><?php _e('Suggestions for Improvement', 'promotion-slider'); ?></li>
								<li><?php _e('Bug Reports', 'promotion-slider'); ?></li>
							</ul>
						</p>
					</div> 
				</div>
				
				<!-- <div id="latest-news" class="postbox">
					<div class="handlediv" title="Click to toggle"><br /></div> 
					<h3 class="hndle"><span><?php _e('Latest News', 'promotion-slider'); ?></span></h3>
					<div class="inside">
						<ul>
							<li><a href="#">News Item</a></li>
							<li><a href="#">News Item</a></li>
							<li><a href="#">News Item</a></li>
						</ul>
					</div> 
				</div> -->
				
			</div> 
  
		</div>
	
	</div>
	
</div>