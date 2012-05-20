<p style="color:red;"><?php printf( __('If you have found this plugin to be helpful, please consider making a %sdonation%s!', 'promotion-slider'), '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZWYWE2TQLXBJY" target="_blank">', '</a>'); ?></p>

<p><?php _e('There are three sections that allow you to control the global settings for your promotion sliders.  Keep in mind that most of these settings can be overridden by using the shortcodes.'); ?></p>

<p><strong><?php _e('Slider Settings'); ?></strong> - <?php _e('This section allows you to change general functionality of your sliders.'); ?></p>
<ul>
	<li><strong><?php _e('Start Slider On'); ?></strong> - <?php _e('On each page load, this setting determines whether the first slide or a random slide should be displayed.  By default, all slides are ordered chronologically. If you wish to reorder your promotions, you can change the publish dates as needed or advanced users can use one of the provided hooks to alter the query for a slider.'); ?></li>
	<li><strong><?php _e('Automatic Slide Advancement'); ?></strong> - <?php _e('When enabled, slides will automatically progress at the time interval that has been set.  If you disable automatic advancement, a single slide will display until a user clicks on a link in the slider navigation.  Please be sure you have some form of slider navigation enabled if automatic advancement is disabled.'); ?></li>
	<li><strong><?php _e('Set Slider Time Delay'); ?></strong> - <?php _e('When automatic advancement is enabled, this time delay is used to determine how long to wait before advancing to the next slide.'); ?></li>
	<li><strong><?php _e('Pause Slider on Mouse Hover'); ?></strong> - <?php _e("Enabling this feature will suspend the automatic advancement of slides (if enabled) when a user's mouse is over the slider.  This allows users more time to view a particular slide before continuing to the next one."); ?></li>
	<li><strong><?php _e('Default Image Size'); ?></strong> - <?php _e('When you upload an image, WordPress saves that image in several different sizes for you.  This option lets you choose which image size best fits your slider.  If you have created a custom image size, you utilize our plugin hooks to make the slider use your custom size.'); ?></li>
</ul>

<p><strong><?php _e('Display Settings'); ?></strong> - <?php _e('This section allows you to change the content that displays in your sliders.'); ?></p>
<ul>
	<li><strong><?php _e('Choose a navigation display for your slider'); ?></strong> - <?php _e("Enabling the slider navigation allows users different methods of browsing the slides. The default and fancy navigation work the same, but the fancy navigation uses a nice background image.  Both the default and fancy navigation allow users to move forward or backward a slide, or to access a slide by clicking on the slide number.  The slider navigation links option displays a forward and back button on the slider when a user moves their mouse over the slider.  This is a good option if you want to provide navigation without using extra space or detracting from the slider's contents. The thumbnail navigation displays below the slider and allows users to move forward or backward, or to view a particular slide by clicking on a thumbnail image."); ?></li>
	<li><strong><?php _e('Choose a title display for your slider'); ?></strong> - <?php _e('When enabled, the title of your promotions will display in the slider.  The default title will display just the text, while the fancy title display will show the title with a nice background image.'); ?></li>
	<li><strong><?php _e('Should we display the excerpt in your slider?'); ?></strong> - <?php _e('Enabling this option will display the excerpt on each slide in your sliders.'); ?></li>
</ul>

<p><strong><?php _e('Advanced Settings'); ?></strong></p>
<ul>
	<li><strong><?php _e('Load Javascript In'); ?></strong> - <?php _e("You can choose to load the JavaScript, which makes the slider actually work, in your theme's header or footer.  To load in the header, your theme should use the wp_head() function in the header.php file.  To load in the footer, your theme should use the wp_footer() function in the footer.php file.  By default, the JavaScript is loaded in the header, because many custom themes tend to have the wp_head() function and not the wp_footer() function.  We recommend loading the JavaScript in the footer if your theme supports it."); ?></li>
</ul>

<p><strong><?php _e('For more information:', 'promotion-slider'); ?></strong></p>

<p><a href="http://wordpress.org/extend/plugins/promotion-slider/faq/" target="_blank"><?php _e('Documentation on Promotion Slider', 'promotion-slider'); ?></a></p>

<p><a href="http://wordpress.org/tags/promotion-slider" target="_blank"><?php _e('Support Forums', 'promotion-slider'); ?></a></p>