<?php
/*
Plugin Name: Promotion Slider
Plugin URI: http://www.orderofbusiness.net/plugins/promotion-slider/
Description: This plugin creates a special post type called 'Promotions' that it uses to populate the carousel.  Just use the shortcode [promoslider] to display the slider on your site.  Be sure to check the <a href="http://wordpress.org/extend/plugins/promotion-slider/faq/" target="_blank">user guide</a> for more information on what this plugin can really do!
Version: 3.3.1
Author: Micah Wood
Author URI: http://www.orderofbusiness.net/micah-wood/
License: GPL3

Copyright 2010  Micah Wood  (email : micah.wood@orderofbusiness.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Check for PHP 5+ and return error before getting to code that will throw errors
if( phpversion() < 5 )
	die( printf(__('Sorry, this plugin requires PHP version %s or later. You are currently running version %s.', 'promotion-slider'), 5, phpversion()) );

// DEFINE CONSTANTS
if( !defined('PROMOSLIDER_VER') ) define( 'PROMOSLIDER_VER', '3.3.1' );

// INCLUDE FILES
include( dirname(__FILE__).'/classes/post_type.php' );
include( dirname(__FILE__).'/classes/register_tax.php' );
include( dirname(__FILE__).'/classes/legacy.php' );
include( dirname(__FILE__).'/includes/functions.php' );

// BEGIN MAIN CLASS
if( !class_exists('promo_slider') ){
	
	class promo_slider{
	  
		// Define post type and taxonomy name
		private $post_type = 'ps_promotion',
				$taxonomy = 'promotion-categories',
				$options,
				$options_page;
	  
		function __construct(){
			// Set global plugin defaults
			$this->options = array(	'version' => PROMOSLIDER_VER,
									'start_on' => 'first',				// VALUES: first, random
									'auto_advance' => 'auto_advance',	// VALUES: auto_advance, no_auto_advance
									'time_delay' => 6,					// VALUES: any integer between 3 and 15
									'display_nav' => 'fancy',			// VALUES: none, default, fancy, links, thumb
									'display_title' => 'none',			// VALUES: none, default, fancy
									'display_excerpt' => 'none',		// VALUES: none, excerpt
									'pause_on_hover' => 'no_pause',		// VALUES: pause, no_pause
									'load_js_in' => 'header',			// VALUES: header, footer
									'default_img_size' => 'full',		// VALUES: thumbnail, medium, large, full
									'show_title' => false,				// DEPRECIATED: replaced by display_title, VALUES: true, false
									'show_excerpt' => false,			// DEPRECIATED: replaced by display_excerpt, VALUES: true, false
									'nav_option' => 'default',			// DEPRECIATED: replaced by display_nav, VALUES: none, default, links, thumb
									'disable_fancy_title' => false,		// DEPRECIATED: replaced by display_title, VALUES: true, false
									'disable_fancy_nav' => false		// DEPRECIATED: replaced by display_nav, VALUES: true, false
									);
		  
			// Create the post type that we will pull from
			new wordpress_custom_post_type($this->post_type, 
										   array('singular' => __('Promotion', 'promotion-slider'), 
												 'plural' => __('Promotions', 'promotion-slider'),
											     'slug' => 'promotion', 
											     'args' => array('supports' => array('title', 'editor', 'author', 'excerpt', 'thumbnail'))));

			// Register the taxonomy for our post type
			new wordpress_custom_taxonomy($this->taxonomy, 
										  $this->post_type, 
										  array('singular' => __('Category', 'promotion-slider'), 
											    'plural' => __('Categories', 'promotion-slider'), 
											    'slug' => 'promotions',
												'args' => array('hierarchical' => TRUE)) );

			// Perform some maintenance activities on activation
			register_activation_hook( __FILE__, array($this, 'activate') );
			
			// Check if plugin has been updated, if so run activation function
			if( $this->get_options()->version != PROMOSLIDER_VER )
				$this->activate();
		  
			// Initiate key components
			add_action( 'after_setup_theme', array($this, 'after_setup_theme') );
			add_action( 'init', array($this, 'init') );
			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_menu', array($this, 'admin_menu') );
			add_action( 'admin_print_styles', array($this, 'load_css_for_options_page') );
		  
			// Load this plugin last to ensure other plugins don't overwrite theme support
			add_action( 'activated_plugin', array($this, 'load_last') );
			
			// Add contextual help
			add_filter( 'contextual_help', array($this, 'slider_options_help'), 10, 3 );
			
			// Add menu items to the WordPress admin bar
			add_action( 'wp_before_admin_bar_render', array($this, 'wp_admin_bar') );

		}
		
		function wp_admin_bar() {
			global $wp_admin_bar;
			$wp_admin_bar->add_menu( array( 'parent' => 'appearance', 
											'id' => 'promoslider_settings', 
											'title' => __('Promotion Slider Options', 'promotion-slider'), 
											'href' => admin_url( 'edit.php?post_type='.$this->post_type.'&page=options' )) );
		}
		
		function load_css_for_options_page(){
			if( @$_GET['post_type'] == $this->post_type && @$_GET['page'] == 'options' )
				wp_enqueue_style('options_page_css', plugins_url('/css/admin.css', __FILE__) );
		}
		
		function slider_options_help( $contextual_help, $screen_id, $screen ){
			if( $screen_id == 'edit-'.$this->post_type ):
				// Promotions
				$contextual_help = $this->get_include_contents( dirname(__FILE__).'/includes/help.promotions.php' );
			elseif( $screen_id == $this->post_type ):
				// Add New Promotion
				$contextual_help = $this->get_include_contents( dirname(__FILE__).'/includes/help.add_new_promotion.php' );
			elseif( $screen_id == 'edit-'.$this->taxonomy ):
				// Categories
				$contextual_help = $this->get_include_contents( dirname(__FILE__).'/includes/help.categories.php' );
			elseif( $screen_id == $this->options_page ):
				// Slider Options
				$contextual_help = $this->get_include_contents( dirname(__FILE__).'/includes/help.slider_options.php' );
			endif;
			return $contextual_help;
		}
		
		function get_include_contents( $filename ) {
			if( is_file($filename) ) {
				ob_start();
				include $filename;
				return ob_get_clean();
			}
			return false;
		}
	  
		function activate() {
			// Make sure user is using WordPress 3.0+
			$this->requires_wordpress_version();
			// Change post type, if necessary
			ps_legacy::change_legacy_post_type();
			// Ensure all options are up-to-date when upgrading
			$this->option_management();
			// One time flush of rewrite rules
			flush_rewrite_rules();
		}
		
		function requires_wordpress_version( $ver = 3 ){
			global $wp_version;
			if( $wp_version < $ver )
				die( printf(__('Sorry, this plugin requires WordPress version %d or later. You are currently running version %s.', 'promotion-slider'), $ver, $wp_version) );
		}
	  
		function after_setup_theme(){ 
			// Adds support for featured images, which is where the slider gets its images
			add_theme_support( 'post-thumbnails' ); 
		}
	  
		function init(){
			// Add support for translations
			load_plugin_textdomain( 'promotion-slider', FALSE, dirname(plugin_basename(__FILE__)).'/lang/' );
			// Load our js and css files
			add_action( 'wp_print_styles', array($this, 'enqueue_styles') );
			add_action( 'wp_print_scripts', array($this, 'enqueue_scripts') );
			// Create [promoslider] shortcode
			add_shortcode( 'promoslider', array($this, 'show_slider') );
			// Enable use of the shortcode in text widgets
			add_filter( 'widget_text', 'do_shortcode' );
			// Add our custom column to the promotions listing page
			add_filter( 'manage_edit-ps_promotion_columns', array($this, 'add_promotion_columns') );
			add_action( 'manage_posts_custom_column',  array($this, 'show_promotion_columns') );
			// Add our custom filters to the promotions listing page
			add_action( 'restrict_manage_posts', array($this, 'manage_posts_by_category') );
		}
		
		function enqueue_styles(){
			// Loads our styles, only on the front end of the site
			if( !is_admin() ){
				wp_enqueue_style( 'promoslider_main', plugins_url('/css/slide.css', __FILE__) );
			}
		}
		
		function enqueue_scripts(){
			// Loads our scripts, only on the front end of the site
			if( !is_admin() ){
				// Get plugin options
				$options = $this->get_options();
				// Load javascript
				$load_js_in_footer = ( $options->load_js_in == 'footer' ) ? TRUE : FALSE;
				wp_enqueue_script( 'promoslider_main', plugins_url('/js/promo_slider.js', __FILE__), array('jquery'), FALSE, $load_js_in_footer );
				// Localize plugin options
				$data = array('version' => PROMOSLIDER_VER);
				wp_localize_script('promoslider_main', 'promo_slider_options', $data);
			}
		}
	  
		function admin_init(){ 
			// Register plugin options
			register_setting( 'promoslider-settings-group', 'promotion_slider_options', array($this, 'update_options') ); 
			// Add meta boxes to our post type
			add_meta_box( 'promo_slider_meta_box', __('Promotion Slider Options', 'promotion-slider'), array($this, 'meta_box_content'), $this->post_type );
			// Save meta data when saving our post type
			add_action( 'save_post', array($this, 'save_meta_data') );
		}
	  
		function admin_menu(){
			// Create options page
			$this->options_page = add_submenu_page( 'edit.php?post_type='.$this->post_type, 
												    __('Promotion Slider Options', 'promotion-slider'), 
												    __('Slider Options', 'promotion-slider'), 
												    'manage_options', 
												    'options', 
												    array($this, 'options_page') );
		}
	  
		function load_last(){
			// Get array of active plugins
			if( !$active_plugins = get_option('active_plugins') ) return;
			// Set this plugin as variable
			$my_plugin = 'promotion-slider/'.basename(__FILE__);
			// See if my plugin is in the array
			$key = array_search( $my_plugin, $active_plugins );
			// If my plugin was found
			if( $key !== FALSE ){
				// Remove it from the array
				unset( $active_plugins[$key] );
				// Reset keys in the array
				$active_plugins = array_values( $active_plugins );
				// Add my plugin to the end
				array_push( $active_plugins, $my_plugin );
				// Resave the array of active plugins
				update_option( 'active_plugins', $active_plugins );
			}
		}
	  
		function options_page(){ 
			// Load options page
			include( dirname(__FILE__).'/includes/options_page.php' ); 
		}
	  
		function meta_box_content() { 
			include( dirname(__FILE__).'/includes/meta_box.php' ); 
		}
			
		function save_meta_data( $post_id ) {
			// If this is an auto save, our form has not been submitted, so we don't want to do anything
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
			// Is the post type correct?
			if ( isset($_POST['post_type']) && $this->post_type != $_POST['post_type'] ) return $post_id;
			// Verify this came from our screen and with proper authorization, because save_post can be triggered at other times
			if ( !isset($_POST['promo_slider_noncename']) || !wp_verify_nonce( $_POST['promo_slider_noncename'], 'ps_update_promotion' )) { return $post_id; }
			// Can user edit this post?
			if ( !current_user_can('edit_post', $post_id) ) return $post_id;
			// Setup array for the data we are saving	
			$data = array('_promo_slider_target', '_promo_slider_url', '_promo_slider_disable_links', '_promo_slider_show_ad_code', '_promo_slider_ad_code', '_promo_slider_cdn_image_url');
			// Use this filter to add postmeta to save for the default post type
			$data = apply_filters('promoslider_add_meta_to_save', $data);
			// Save meta data
			foreach($data as $meta){
				// Get current meta
				$current = get_post_meta($post_id, $meta, TRUE);
				// Get new meta
				$new = $_POST[$meta];
				// If the new meta is empty, delete the current meta
				if( empty($new) ) delete_post_meta($post_id, $meta);
				// Otherwise, update the current meta
				else update_post_meta($post_id, $meta, $new);
			}
		}
	  
		function show_slider( $atts ){
			global $post;
			// Get plugin options
			$options = $this->get_options();
			// Get combined and filtered attribute list
			$options = shortcode_atts(array('id' => NULL,
											'width' => NULL, 
											'height' => NULL,
											'post_type' => $this->post_type, 
											'category' => NULL,
											'numberposts' => -1,
											'start_on' => $options->start_on,
											'auto_advance' => $options->auto_advance,
											'time_delay' => $options->time_delay, 
											'display_nav' => $options->display_nav,
											'display_title' => $options->display_title,
											'display_excerpt' => $options->display_excerpt,
											'pause_on_hover' => $options->pause_on_hover), $atts);
			// Validate options
			foreach( $options as $option => $value )
				$options[$option] = $this->validate_options( $option, $value );
			
			// Extract shortcode attributes
			extract( $options );
			
			// Create an array with default values that we can use to build our query
			$query = array('numberposts' => $numberposts, 'post_type' => $post_type);
			
			// If the post type is post or the default, set the category based on taxonomy.
			if( $category ){
				if( $query['post_type'] == $this->post_type ) $query[$this->taxonomy] = $category;
				elseif( $query['post_type'] == 'post' ) $query['category_name'] = $category;
			}
			
			// Use the promoslider_query filter to customize the results returned.
			$query = apply_filters('promoslider_query', $query);
			
			// Use the promoslider_query_by_id filter to customize a query for a particular slider.
			$query_by_id = apply_filters('promoslider_query_by_id', array('query' => $query, 'id' => $id));
			$query = $query_by_id['query'];
		
			// Use the promoslider_custom_query_results to run your own query and return the results object to the slider
			$promo_posts = apply_filters('promoslider_custom_query_results', $promo_posts);
		
			// Run query and get posts
			if( !has_filter('promoslider_custom_query_results') )
				$promo_posts = get_posts( $query );
		
			// If there are results, build slider.  Otherwise, don't show anything.
			if( $promo_posts ){ 
			
				// Initiate iteration counter
				$i = 1;
				
				// Setup thumbnail array if thumbnail nav is being used
				if( $display_nav == 'thumb' ) $thumb_collection = array();
				
				// Setup attributes for wrapper element
				$wrapper_classes = 'promo_slider_wrapper '.$start_on;
				if( $display_nav != 'none' ) $wrapper_classes .= ' '.$display_nav.'_nav';
				$wrapper_classes .= ' '.$pause_on_hover;
				$wrapper_atts = ' class="'.$wrapper_classes.'"';
				
				// Setup attributes for main slider element
				$slider_id = ( $id ) ? ' id="'.$id.'"' : '';
				$slider_classes = 'promo_slider '.$auto_advance;
				$width = ( $width ) ? 'width:'.$width.';' : '';
				$height = ( $height ) ? 'height:'.$height.';' : '';
				$style = ( $width || $height ) ? ' style="'.$width.' '.$height.'"' : '';
				$slider_atts = $slider_id.' class="'.$slider_classes.'"'.$style;
		   
				// Begin Output
				ob_start(); ?>
  
				<div<?php echo $wrapper_atts; ?>>
					<?php do_action('before_promoslider', $id); ?>
					
					<div<?php echo $slider_atts; ?>>
						<?php if($time_delay) echo '<span class="promo_slider_time_delay" style="display:none;">'.$time_delay.'</span>'; ?>
                
						<?php foreach($promo_posts as $post): setup_postdata($post);
							// Setup values to be passed to the promoslider_content action hook //
							
							// Get the title
							$title = get_the_title(); 
							// Get the excerpt
							$excerpt = get_the_excerpt();
							// Fetch image for slider
							$image = $this->get_slider_image( $id );
							// Fetch thumbnails for slider nav, if thumbnail nav is being used
							if( $display_nav == 'thumb' ) $thumb_collection[] = $this->get_slider_thumb( $title );
							// Fetch link settings
							extract( $this->fetch_link_settings() );
							
							// Store all the values in an array and pass it to the promoslider_content action
							$values = compact('id', 'title', 'excerpt', 'image', 'destination_url', 'target', 'disable_links', 'display_title', 'display_excerpt'); ?>
									
							<div class="panel panel-<?php echo $i; ?>">
								<span class="panel-title" style="display:none;"><?php echo $title; ?></span>
								<?php // Use the promoslider_content action to populate the contents for panel
								do_action('promoslider_content', $values); ?>
							</div>
						
							<?php $i++; ?>
						<?php endforeach; 
					
						if( $i > 2 && $display_nav != 'thumb' ):
							// Use promoslider_nav action to generate the nav options
							do_action('promoslider_nav', $display_nav);
						endif; ?>
					
						<div class="clear"></div>
					
					</div><?php
					
					if( $i > 2 && $display_nav == 'thumb' ) 
						do_action('promoslider_thumbnail_nav', array('id' => $id, 'title' => $title, 'thumbs' => $thumb_collection, 'width' => $width) );
					do_action('after_promoslider', $id); ?>
					
				</div><?php
				
				// Reset query so that comment forms work properly
				wp_reset_query();
		  
				// End Output		  
				return ob_get_clean();
		
			}
		}
		
		function get_slider_image( $slider_id = NULL ){
			global $post;
			$options = $this->get_options();
			// If functionality or image doesn't exist, go ahead and terminate
			if( !function_exists('has_post_thumbnail') || !has_post_thumbnail($post->ID) ) return FALSE;
			// Filters allow for use of particular image sizes in the slider
			$image_size = apply_filters('promoslider_image_size', $options->default_img_size);
			// Filters allow for use of particular image sizes in specific sliders
			$image_size = apply_filters('promoslider_image_size_by_id', array('id' => $slider_id, 'image_size' => $image_size) );
			$image_size = $image_size['image_size'];
			// Return the appropriate sized image
			return get_the_post_thumbnail($post->ID, $image_size);
		}
		
		function get_slider_thumb( $title ){
			global $post;
			// If functionality or image doesn't exist, go ahead and terminate
			if( !function_exists('has_post_thumbnail') || !has_post_thumbnail($post->ID) ) return FALSE;
			// Filter allows for use of particular thumbnail size in the slider
			$thumb_size = apply_filters('promoslider_thumb_size', 'thumbnail');
			// Return the appropriate sized image with corrected title attribute
			return preg_replace('/title="[^"]*"/', 'title="'.$title.'"', get_the_post_thumbnail($post->ID, $thumb_size) );
		}
		
		function fetch_link_settings(){
			global $post;
			// If the destination url is set by the user, use that.  Otherwise, use the permalink
			$destination_url = get_post_meta($post->ID, '_promo_slider_url', TRUE);
			if( !$destination_url ) $destination_url = get_permalink($post->ID);
			// If the target attribute is set the user, use that.  Otherwise, set it to _self
			$target = get_post_meta($post->ID, '_promo_slider_target', TRUE);
			if( !$target ) $target = '_self';
			// Setup the disable links variable
			$disable_links = get_post_meta($post->ID, '_promo_slider_disable_links', TRUE);
			return compact('destination_url', 'target', 'disable_links');
		}
		
		function add_promotion_columns( $columns ){
			// Create a new array so we can put columns in the order we want
			$new_columns = array();
			// Transfer columns to new array and append ours after the desired elements
			foreach($columns as $key => $value){
				$new_columns[$key] = $value;
				if($key == 'title')
					$new_columns[$this->taxonomy] = __('Categories', 'promotion-slider');
			}
			// Return the new column configuration
			return $new_columns;
		}

		function show_promotion_columns( $name ) {
			global $post;
			// Display our categories on the promotions listing page
			switch ( $name ) {
				case $this->taxonomy:
					$terms = get_the_terms( $post->ID, $this->taxonomy );
					if( $terms ){
						$links = array();
						foreach( $terms as $term ){
							$links[] = '<a href="edit.php?post_type='.$this->post_type.'&'.$this->taxonomy.'='.$term->slug.'">'.$term->name.'</a>';
						}
						echo implode(', ', $links);
					}
					else
						_e('No Categories', 'promotion-slider');
					break;
			}
		}
		
		function manage_posts_by_category(){
			global $typenow;
			// If we are on our custom post type screen, add our custom taxonomy as a filter
			if( $typenow == $this->post_type ){
				$taxonomy = get_terms($this->taxonomy); 
				if( $taxonomy ): //print_r($taxonomy); ?>
					<select name="<?php echo $this->taxonomy; ?>" id="<?php echo $this->taxonomy; ?>" class="postform">
						<option value="">Show All Categories</option><?php
						foreach( $taxonomy as $terms ): ?>
							<option value="<?php echo $terms->slug; ?>"<?php if( isset($_GET[$this->taxonomy]) && $terms->slug == $_GET[$this->taxonomy] ) echo ' selected="selected"'; ?>><?php echo $terms->name; ?></option><?php
						endforeach; ?>
					</select><?php
				endif;
			}
		}
		
		function get_options(){ 
			// Get options from database
			$options = get_option('promotion_slider_options'); 
			// If nothing, return false
			if( !$options ) return FALSE;
			// Otherwise, return the options as an object (my personal preference)
			return (object) $options;
		}
		
		function update_options( $options = array() ){
			// Get plugin default options as an array
			$defaults = (array) $this->options;
			// Get new options as an array
			$options = (array) $options;
			// Merge the arrays allowing the new options to override defaults
			$options = wp_parse_args( $options, $defaults );
			// Validate options
			foreach( $options as $option => $value ){
				$options[$option] = $this->validate_options( $option, $value );
				if( $value === FALSE ) unset($options[$option]);
			}
			// Return new options array
			return $options;
		}
		
		function validate_options( $option_name, $option_value ){
			switch( $option_name ){
				case 'version':
					return PROMOSLIDER_VER;
				case 'start_on':
					if( in_array($option_value, array('first', 'random')) ) 
						return $option_value;
					break;
				case 'auto_advance':
					if( in_array($option_value, array('auto_advance', 'no_auto_advance')) )
						return $option_value;
					break;
				case 'time_delay':
					$option_value = (int) $option_value;
					if( is_int($option_value) && $option_value >= 3 && $option_value <= 15 ) 
						return $option_value;
					break;
				case 'display_nav':
					if( in_array($option_value, array('none', 'default', 'fancy', 'links', 'thumb')) )
						return $option_value;
					break;
				case 'display_title':
					if( in_array($option_value, array('none', 'default', 'fancy')) )
						return $option_value;
					break;
				case 'display_excerpt':
					if( in_array($option_value, array('none', 'excerpt')) )
						return $option_value;
					break;
				case 'pause_on_hover':
					if( in_array($option_value, array('pause', 'no_pause')) )
						return $option_value;
					break;
				case 'load_js_in':
					if( in_array($option_value, array('head', 'footer')) )
						return $option_value;
					break;
				case 'post_type':
					if( $option_value != $this->post_type && post_type_exists($option_value) ) 
						return $option_value;
					return $this->post_type;
					break;
				case 'category':
					if( term_exists($option_value) )
						return $option_value;
					return FALSE;
				case 'id':
					return $option_value;
					break;
				case 'width':
					return $option_value;
					break;
				case 'height':
					return $option_value;
					break;
				case 'numberposts':
					$option_value = (int) $option_value;
					if( is_int($option_value) )
						return $option_value;
					return -1;
					break;
				case 'default_img_size':
					if( in_array($option_value, array('thumbnail', 'medium', 'large', 'full')) )
						return $option_value;
					break;
				default:
					return FALSE;
			}
			return $this->options[$option_name];
		}
		
		function save_options( $options ){
			// Takes an array or object and saves the options to the database after validating
			update_option('promotion_slider_options', $this->update_options($options));
		}
		
		function option_management(){
			// Get existing options array, if available
			$options = (array) $this->get_options();
			// If unavailable, create an empty array
			if( !$options ) $options = array();
			// Original options were stored individually, transfer these to our array
			if( get_option('time_delay') ) $options['time_delay'] = get_option('time_delay');
			if( get_option('auto_advance') ) $options['auto_advance'] = get_option('auto_advance');
			if( get_option('show_title') ) $options['show_title'] = get_option('show_title');
			if( get_option('show_excerpt') ) $options['show_excerpt'] = get_option('show_excerpt');
			if( get_option('nav_option') ) $options['nav_option'] = get_option('nav_option');
			if( get_option('disable_fancy_title') ) $options['disable_fancy_title'] = get_option('disable_fancy_title');
			if( get_option('disable_fancy_nav') ) $options['disable_fancy_nav'] = get_option('disable_fancy_nav');
			// Update auto_advance option due to change in values
			if( $options['auto_advance'] == true ) $options['auto_advance'] = 'auto_advance';
			elseif( $options['auto_advance'] == false ) $options['auto_advance'] = 'no_auto_advance';
			else $options['auto_advance'] = 'auto_advance';
			// Update depreciated options
			if( isset($options['nav_option']) ) $options['display_nav'] = $options['nav_option'];
			if( isset($options['disable_fancy_nav']) && $options['disable_fancy_nav'] != true ) $options['nav_option'] = 'fancy';
			if( isset($options['show_title']) && $options['show_title'] != true ) $options['display_title'] = 'none';
			elseif( isset($options['disable_fancy_title']) && $options['disable_fancy_title'] == true ) $options['display_title'] = 'default';
			elseif( isset($options['disable_fancy_title']) && $options['disable_fancy_title'] == false ) $options['display_title'] = 'fancy';
			if( $options['show_excerpt'] == true ) $options['display_excerpt'] = 'excerpt';
			else $options['display_excerpt'] = 'none';
			// Properly saves options and updates plugin version
			$this->save_options( $options );
			// Remove legacy options, if needed
			ps_legacy::remove_legacy_options();	
		}

	}
  
}

if( class_exists('promo_slider') ){
	new promo_slider();
}

?>