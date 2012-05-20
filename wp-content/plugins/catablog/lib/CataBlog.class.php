<?php
/**
 * CataBlog Class
 *
 * This file contains the core class for the CataBlog WordPress Plugin.
 * @author Zachary Segal <zac@illproductions.com>
 * @version 1.6.3
 * @package catablog
 */

/**
 * CataBlog, the core class that creates the custom post type and taxonomy, 
 * has the admin and frontend controller and most database manipulation.
 *
 * @package catablog
 * @author Zachary Segal
 */
class CataBlog {
	
	// plugin version number and blog url
	private $version     = "1.6.3";
	private $blog_url    = 'http://catablog.illproductions.com/';
	private $debug       = false;
	
	// wordpress custom post and taxonomy labels
	private $custom_post_name         = "catablog-items";
	private $custom_post_gallery_name = "catablog-gallery";
	private $custom_tax_name          = "catablog-terms";
	private $custom_user_meta_name    = "catablog_screen_settings";
	private $pagination_query_label   = "catablog-page";
	
	// wordpress database options
	private $options      = array();
	private $options_name = 'catablog-options';
	
	// user permission requirements
	private $user_level = 'edit_pages';
	
	// default image sizes TO BE DELETED
	private $default_thumbnail_size = 100;
	private $default_image_size     = 600;
	private $default_bg_color       = "#ffffff";
	
	// two private arrays for storing common file paths
	public $directories   = array();
	public $urls          = array();
	
	// default term name and variables to cache fetched terms from the database
	private $terms             = NULL;
	private $default_term      = NULL;
	private $default_term_name = "Uncategorized";
	
	// wether to load frontend css and js files
	private $load_support_files = true;
	private $wp_messages        = array();
	private $wp_error_messages  = array();
	
	public function __construct() {
		// get plugin options from wp database
		$this->options = $this->get_options();
		
		$wp_upload_dir = wp_upload_dir();
		$upload_directory = $wp_upload_dir['baseurl'];
		if (is_ssl()) {
			$upload_directory = str_replace('http://', 'https://', $wp_upload_dir['baseurl']);
		}
		
		// define common directories and files for the plugin
		$this->plugin_file               = WP_CONTENT_DIR . "/plugins/catablog/catablog.php";
		$this->directories['plugin']     = WP_CONTENT_DIR . "/plugins/catablog";
		$this->directories['css']        = WP_CONTENT_DIR . "/plugins/catablog/css";
		$this->directories['template']   = WP_CONTENT_DIR . "/plugins/catablog/templates";
		$this->directories['views']      = WP_CONTENT_DIR . "/plugins/catablog/templates/views";
		$this->directories['buttons']    = WP_CONTENT_DIR . "/plugins/catablog/templates/buttons";
		$this->directories['languages']  = WP_CONTENT_DIR . "/plugins/localization";
		
		$this->directories['wp_uploads'] = $wp_upload_dir['basedir'];
		$this->directories['uploads']    = $wp_upload_dir['basedir'] . "/catablog";
		
		$this->directories['originals']  = $wp_upload_dir['basedir'] . "/catablog/originals";
		$this->directories['thumbnails'] = $wp_upload_dir['basedir'] . "/catablog/thumbnails";
		$this->directories['fullsize']   = $wp_upload_dir['basedir'] . "/catablog/fullsize";
		$this->directories['user_views'] = $wp_upload_dir['basedir'] . "/catablog/templates";
		
		// define commen urls for the plugin
		$this->urls['plugin']     = content_url() . "/plugins/catablog";
		$this->urls['css']        = content_url() . "/plugins/catablog/css";
		$this->urls['javascript'] = content_url() . "/plugins/catablog/js";
		$this->urls['images']     = content_url() . "/plugins/catablog/images";
		$this->urls['template']   = content_url() . "/plugins/catablog/templates";
		$this->urls['views']      = content_url() . "/plugins/catablog/templates/views";
		$this->urls['buttons']    = content_url() . "/plugins/catablog/templates/buttons";
		
		$this->urls['originals']  = $upload_directory . "/catablog/originals";
		$this->urls['thumbnails'] = $upload_directory . "/catablog/thumbnails";
		$this->urls['fullsize']   = $upload_directory . "/catablog/fullsize";
		$this->urls['user_views'] = $upload_directory . "/catablog/templates";
	}
	
	public function getCustomPostName() {
		return $this->custom_post_name;
	}
	
	public function getCustomPostGalleryName() {
		return $this->custom_post_gallery_name;
	}
	
	public function getCustomTaxName() {
		return $this->custom_tax_name;
	}
	
	
	
	/*****************************************************
	**       - WORDPRESS HOOKS
	*****************************************************/
	public function registerWordPressHooks() {
		// register custom post type and taxonomy
		add_action('init', array(&$this, 'initialize_plugin'), 0);
		
		// register custom menus in the Admin Menu Bar
		add_action('wp_before_admin_bar_render', array(&$this, 'admin_bar_edit_button'), 99);
		add_action('wp_before_admin_bar_render', array(&$this, 'admin_bar_menu'), 100);
		
		// register custom sidebar widgets
		add_action( 'widgets_init', create_function('', 'return register_widget("CataBlogWidget");') );
		add_action( 'widgets_init', create_function('', 'return register_widget("CataBlogCategoryWidget");') );
		
		
		// register admin hooks
		if (is_admin()) {
			
			// forward the user to the catablog admin interface if a catablog item is loaded in the post editor (post.php)
			add_action('posts_selection', array(&$this, 'forward_if_catablog_item_in_post_editor'), 100);
			
			// register admin menus, stylesheets and javascript libraries
			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_print_styles', array(&$this, 'admin_print_styles'));
			add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
			
			// register help and screen settings panel
			add_filter('screen_settings', array(&$this, 'screen_settings'), 10, 2);
			add_filter('contextual_help', array(&$this, 'contextual_help'), 10, 2);
			
			$catablog_page       = strpos($_SERVER['QUERY_STRING'], 'page=catablog') !== false;
			$catablog_remove_page = strpos($_SERVER['QUERY_STRING'], 'page=catablog-remove') !== false;
			
			if ($catablog_page) {
				add_action('admin_init', array(&$this, 'admin_init'));
			}
			
			if ($catablog_page && !$catablog_remove_page) {
			 	add_action('init', array(&$this, 'setup'), 1);
			}
			
			if (!$catablog_remove_page) {
				add_action('init', array(&$this, 'upgrade'), 2);
			}
			
			// register admin ajax actions
			add_action('wp_ajax_catablog_micro_save', array($this, 'ajax_micro_save'));
			add_action('wp_ajax_catablog_update_screen_settings', array($this, 'ajax_update_screen_settings'));
			
			add_action('wp_ajax_catablog_new_category', array($this, 'ajax_new_category'));
			add_action('wp_ajax_catablog_edit_category', array($this, 'ajax_edit_category'));
			add_action('wp_ajax_catablog_delete_category', array($this, 'ajax_delete_category'));
			
			add_action('wp_ajax_catablog_flush_fullsize', array($this, 'ajax_flush_fullsize'));
			add_action('wp_ajax_catablog_render_images', array(&$this, 'ajax_render_images'));
			
			add_action('wp_ajax_catablog_delete_subimage', array(&$this, 'ajax_delete_subimage'));
			add_action('wp_ajax_catablog_delete_library', array(&$this, 'ajax_delete_library'));
			add_action('wp_ajax_catablog_delete_system', array(&$this, 'ajax_delete_system'));
		}
		
		// register frontend hooks
		else {
			add_action('wp_enqueue_scripts', array(&$this, 'frontend_init'));
			add_action('wp_head', array(&$this, 'frontend_header'));
			add_action('wp_footer', array(&$this, 'frontend_footer'));
			
			// add content and excerpt filters if the public feature is enabled
			$public_posts_enabled = (isset($this->options['public_posts']))? $this->options['public_posts'] : false;
			if ($public_posts_enabled) {
				add_filter('the_content', array(&$this, 'frontend_single_filter_content'), 12);
				add_filter('the_excerpt', array(&$this, 'frontend_single_filter_content'), 12);	
			}
		}
		
		// register shortcodes
		add_shortcode('catablog', array(&$this, 'frontend_shortcode_catablog'));
		add_shortcode('catablog_gallery', array(&$this, 'frontend_shortcode_catablog_gallery'));
		add_shortcode('catablog_categories', array(&$this, 'frontend_shortcode_catablog_categories'));
	}
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - REGISTER CUSTOM POST TYPE 
	*****************************************************/
	public function initialize_plugin() {
		
		// load in i18n file
		load_plugin_textdomain('catablog', false, '/catablog/localization');
		$this->default_term_name = __("Uncategorized", "catablog");
		
		
		// attempt to load public settings, default to private if options not available
		$public_posts_enabled = (isset($this->options['public_posts']))?     $this->options['public_posts'] : false;
		$public_posts_slug    = (isset($this->options['public_post_slug']))? array('slug'=>$this->options['public_post_slug']) : true;
		$public_tax_slug      = (isset($this->options['public_tax_slug']))?  array('slug'=>$this->options['public_tax_slug']) : true;
		
		
		// if saving options and public option enabled use post values instead of saved option values
		$catablog_options_page = strpos($_SERVER['QUERY_STRING'], 'page=catablog-options') !== false;
		if ($catablog_options_page && isset($_POST['public_posts'])) {
			$post_vars = array_map('stripslashes_deep', $_POST);
			$post_vars = array_map('trim', $post_vars);
			$public_posts_slug = array('slug'=>$post_vars['public_post_slug']);
			$public_tax_slug   = array('slug'=>$post_vars['public_tax_slug']);
		}
		
		
		// create the catalog item custom post type
		$params = array();
		$params['label']               = __("CataBlog Item", 'catablog');
		$params['public']              = $public_posts_enabled;
		$params['show_ui']             = false;
		$params['show_in_nav_menus']   = false;
		$params['supports']            = array('title', 'editor');
		$params['description']         = __("A Catalog Item, generated by CataBlog.", 'catablog');
		$params['hierarchical']        = false;
		$params['taxonomies']          = array($this->custom_tax_name);
		$params['menu_position']       = 45;
		$params['menu_icon']           = $this->urls['plugin']."/images/catablog-icon-16.png";
		$params['rewrite']             = $public_posts_slug;
		register_post_type($this->custom_post_name, $params);
		
		
		// create the catalog categories custom taxonomy
		$params = array();
		$params['label']                = __("CataBlog Category", 'catablog');
		$params['public']                = $public_posts_enabled;
		$params['show_ui']               = false;
		$params['show_tagcloud']         = true;
		$params['hierarchical']          = false;
		$params['rewrite']               = $public_tax_slug;
		register_taxonomy($this->custom_tax_name, $this->custom_post_name, $params);
		
		
		// create the gallery custom post type
		$params = array();
		$params['label']               = __("CataBlog Gallery", 'catablog');
		$params['public']              = false;
		$params['show_ui']             = false;
		$params['show_in_nav_menus']   = false;
		$params['supports']            = array('title', 'editor');
		$params['description']         = __("A Catalog Gallery, generated by CataBlog.", 'catablog');
		$params['hierarchical']        = false;
		$params['taxonomies']          = array();
		$params['menu_position']       = 46;
		$params['menu_icon']           = $this->urls['plugin']."/images/catablog-icon-16.png";
		$params['rewrite']             = false;
		register_post_type($this->custom_post_gallery_name, $params);
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - ADMIN MENU AND HOOKS
	*****************************************************/
	public function admin_init() {
		
		
		// go straigt to create new item action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-create') !== false) {
			$this->admin_create(true);
		}
		
		// go straigt to save item action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-save') !== false) {
			$this->admin_save(true);
		}

		// go straigt to delete item action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-delete') !== false) {
			$this->admin_delete(true);
		}
		
		// go straigt to bulk edit item action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-bulkedit') !== false) {
			$this->admin_bulk_edit(true);
		}
		
		// go straigt to create gallery action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-gallery-create') !== false) {
			$this->admin_gallery_create(true);
		}
		
		// go straigt to save gallery action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-gallery-save') !== false) {
			$this->admin_gallery_save(true);
		}

		// go straigt to append gallery action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-gallery-append') !== false) {
			$this->admin_gallery_append_new_items(true);
		}

		
		// go straigt to delete gallery action, no interface
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-gallery-delete') !== false) {
			$this->admin_gallery_delete(true);
		}
		
		// if export action is being called go directly to admin_export method 
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-export') !== false) {
			$this->admin_export();
		}
		
		// go striaght to replace main image action
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-replace-image') !== false) {
			$this->admin_replace_main_image(true);
		}
		
		// go straight to add subimage action
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-add-subimage') !== false) {
			$this->admin_add_subimage(true);
		}
		
		// go straight to create template action
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-templates-create') !== false) {
			$this->admin_templates_create(true);
		}
		
		// go straight to save template action
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-templates-save') !== false) {
			$this->admin_templates_save(true);
		}
		
		// go straight to delete template action
		if(strpos($_SERVER['QUERY_STRING'], 'catablog-templates-delete') !== false) {
			$this->admin_templates_delete(true);
		}
		
		
		// set cookie to remember the admin view settings
		if(isset($_GET['page']) && $_GET['page'] == 'catablog') {
			$options = array('sort', 'order', 'view');
			$option_defaults = array(
				'sort'  => 'date',
				'order' => 'asc',
				'view'  => 'list',
			);
			
			foreach ($option_defaults as $option => $default_value) {
				if(isset($_GET[$option])) {
					setCookie("catablog-view-cookie[$option]", $_REQUEST[$option], (time()+36000000));
				}
				else {
					setCookie("catablog-view-cookie[$option]", $default_value, (time()+36000000));
				}
			}
			// remove old view cookie if still present
			setCookie("catablog-view-cookie", false, (time() - 36000));
		}
	}
	
	public function forward_if_catablog_item_in_post_editor() {
		global $post, $pagenow;
		
		if ($pagenow == 'post.php') {
			if (isset($post)) {
				if ($post->post_type === $this->custom_post_name) {
					header('Location: admin.php?page=catablog&id='.$_GET['post']); die;
				}
			}
		}
	}
	
	public function admin_menu() {
		// register main plugin menu
		add_menu_page("CataBlog Library", "CataBlog", $this->user_level, 'catablog', array(&$this, 'admin_library'), $this->urls['plugin']."/images/catablog-icon-16.png");
		
		// register main plugin pages
		add_submenu_page('catablog', __("CataBlog Library", 'catablog'), __('Library', 'catablog'), $this->user_level, 'catablog', array(&$this, 'admin_library'));
		add_submenu_page('catablog', __("Add New CataBlog Entry", 'catablog'), __('Add New', 'catablog'), $this->user_level, 'catablog-upload', array(&$this, 'admin_new'));
		add_submenu_page('catablog', __("CataBlog Galleries", 'catablog'), __('Galleries', 'catablog'), $this->user_level, 'catablog-gallery', array(&$this, 'admin_galleries'));
		add_submenu_page('catablog', __("CataBlog Templates", 'catablog'), __('Templates', 'catablog'), $this->user_level, 'catablog-templates', array(&$this, 'admin_templates'));
		add_submenu_page('catablog', __("CataBlog Options", 'catablog'), __('Options', 'catablog'), $this->user_level, 'catablog-options', array(&$this, 'admin_options'));
		add_submenu_page('catablog', __("About CataBlog", 'catablog'), __('About', 'catablog'), $this->user_level, 'catablog-about', array(&$this, 'admin_about'));
		
		// register create/edit/delete catalog item actions
		add_submenu_page('catablog-hidden', "Create CataBlog Item", "Create", $this->user_level, 'catablog-create', array(&$this, 'admin_create'));
		add_submenu_page('catablog-hidden', "Save CataBlog Item", "Save", $this->user_level, 'catablog-save', array(&$this, 'admin_save'));
		add_submenu_page('catablog-hidden', "Delete CataBlog Item", "Delete", $this->user_level, 'catablog-delete', array(&$this, 'admin_delete'));
		add_submenu_page('catablog-hidden', "Bulk Edit CataBlog Items", "Bulk", $this->user_level, 'catablog-bulkedit', array(&$this, 'admin_bulk_edit'));
		add_submenu_page('catablog-hidden', "Replace Main Image", "Replace", $this->user_level, 'catablog-replace-image', array(&$this, 'admin_replace_main_image'));
		add_submenu_page('catablog-hidden', "Add SubImage", "SubImage", $this->user_level, 'catablog-add-subimage', array(&$this, 'admin_add_subimage'));
		
		// register create/edit/delete catalog gallery actions
		add_submenu_page('catablog-hidden', "Create CataBlog Gallery", "Create Gallery", $this->user_level, 'catablog-gallery-create', array(&$this, 'admin_gallery_create'));
		add_submenu_page('catablog-hidden', "Save CataBlog Gallery", "Save Gallery", $this->user_level, 'catablog-gallery-save', array(&$this, 'admin_gallery_save'));
		add_submenu_page('catablog-hidden', "Append To CataBlog Gallery", "Append To Gallery", $this->user_level, 'catablog-gallery-append', array(&$this, 'admin_gallery_append_new_items'));
		add_submenu_page('catablog-hidden', "Delete CataBlog Gallery", "Delete Gallery", $this->user_level, 'catablog-gallery-delete', array(&$this, 'admin_gallery_delete'));
		
		// register import/export page actions to hidden menu
		add_submenu_page('catablog-hidden', "CataBlog Import", "Import", $this->user_level, 'catablog-import', array(&$this, 'admin_import'));
		add_submenu_page('catablog-hidden', "CataBlog Export", "Export", $this->user_level, 'catablog-export', array(&$this, 'admin_export'));
		add_submenu_page('catablog-hidden', "CataBlog Unlock Folders", "Unlock Folders", $this->user_level, 'catablog-unlock-folders', array(&$this, 'admin_unlock_folders'));
		add_submenu_page('catablog-hidden', "CataBlog Lock Folders", "Lock Folders", $this->user_level, 'catablog-lock-folders', array(&$this, 'admin_lock_folders'));
		add_submenu_page('catablog-hidden', "CataBlog Regenerate Images", "Regenerate Images", $this->user_level, 'catablog-regenerate-images', array(&$this, 'admin_regenerate_images'));
		add_submenu_page('catablog-hidden', "CataBlog Rescan Images", "Rescan Images Folder", $this->user_level, 'catablog-rescan-images', array(&$this, 'admin_rescan_images'));
		
		// register template modification actions to hidden menu
		add_submenu_page('catablog-hidden', "Create CataBlog Template", "Create Template", $this->user_level, 'catablog-templates-create', array(&$this, 'admin_templates_create'));
		add_submenu_page('catablog-hidden', "Save CataBlog Template", "Save Template", $this->user_level, 'catablog-templates-save', array(&$this, 'admin_templates_save'));
		add_submenu_page('catablog-hidden', "Delete CataBlog Template", "Delete Template", $this->user_level, 'catablog-templates-delete', array(&$this, 'admin_templates_delete'));
		
		// register about page actions to hidden menu
		// add_submenu_page('catablog-hidden', "CataBlog Install", "Install", $this->user_level, 'catablog-install', array(&$this, 'admin_install'));
		add_submenu_page('catablog-hidden', "CataBlog Remove", "Remove", $this->user_level, 'catablog-remove', array(&$this, 'admin_remove_all'));
	}
	
	
	public function admin_print_styles() {
		$catablog_page = strpos($_SERVER['QUERY_STRING'], 'page=catablog') !== false;
		if ($catablog_page) {
			wp_enqueue_style('farbtastic');
			wp_enqueue_style('catablog-admin-css', $this->urls['css'] . '/catablog-admin.css', false, $this->version);
		}
	}
	
	
	public function admin_enqueue_scripts() {
		$catablog_page = strpos($_SERVER['QUERY_STRING'], 'page=catablog') !== false;
		if ($catablog_page) {
			wp_enqueue_script('jquery');		
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-effects-core');
			wp_enqueue_script('farbtastic');
			wp_enqueue_script('catablog-admin', $this->urls['javascript'] . '/catablog-admin.js', array('jquery'), $this->version);
		}
		
		$catablog_upload_page = strpos($_SERVER['QUERY_STRING'], 'page=catablog-upload') !== false;
		if ($catablog_page) {
			wp_enqueue_script('swfupload');
			wp_enqueue_script('swfobject');
			wp_enqueue_script('catablog-handlers', $this->urls['javascript'] . '/catablog.handlers.js', array('jquery'), '1.0');
		}
	}
	
	public function admin_bar_edit_button() {
		global $post;
		
		if (is_object($post)) {
			if ($post->post_type == $this->custom_post_name) {
				global $wp_admin_bar;
				$wp_admin_bar->add_menu( array( 'id' => 'edit-catablog-entry', 'title' => __('Edit CataBlog Entry', 'catablog'), 'href' => get_admin_url(null, 'admin.php?page=catablog&id='.$post->ID), ) );	
			}
		}
	}
	
	public function admin_bar_menu() {
		// if user can't use catablog do not register the admin bar items
		if (!current_user_can($this->user_level)) {
			return false;
		}
		
		global $wp_admin_bar;
		
		// add a CataBlog menu to the Admin Menu Bar
		$wp_admin_bar->add_menu( array( 'id' => 'catablog-menu', 'title' => __( 'CataBlog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog'), ) );
		$wp_admin_bar->add_menu( array( 'parent' => 'catablog-menu', 'id' => 'catablog-library', 'title' => __( 'Library', 'catablog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog'), ) );
		$wp_admin_bar->add_menu( array( 'parent' => 'catablog-menu', 'id' => 'catablog-new-entry', 'title' => __( 'Add New', 'catablog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog-upload'), ) );
		$wp_admin_bar->add_menu( array( 'parent' => 'catablog-menu', 'id' => 'catablog-gallery', 'title' => __( 'Galleries', 'catablog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog-gallery'), ) );
		$wp_admin_bar->add_menu( array( 'parent' => 'catablog-menu', 'id' => 'catablog-templates', 'title' => __( 'Templates', 'catablog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog-templates'), ) );
		$wp_admin_bar->add_menu( array( 'parent' => 'catablog-menu', 'id' => 'catablog-options', 'title' => __( 'Options', 'catablog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog-options'), ) );
		$wp_admin_bar->add_menu( array( 'parent' => 'catablog-menu', 'id' => 'catablog-about', 'title' => __( 'About', 'catablog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog-about'), ) );
		
		// add a CataBlog Entry sub menu item to the Admin Menu Bar Add New menu
		$wp_admin_bar->add_menu( array( 'parent' => 'new-content', 'id' => 'new-catablog-entry', 'title' => __( 'CataBlog Entry', 'catablog' ), 'href' => get_admin_url(null, 'admin.php?page=catablog-upload'), ) );
		
	}
	
	
	public function screen_settings($current, $screen) {
		if (!isset($screen->id)) {
			return false;
		}
		
		switch($screen->id) {
			case 'toplevel_page_catablog':
				ob_start();
				include_once($this->directories['template'] . '/admin-screen-options-library.php');
				return ob_get_clean();
				break;
			case 'catablog_page_catablog-upload':
				ob_start();
				include_once($this->directories['template'] . '/admin-screen-options-add-new.php');
				return ob_get_clean();
				break;
			case 'catablog_page_catablog-gallery':
				ob_start();
				include_once($this->directories['template'] . '/admin-screen-options-galleries.php');
				return ob_get_clean();
				break;
			
		}
		
		
	}
	
	
	public function contextual_help($contextual_help, $screen) {
		if (!isset($screen)) {
			return false;
		}
		
		if (strpos($screen, 'catablog') !== false) {
			ob_start();
			include_once($this->directories['template'] . '/admin-contextual-help.php');
			return ob_get_clean();
		}
		
		return $contextual_help;
	}
	
	
	
	
	
	
	
	
	
	
	

	
	/*****************************************************
	**       - ADMIN PAGES
	*****************************************************/
	
	public function admin_library() {
		
		
		// if id is set show edit form
		if (isset($_GET['id'])) {
			$this->admin_edit();
			return false;
		}
		
		// order and pagination defaults
		$sort   = 'date';
		$order  = 'desc';
		$paged  = 1;
		$offset = 0;
		$limit  = 20;
		$category_filter = false;
		
		
		$user = wp_get_current_user();
		$user_settings = get_user_meta($user->ID, $this->custom_user_meta_name, true);
		$screen_settings = $user_settings['library'];
		
		if (is_numeric($screen_settings['limit'])) {
			$limit = $screen_settings['limit'];
		}
		
		if (!is_array($screen_settings['hide-columns'])) {
			$screen_settings['hide-columns'] = array();
		}

		$table_columns = array('description', 'link', 'price', 'product_code', 'categories', 'order', 'date');
		foreach ($table_columns as $table_column) {
			// creates variable names like $description_col_class
			if (in_array($table_column, $screen_settings['hide-columns'])) {
				${$table_column."_col_class"} = "hide";
			}
			else {
				${$table_column."_col_class"} = "";
			}
		}		
		
		
		$selected_term = false;//$this->get_default_term();
		if (isset($_GET['category']) && is_numeric($_GET['category'])) {
			if ($_GET['category'] > 0) {
				$selected_term = get_term_by('id', $_GET['category'], $this->custom_tax_name);
			}
		}
		
		if (isset($_GET['sort'])) {
			$sort = $_GET['sort'];
		}
		
		if (isset($_GET['order'])) {
			$order = $_GET['order'];
		}
		
		if (isset($_GET['paged'])) {
			$page_number = (int) $_GET['paged'];
			$paged = $page_number;
		}
		
		
		if ($selected_term) {
			$category_filter = array($selected_term->term_id);
		}
		
		$sort = 'date';
		$order = 'asc';
		$view = 'list';
		if (isset($_COOKIE['catablog-view-cookie'])) {
			$cookie = $_COOKIE['catablog-view-cookie'];
			$sort  = $cookie['sort'];
			$order = $cookie['order'];
			$view  = $cookie['view'];
			
			if (is_array($cookie)) {
				foreach ($cookie as $key => $value) {
					if (isset($_GET[$key])) {
						${$key} = $_GET[$key];
					}
				}
			}
		}
		
		// pagination variables
		// echo $current_page        = ($paged == 0)? 1 : $paged;
		$total_catalog_items = wp_count_posts($this->custom_post_name)->publish;
		$category_get_param  = "";
		
		if ($selected_term) {
			$total_catalog_items = $selected_term->count;
			$category_get_param  = "&amp;category=" . $selected_term->term_id;
		}
		
		
		$total_catalog_pages = ceil($total_catalog_items / $limit);
		
		if ($paged < 1) {
			$paged = 1;
		}
		if ($paged > $total_catalog_pages) {
			$paged = $total_catalog_pages;
		}
		
		$first_catalog_page_link = "?page=catablog$category_get_param";
		$prev_catalog_page_link  = "?page=catablog$category_get_param&amp;paged=" . (($paged > 1)? ($paged - 1) : 1);
		$next_catalog_page_link  = "?page=catablog$category_get_param&amp;paged=" . (($paged < $total_catalog_pages)? ($paged + 1) : $total_catalog_pages);
		$last_catalog_page_link  = "?page=catablog$category_get_param&amp;paged=" . $total_catalog_pages;
		
		$offset = ($paged - 1) * $limit;
		
		$results = CataBlogItem::getItems($category_filter, 'IN', $sort, $order, $offset, $limit);
		
		if (isset($_GET['message'])) {
			switch ($_GET['message']) {
				case 1:
					$this->wp_message(__("Changes Saved Successfully.", 'catablog'));
					break;
				case 2:
					$this->wp_message(__("Catalog Item Deleted Successfully.", 'catablog'));
					break;
				case 3:
					$this->wp_error(__("Could Not Delete Item Because ID was non existent.", 'catablog'));
					break;
				case 4:
					$this->wp_error(__('Could not verify bulk edit action nonce, please refresh page and try again.', 'catablog'));
					break;
				case 5:
					$this->wp_message(__('Bulk categories edit performed successfully.', 'catablog'));
					break;
				case 6:
					$this->wp_message(__('Bulk delete performed successfully.', 'catablog'));
					break;
				case 7:
					$this->wp_message(__('Reset all data successfully.', 'catablog'));
					break;
				case 8:
					$this->wp_message(__('Catalog items added to Gallery successfully.', 'catablog'));
					break;
				case 9:
					$this->wp_error(__('Could not find the gallery to add items to, please make sure you select a gallery in the drop down menu and try again.', 'catablog'));
					break;
				case 10:
				 	$this->wp_error(__('Could not validate the gallery, please modify the gallery and try again.', 'catablog'));
					break;
			}
		}
		
		
		include_once($this->directories['template'] . '/admin-library.php');
	}
	
	public function admin_edit() {
		if (isset($_GET['id'])) {
			
			if (isset($_GET['message'])) {
				switch ($_GET['message']) {
					case 1:
						
						$message = __("Changes Saved Successfully.", 'catablog');
						
						if ($this->options['public_posts']) {
							$permalink = get_permalink($_GET['id']);
							$message .= sprintf(" (<a href='$permalink'>%s</a>)", __("View Now"));
						}
						
						$this->wp_message($message);
						break;
				}
			}
			
			
			$result = CataBlogItem::getItem($_GET['id']);
			if (!$result) {
				include_once($this->directories['template'] . '/admin-404.php');
				return false;
			}
			
			include_once($this->directories['template'] . '/admin-edit.php');
		}
	}
	
	public function admin_new() {
		if (function_exists('is_upload_space_available') && is_upload_space_available() == false) {
			include_once($this->directories['template'] . '/admin-discfull.php');
		}
		else {
			include_once($this->directories['template'] . '/admin-new.php');
		}		
	}
	
	public function admin_options() {
		$recalculate_images = false;
		
		if (isset($_REQUEST['save'])) {
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_options_nonce'], 'catablog_options' );
			if ($nonce_verified) {
				
				// strip slashes from post values
				$post_vars = array_map('stripslashes_deep', $_POST);
				$post_vars = array_map('trim', $post_vars);
				
				
				// transform link target and rel value so it only contains alphanumeric, hyphen, underscore
				// $post_vars['link_target'] = preg_replace('/[^a-z0-9_-]/', '', $post_vars['link_target']);
				// $post_vars['link_relationship'] = preg_replace('/[^a-z0-9_-]/', '', $post_vars['link_relationship']);
				
				
				// transform catalog slugs
				if ($this->string_length($post_vars['public_post_slug']) > 0) {
					$post_vars['public_post_slug'] = sanitize_title_with_dashes($post_vars['public_post_slug']);
				}
				else {
					$post_vars['public_post_slug'] = $this->custom_post_name;
				}
				if ($this->string_length($post_vars['public_tax_slug']) > 0) {
					$post_vars['public_tax_slug'] = sanitize_title_with_dashes($post_vars['public_tax_slug']);
				}
				else {
					$post_vars['public_tax_slug'] = $this->custom_tax_name;
				}
				
				// make sure public post and tax slugs are not the same
				if ($post_vars['public_post_slug'] == $post_vars['public_tax_slug']) {
					$this->wp_error(__('Form Validation Error. Please make sure that the individual and category public page slugs are not identical.', 'catablog'));
				}
				
				// flush the rewrite rules for public option updates
				flush_rewrite_rules(false);
				
				
				// set default values for post message and image recalculation
				$save_message = __("CataBlog Options Saved", 'catablog');
				
				$fullsize_dir = new CataBlogDirectory($this->directories['fullsize']);
				
				// get image size and rendering differences
				$image_width_different  = $post_vars['thumbnail_width'] != $this->options['thumbnail-width'];
				$image_height_different = $post_vars['thumbnail_height'] != $this->options['thumbnail-height'];
				$image_size_different   = ($image_width_different || $image_height_different);
				
				$bg_color_different     = $post_vars['bg_color'] != $this->options['background-color'];
				$keep_ratio_different   = isset($post_vars['keep_aspect_ratio']) != $this->options['keep-aspect-ratio'];
				$lightbox_enabled       = (isset($post_vars['lightbox_enabled'])) && (isset($post_vars['lightbox_render'])) && ($fullsize_dir->getCount() < 1);
				$fullsize_different     = (isset($post_vars['lightbox_enabled'])) && $post_vars['lightbox_image_size'] != $this->options['image-size'];
				
				// set recalculation of thumbnails and update post message
				if ($image_size_different || $bg_color_different || $keep_ratio_different || $lightbox_enabled || $fullsize_different) {
					$recalculate_images = true;
				}
				
				// save new plugins options to database
				$this->options['thumbnail-width']      = $post_vars['thumbnail_width'];
				$this->options['thumbnail-height']     = $post_vars['thumbnail_height'];
				$this->options['image-size']           = $post_vars['lightbox_image_size'];
				$this->options['lightbox-enabled']     = isset($post_vars['lightbox_enabled']);
				$this->options['lightbox-navigation']  = isset($post_vars['lightbox_navigation']);
				$this->options['lightbox-render']      = isset($post_vars['lightbox_render']);
				$this->options['lightbox-selector']    = $post_vars['lightbox_selector'];
				$this->options['background-color']     = $post_vars['bg_color'];
				$this->options['keep-aspect-ratio']    = isset($post_vars['keep_aspect_ratio']);
				$this->options['link-target']          = strip_tags($post_vars['link_target']);
				$this->options['link-relationship']    = strip_tags($post_vars['link_relationship']);
				$this->options['filter-description']   = isset($post_vars['wp-filters-enabled']);
				$this->options['nl2br-description']    = isset($post_vars['nl2br-enabled']);
				$this->options['excerpt-length']       = $post_vars['excerpt_length'];
				$this->options['public_posts']         = isset($post_vars['public_posts']);
				$this->options['public_post_slug']     = $post_vars['public_post_slug'];
				$this->options['public_tax_slug']      = $post_vars['public_tax_slug'];
				$this->options['nav-prev-label']       = $post_vars['nav_prev_label'];
				$this->options['nav-next-label']       = $post_vars['nav_next_label'];
				$this->options['nav-location']         = $post_vars['nav_location'];
				$this->options['nav-show-meta']        = isset($post_vars['nav_show_meta']);
				
				$this->save_wp_options();
				
				// recalculate thumbnail and fullsize images if necessary
				if ($recalculate_images) {
					$save_message .= " - ";
					$link = wp_nonce_url('admin.php?page=catablog-regenerate-images', 'catablog-regenerate-images');
					$save_message .= sprintf(__("You have changed your image settings, please %sRegenerate All Images Now%s", 'catablog'), '<a href="'.$link.'">', '</a>');
				}
				
				$this->wp_message($save_message);
			}
			else {
				$this->wp_error(__('Form Validation Error. Please reload the page and try again.', 'catablog'));
			}
		}
		
		if (!isset($this->options['thumbnail-width'])) {
			$this->options['thumbnail-width'] = $this->options['thumbnail-size'];
		}
		if (!isset($this->options['thumbnail-height'])) {
			$this->options['thumbnail-height'] = $this->options['thumbnail-size'];
		}
		
		$thumbnail_width              = $this->options['thumbnail-width'];
		$thumbnail_height             = $this->options['thumbnail-height'];
		$lightbox_size                = $this->options['image-size'];
		$lightbox_enabled             = $this->options['lightbox-enabled'];
		$lightbox_navigation          = $this->options['lightbox-navigation'];
		$lightbox_render              = $this->options['lightbox-render'];
		$lightbox_selector            = $this->options['lightbox-selector'];
		$background_color             = $this->options['background-color'];
		$keep_aspect_ratio            = $this->options['keep-aspect-ratio'];
		$link_target                  = $this->options['link-target'];
		$link_relationship            = $this->options['link-relationship'];
		$wp_filters_enabled           = $this->options['filter-description'];
		$nl2br_enabled                = $this->options['nl2br-description'];
		$excerpt_length               = $this->options['excerpt-length'];
		$public_posts_enabled         = $this->options['public_posts'];
		$public_posts_slug            = $this->options['public_post_slug'];
		$public_tax_slug              = $this->options['public_tax_slug'];
		$nav_prev_label               = $this->options['nav-prev-label'];
		$nav_next_label               = $this->options['nav-next-label'];
		$nav_location                 = $this->options['nav-location'];
		$nav_show_meta                = $this->options['nav-show-meta'];

		
		include_once($this->directories['template'] . '/admin-options.php');
	}
	
	

	
	
	public function admin_templates() {
		if (isset($_GET['message'])) {
			switch ($_GET['message']) {
				case 1:
					$this->wp_message(__("Template Changes Saved Successfully.", 'catablog'));
					break;
				case 2:
					$this->wp_message(__('Template Created Successfully.', 'catablog'));
					break;
				case 3:
					$this->wp_error(__('Form Validation Error. Please reload the page and try again.', 'catablog'));
					break;
				case 4:
					$this->wp_error(__('Form Validation Error. Please reload the page and try again.', 'catablog'));
					break;
				case 5:
					$this->wp_error(sprintf(__('File Write Error. Please make sure WordPress can write to this directory:<br /><code>%s</code>', 'catablog'), $this->directories['user_views']));
					break;
				case 6:
					$this->wp_error(__('File Creation Error. A template already exists with that name.', 'catablog'));
					break;
				case 7:
					$this->wp_error(__('File Creation Error. A template name may only consist of underscores, hyphens and alphanumeric characters.', 'catablog'));
					break;
				case 8:
					$this->wp_message(__('Template Deleted Successfully.', 'catablog'));
					break;
			}
		}
		
		$views = new CataBlogDirectory($this->directories['user_views']);
		
		include_once($this->directories['template'] . '/admin-templates-editor.php');
	}
	
	public function admin_templates_create($init_run=true) {
		if (isset($_REQUEST['save'])) {
			
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_add_template_nonce'], 'catablog_add_template' );
			if ($nonce_verified) {
				
				// check id the file name contains only valid characters
				$filename = $_REQUEST['new_template_name'];
				if (preg_match('/[^a-z0-9\_\-]/i', $filename)) {
					header('Location: admin.php?page=catablog-templates&message=7'); die;
				}
				
				// add htm file extension and build file path
				$filename .= '.htm';
				$filepath = $this->directories['user_views'] . '/' . $filename;
				
				// check if a file already exists with the name
				if (is_file($filepath)) {
					header('Location: admin.php?page=catablog-templates&message=6'); die;
				}
				
				// open, write the blank file and then close the file
				$file = fopen($filepath, "w"); 
				if ($file === false) {
					header('Location: admin.php?page=catablog-templates&message=5'); die;
				}
				if (fwrite($file, "") === false) {
					header('Location: admin.php?page=catablog-templates&message=5'); die;
				}
				fclose($file);
				
				// redirect back to templates panel
				header('Location: admin.php?page=catablog-templates&message=2#'.$filename); die;
			}
			else {
				header('Location: admin.php?page=catablog-templates&message=4'); die;
			}
		}
	}
	
	public function admin_templates_save($init_run=true) {
		if (isset($_REQUEST['save'])) {
			
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_templates_save_nonce'], 'catablog_templates_save' );
			if ($nonce_verified) {
				
				$filename = $_REQUEST['catablog-template-filename'];
				$filepath = $this->directories['user_views'] . '/' . $filename;
				
				$file = fopen($filepath, "w");
				
				if ($file === false) {
					header('Location: admin.php?page=catablog-templates&message=5'); die;
				}
				
				$post_vars = array_map('stripslashes_deep', $_POST);
				if (isset($post_vars['template-code'])) {
					if (fwrite($file, $post_vars['template-code']) === false) {
						header('Location: admin.php?page=catablog-templates&message=5'); die;
					}
				}
				
				fclose($file);
				
				header('Location: admin.php?page=catablog-templates&message=1#'.$filename); die;
			}
			else {
				header('Location: admin.php?page=catablog-templates&message=3'); die;
			}
		}
	}
	
	public function admin_templates_delete($init_run=true) {
		if (isset($_REQUEST['delete'])) {
			
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_templates_delete_nonce'], 'catablog_templates_delete');
			if ($nonce_verified) {
				
				$filename = $_REQUEST['catablog-template-filename'];
				$filepath = $this->directories['user_views'] . '/' . $filename;
				
				$delete_success = unlink($filepath);
				if ($delete_success === false) {
					header('Location: admin.php?page=catablog-templates&message=5'); die;
				}
				
				header('Location: admin.php?page=catablog-templates&message=8'); die;
			}
			else {
				header('Location: admin.php?page=catablog-templates&message=3'); die;
			}
		}
	}
	
	
	
	
	public function admin_about() {
		global $wpdb;
		
		$thumbnail_size = __('not present', 'catablog');
		$fullsize_size  = __('not present', 'catablog');
		$original_size  = __('not present', 'catablog');
		
		$thumb_dir = new CataBlogDirectory($this->directories['thumbnails']);
		$fullsize_dir = new CataBlogDirectory($this->directories['fullsize']);
		$original_dir = new CataBlogDirectory($this->directories['originals']);
		
		if ($thumb_dir->isDirectory()) {
			$thumbnail_size = round(($thumb_dir->getDirectorySize() / (1024 * 1024)), 2) . " MB";
		}
		if ($fullsize_dir->isDirectory()) {
			$fullsize_size  = round(($fullsize_dir->getDirectorySize() / (1024 * 1024)), 2) . " MB";
		}
		if ($original_dir->isDirectory()) {
			$original_size  = round(($original_dir->getDirectorySize() / (1024 * 1024)), 2) . " MB";
		}
		
		$gd_info = gd_info();
		
		$stats = array();
		$stats['CataBlog_Version'] = $this->version;
		$stats['MySQL_Version']    = $wpdb->get_var("SELECT version()");
		$stats['PHP_Version']      = phpversion();
		$stats['GD_Version']       = $gd_info['GD Version'];
		
		$stats['PHP_Memory_Usage'] = round((memory_get_peak_usage(true) / (1024 * 1024)), 2) . " MB";
		$stats['PHP_Memory_Limit'] = preg_replace('/[^0-9]/', '', ini_get('memory_limit')) . " MB";
		
		$stats['Max_Uploaded_File_Size']     = ini_get('upload_max_filesize');
		$stats['Max_Post_size']              = ini_get('post_max_size');
		
		$stats['Thumbnail_Disc_Usage']       = $thumbnail_size;
		$stats['Full_Size_Disc_Usage']       = $fullsize_size;
		$stats['Original_Upload_Disc_Usage'] = $original_size;
		$stats['Total_Disc_Usage']   = (round($thumbnail_size, 2) + round($fullsize_size, 2) + round($original_size, 2)) . " MB";
		
		include_once($this->directories['template'] . '/admin-about.php');
	}
	
	
	
	
	
	
	
	
	

	/*****************************************************
	**       - ADMIN GALLERY ACTIONS
	*****************************************************/
	public function admin_galleries() {
		
		// if id is set show edit form
		if (isset($_GET['id'])) {
			$this->admin_gallery_edit();
			return false;
		}
		
		// order and pagination defaults
		$sort   = 'title';
		$order  = 'asc';
		$paged  = 1;
		$offset = 0;
		$limit  = 20;
		
		$user = wp_get_current_user();
		$screen_settings = get_user_meta($user->ID, $this->custom_user_meta_name, true);
		$screen_settings = $screen_settings['gallery'];
		
		$table_columns = array('description', 'size', 'shortcode', 'date');
		foreach ($table_columns as $table_column) {
			// creates variable names like $description_col_class
			if (in_array($table_column, $screen_settings['hide-columns'])) {
				${$table_column."_col_class"} = "hide";
			}
			else {
				${$table_column."_col_class"} = "";
			}
		}
		
		if (isset($screen_settings['limit'])) {
			$limit = $screen_settings['limit'];
		}
		
		if (isset($_GET['sort'])) {
			$sort = $_GET['sort'];
		}
		
		if (isset($_GET['order'])) {
			$order = $_GET['order'];
		}
		
		if (isset($_GET['paged'])) {
			$page_number = intval($_GET['paged']);
			$paged = $page_number;
		}
		
		$total_gallery_count = wp_count_posts($this->custom_post_gallery_name)->publish;
		$total_gallery_pages = ceil($total_gallery_count / $limit);
		
		if ($paged < 1) {
			$paged = 1;
		}
		if ($paged > $total_gallery_pages) {
			$paged = $total_gallery_pages;
		}
		
		$first_gallery_page_link = "?page=catablog-gallery";
		$prev_gallery_page_link  = "?page=catablog-gallery&amp;paged=" . (($paged > 1)? ($paged - 1) : 1);
		$next_gallery_page_link  = "?page=catablog-gallery&amp;paged=" . (($paged < $total_gallery_pages)? ($paged + 1) : $total_gallery_pages);
		$last_gallery_page_link  = "?page=catablog-gallery&amp;paged=" . $total_gallery_pages;
		
		$offset = ($paged - 1) * $limit;
		
		$galleries = CataBlogGallery::getGalleries($sort, $order, $offset, $limit);
		
		if (isset($_GET['message'])) {
			switch ($_GET['message']) {
				case 1:
					$this->wp_message(__("Gallery Created Successfully.", 'catablog'));
					break;
				case 2:
					$this->wp_error(__("Form Validation Error. Please check your gallery title and try again.", 'catablog'));
					break;
				case 3:
					$this->wp_error(__("Form Validation Error. Please reload the page and try again.", 'catablog'));
					break;
				case 4:
					$this->wp_message(__("Gallery Deleted Successfully.", 'catablog'));
					break;
				case 5:
					$this->wp_error(__("Could Not Delete Gallery Because ID was non existent.", 'catablog'));
					break;
				case 6:
					$this->wp_message(__('Multiple Galleries Deleted Successfully.', 'catablog'));
					break;
			}
		}
		
		include_once($this->directories['template'] . '/admin-galleries.php');
	}
	
	
	
	public function admin_gallery_create($init_run=false) {
		$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_create_gallery_nonce'], 'catablog_create_gallery' );
		if ($nonce_verified) {
			
			$post_vars = $_POST;
			$post_vars = array_map('stripslashes_deep', $post_vars);
			
			$new_gallery = new CataBlogGallery($post_vars);
			
			$validate = $new_gallery->validate();
			if ($validate === true) {
				$new_gallery->save();
				header('Location: admin.php?page=catablog-gallery&message=1'); die;
			}
			else {
				header('Location: admin.php?page=catablog-gallery&message=2'); die;
			}
			
		}
		else {
			header('Location: admin.php?page=catablog-gallery&message=3'); die;
		}
	}
	
	public function admin_gallery_edit() {
		if (isset($_GET['id'])) {
			
			if (isset($_GET['message'])) {
				switch ($_GET['message']) {
					case 1:
						$this->wp_message(__("Changes Saved Successfully.", 'catablog'));
						break;
					case 2:
						$this->wp_error(__("Form Validation Error: Please make sure your title is at least one character.", 'catablog'));
						break;
					case 3:
						$this->wp_error(__("Form Validation Error. Please reload the page and try again.", 'catablog'));
						break;
				}
			}
			
			$gallery = CataBlogGallery::getGallery($_GET['id']);
			if (!$gallery) {
				include_once($this->directories['template'] . '/admin-404.php');
				return false;
			}
			
			$gallery_items = $gallery->getCataBlogItems();
			include_once($this->directories['template'] . '/admin-gallery-edit.php');
		}
	}
	
	public function admin_gallery_save($init_run=false) {
		$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_save_gallery_nonce'], 'catablog_save_gallery' );
		if ($nonce_verified) {
			
			$post_vars = $_POST;
			$post_vars = array_map('stripslashes_deep', $post_vars);
			
			$new_gallery = new CataBlogGallery($post_vars);
			$validate = $new_gallery->validate();
			if ($validate === true) {
				$new_gallery->save();
				header('Location: admin.php?page=catablog-gallery&id='.$new_gallery->getId().'&message=1'); die;
			}
			else {
				header('Location: admin.php?page=catablog-gallery&id='.$new_gallery->getId().'&message=2'); die;
			}
			
		}
		else {
			header('Location: admin.php?page=catablog-gallery&id='.$_REQUEST['id'].'&message=3'); die;
		}
	}
	
	public function admin_gallery_append_new_items($init_run=false) {
		$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_append_gallery_nonce'], 'catablog_append_gallery' );
		if ($nonce_verified) {
			$gallery_id = (isset($_POST['gallery_id']))? $_POST['gallery_id'] : false;
			$item_ids   = (isset($_POST['item_ids']))? $_POST['item_ids'] : array();
			
			if ($gallery_id === false) {
				header('Location: admin.php?page=catablog&message=9'); die;
			}
			
			$gallery  = CataBlogGallery::getGallery($gallery_id);

			if (get_class($gallery) !== 'CataBlogGallery') {
				header('Location: admin.php?page=catablog&message=9'); die;
			}
			
			foreach ($item_ids as $item_id) {
				$gallery->addItem($item_id);
			}
			
			$validate = $gallery->validate();
			if ($validate === true) {
				$gallery->save();
				header('Location: admin.php?page=catablog&message=8'); die;
			}
			else {
				header('Location: admin.php?page=catablog&message=10'); die;
			}
		}
		else {
			header('Location: admin.php?page=catablog&message=4'); die;
		}
	}
	
	public function admin_gallery_delete($init_run=false) {
		if (isset($_REQUEST['id'])) {
			
			check_admin_referer('catablog-gallery-delete');
			
			$gallery = CataBlogGallery::getGallery($_REQUEST['id']);
			if ($gallery) {
				$gallery->delete();
				header('Location: admin.php?page=catablog-gallery&message=4'); die;
			}
			else {
				header('Location: admin.php?page=catablog-gallery&message=5'); die;
			}
		}
	}
	
	
	
	
	
	
	
	/*****************************************************
	**       - ADMIN ACTIONS
	*****************************************************/
	public function admin_create($init_run=false) {
		$error = false;
		
		$new_item = new CataBlogItem();
		$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_create_nonce'], 'catablog_create' );
		if ($nonce_verified) {
			
			$tmp_name = $_FILES['new_image']['tmp_name'];
			
			if ($this->string_length($tmp_name) > 0) {
				$validate = $new_item->validateImage($tmp_name);
				if ($validate === true) {
					
					$new_item_title = $_FILES['new_image']['name'];
					$new_item_title = preg_replace('/\.[^.]+$/','',$new_item_title);
					$new_item_title = str_replace(array('_','-','.'), ' ', $new_item_title);
					$new_item_order = wp_count_posts($this->custom_post_name)->publish + 1;
					
					$new_item->setOrder($new_item_order);
					$new_item->setTitle($new_item_title);
					
					$new_item->setImage($tmp_name);
					$new_item->setSubImages(array());

					$default_term = $this->get_default_term();
					$new_item->setCategories(array($default_term->term_id=>$default_term->name));
					
					$new_item->save();
					
					// wp_redirect( self_admin_url("admin.php?page=catablog&id=".$new_item->getId()) );
					header('Location: admin.php?page=catablog&id=' . $new_item->getId()); die;
				}
				else {
					$error = $validate;
				}
			}
			else {
				$error = __("The file you selected was to large or you didn't select anything at all, please try again.", 'catablog');
			}
		}
		else {
			$error = __("WordPress Nonce Error, please reload the form and try again.", 'catablog');
		}
			
		if (!$init_run && $error !== false) {
			$this->wp_error($error);
			include_once($this->directories['template'] . '/admin-new.php');
		}
	}
	
	
	public function admin_save($init_run=false) {
		$error = false;
		
		if (isset($_POST['save'])) {
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_save_nonce'], 'catablog_save' );
			if ($nonce_verified) {
				$post_vars = $_POST;
				$post_vars = array_map('stripslashes_deep', $post_vars);
				
				// trim whitespace from specific fields
				$trim_fields = array('title', 'description', 'link', 'price', 'product_code');
				foreach ($trim_fields as $field) {
					$post_vars[$field] = trim($post_vars[$field]);
				}
				
				// build a categories array
				$post_vars['categories'] = (isset($post_vars['categories']))? $post_vars['categories'] : array();
				foreach ($post_vars['categories'] as $key => $value) {
					$post_vars['categories'][$key] = (integer) $value;
				}
				
				// build a sub images array
				if (!isset($post_vars['sub_images'])) {
					$post_vars['sub_images'] = array();
				}
				
				// create new object with modified $post_vars variable
				$result    = new CataBlogItem($post_vars);
				$validate  = $result->validate();
				if ($validate === true) {
					$write = $result->save();
					if ($write === true) {
						header('Location: admin.php?page=catablog&id=' . $result->getId() . '&message=1'); die;
					}
					else {
						$error = $write;
					}
					
				}
				else {
					$error = $validate;
				}
			}
			else {
				$error = __("WordPress Nonce Error, please reload the form and try again.", 'catablog');
			}
		}
		else {
			$error = __("full form was not submitted, please try again.", 'catablog');
		}
		
		if (!$init_run && $error) {
			$this->wp_error($error);
			include_once($this->directories['template'] . '/admin-edit.php');
			return true;
		}
	}
	
	public function admin_replace_main_image($init_run=false) {
		$error = false;
		
		if (is_numeric($_POST['id'])) {
			$result = CataBlogItem::getItem($_POST['id']);
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_replace_image_nonce'], 'catablog_replace_image' );
			if ($nonce_verified) {
				
				$tmp_name = $_FILES['new_image']['tmp_name'];
				
				if ($this->string_length($tmp_name) > 0) {
					$validate = $result->validateImage($tmp_name);
					if ($validate === true) {
						
						$to_delete = array();
						$to_delete["original"]  = $this->directories['originals'] . "/" . $result->getImage();
						$to_delete["thumbnail"] = $this->directories['thumbnails'] . "/" . $result->getImage();
						$to_delete["fullsize"]  = $this->directories['fullsize'] . "/" . $result->getImage();
						foreach ($to_delete as $file) {
							if (is_file($file)) {
								unlink($file);
							}
						}
						
						$result->setImage($tmp_name);
						$result->save();
						
						header('Location: admin.php?page=catablog&id=' . $_POST['id']); die;
					}
					else {
						$error = $validate;
					}
				}
				else {
					$error = __("You didn't select anything to upload, please try again.", 'catablog');
				}
			}
			else {
				$error = __("WordPress Nonce Error, please reload the form and try again.", 'catablog');
			}
		}
		else {
			$error = __("No item ID posted, press back arrow and try again.", 'catablog');
		}
		
		if (!$init_run && $error !== false) {
			$this->wp_error($error);
			include_once($this->directories['template'] . '/admin-edit.php');
		}
	}
	
	public function admin_add_subimage($init_run=false) {
		$error = false;
		
		if (is_numeric($_POST['id'])) {
			$result = CataBlogItem::getItem($_POST['id']);
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_add_subimage_nonce'], 'catablog_add_subimage' );
			if ($nonce_verified) {
				
				$tmp_name = $_FILES['new_sub_image']['tmp_name'];
				
				if ($this->string_length($tmp_name) > 0) {
					$validate = $result->validateImage($tmp_name);
					if ($validate === true) {
						$result->addSubImage($tmp_name);
						header('Location: admin.php?page=catablog&id=' . $_POST['id']); die;
					}
					else {
						$error = $validate;
					}
				}
				else {
					$error = __("You didn't select anything to upload, please try again.", 'catablog');
				}
			}
			else {
				$error = __("WordPress Nonce Error, please reload the form and try again.", 'catablog');
			}
		}
		else {
			$error = __("No item ID posted, press back arrow and try again.", 'catablog');
		}
		
		if (!$init_run && $error !== false) {
			$this->wp_error($error);
			include_once($this->directories['template'] . '/admin-edit.php');
		}
	}
	
	
	public function admin_delete($init_run=false) {
		$error = false;
		
		if (isset($_REQUEST['id'])) {
			
			check_admin_referer('catablog-delete');
			
			$item = CataBlogItem::getItem($_REQUEST['id']);
			if ($item) {
				$item->delete();
				header('Location: admin.php?page=catablog&message=2'); die;
			}
			else {
				header('Location: admin.php?page=catablog&message=3'); die;
			}
		}
	}
	
	
	public function admin_bulk_edit($init_run=false) {
		$action = $_REQUEST['bulk-action'];
		if ($this->string_length($action) > 0) {
			
			// verify that the post has a nonce, and therefor should be acted upon
			$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_bulkedit_nonce'], 'catablog_bulkedit' );
			if (!$nonce_verified) {
				header('Location: admin.php?page=catablog&message=4'); die;
				
			}
			else {
				
				// if no catalog items selected return this error message.
				if (!isset($_REQUEST['bulk_selection'])) {
					$this->wp_message(__('Please make your selection by checking the boxes in the list below.', 'catablog'));
				}
				else {
					
					$ref = "admin.php?page=catablog";
					if ($_POST['reference']) {
						$ref = $_POST['reference'];
					}
					
					// if action is edit-category, change the categories of all selected items
					if ($action == 'edit-category') {
						
						$selection = $_REQUEST['bulk_selection'];
						
						// process categories to add to selected items
						$categories_add    = (isset($_REQUEST['categories-add']))? $_REQUEST['categories-add'] : array();
						foreach ($categories_add as $key => $value) {
							$categories_add[$key] = (integer) $value;
						}
						
						// process categories to remove from selected items
						$categories_remove = (isset($_REQUEST['categories-remove']))? $_REQUEST['categories-remove'] : array();
						foreach ($categories_remove as $key => $value) {
							$categories_remove[$key] = (integer) $value;
						}
						
						foreach ($selection as $item_id) {
							$item = CataBlogItem::getItem($item_id);
							if ($item) {
								
								$categories_items = array_keys($item->getCategories());
								$categories_items = array_merge($categories_items, $categories_add);
								$categories_items = array_unique($categories_items);
								
								foreach ($categories_items as $key => $cat_id) {
									foreach ($categories_remove as $cat_id_remove) {
										if ($cat_id == $cat_id_remove) {
											unset($categories_items[$key]);
										}
									}
								}
								
								$item->setCategories($categories_items);
								$item->save();
							}
							else {
								$this->wp_error(sprintf(__('Error during set category, could not load item with id %s.', 'catablog'), $item_id));
							}
						}
						
						$ref .= "&message=5";
						header("Location: $ref"); die;
						
					}

					// if action is delete, delete all selected items
					if ($action == 'delete') {
						$selection = $_REQUEST['bulk_selection'];
						foreach ($selection as $item_id) {
							$item = CataBlogItem::getItem($item_id);
							if ($item) {
								$item->delete();
							}
							else {
								$this->wp_error(sprintf(__('Error during bulk delete, could not load item with id %s.', 'catablog'), $item_id));
							}
						}
						
						$ref .= "&message=6";
						header("Location: $ref"); die;
					}
					
					
				}
			}
		}
		
		header('Location: admin.php?page=catablog'); die;
		
	}
	
	public function admin_import() {
		$error = false;
		
		$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_import_nonce'], 'catablog_import' );
		if (!$nonce_verified) {
			$error = __("WordPress Nonce Error, please reload the form and try again.", 'catablog');
		}
		
		// do appropriate actions depending on format and successful upload
		$file_error_check = $this->check_file_upload_errors();
		if ($file_error_check !== true) {
			$error = ($file_error_check);
		}
		elseif (isset($_FILES['catablog_data']) === false) {
			$error = __('No file was selected for upload, please try again.', 'catablog');
		}
		else {
			$upload = $_FILES['catablog_data'];
			$extension = end(explode(".", strtolower($upload['name'])));
			
			if ($extension == 'xml') {
				$data = $this->xml_to_array($upload['tmp_name']);
				if ($data === false) {
					$error = __('Uploaded XML File Could Not Be Parsed, Check That The File\'s Content Is Valid XML.', 'catablog');
				}
			}
			else if ($extension == 'csv') {
				$data = $this->csv_to_array($upload['tmp_name']);
				if (!is_array($data)) {
					$error = $data;
				}
			}
			else {
				$error = __('Uploaded file was not of proper format, please make sure the filename has an xml or csv extension.', 'catablog');
			}
			
			
		}
		
		// if there is an error display it and stop
		if ($error !== false) {
			$this->wp_error($error);
			include_once($this->directories['template'] . '/admin-import.php');
			return false;
		}
		
		// Private DataBase Insertion Method Called in Template:  load_array_to_database($data_array)
		include_once($this->directories['template'] . '/admin-import.php');
	}
	
	public function admin_export() {
		
		check_admin_referer('catablog-export');
		
		$date = date('Y-m-d');
		
		$format = 'xml';
		if (isset($_REQUEST['format'])) {
			if ($_REQUEST['format'] == 'csv') {
				$format = 'csv';
			}
		}
		
		header('Content-type: application/'.$format);
		header('Content-Disposition: attachment; filename="catablog-backup-'.$date.'.'.$format.'"');
		header("Pragma: no-cache");
		header("Expires: 0");

		$results = CataBlogItem::getItems();
				
		if ($format == 'csv') {
			ini_set('auto_detect_line_endings', true);
			
			$outstream   = fopen("php://output", 'w');
			$field_names = array('image','subimages','title','description','date','order','link','price','product_code','categories');
			$header      = NULL;
			
			if (true) {
				array_unshift($field_names, 'id');
			}
			
			foreach ($results as $result) {
				if (!$header) {
					fputcsv($outstream, $field_names, ',', '"');
					$header = true;
				}
				fputcsv($outstream, $result->getCSVArray(), ',', '"');
			}
			
			fclose($outstream);
		}
		
		if ($format == 'xml') {
			include_once($this->directories['template'] . '/admin-export.php');
		}
		
		die;
	}


	public function admin_unlock_folders() {
		check_admin_referer('catablog-unlock-folders');
		
		if ($this->unlock_directories()) {
			$this->wp_message(__("The CataBlog upload directories have been unlocked.", 'catablog'));
		}
		else {
			$this->wp_error(__("Could not lock/unlock the directory. Are you using a unix based server?", 'catablog'));
		}
		
		$this->admin_options();
	}

	public function admin_lock_folders() {
		check_admin_referer('catablog-lock-folders');
		
		if ($this->lock_directories()) {
			$this->wp_message(__("The CataBlog upload directories have been locked.", 'catablog'));
		}
		else {
			$this->wp_error(__("Could not lock/unlock the directory. Are you using a unix based server?", 'catablog'));
		}
		
		$this->admin_options();
	}
	
	public function admin_rescan_images() {
		
		check_admin_referer('catablog-rescan-originals');
		
		$items = CataBlogItem::getItems();
		$image_names = array();
		foreach ($items as $item) {
			$image_names[] = $item->getImage();
			foreach ($item->getSubImages() as $subimage) {
				$image_names[] = $subimage;
			}
		}
		
		$new_rows = array();
		$new_rows['images'] = array();
		$originals = new CataBlogDirectory($this->directories['originals']);
		
		if ($originals->isDirectory()) {
			
			$new_order = wp_count_posts($this->custom_post_name)->publish + 1;
			
			$default_term = $this->get_default_term();
			$default_category = (array($default_term->term_id=>$default_term->name));
			
			foreach ($originals->getFileArray() as $file) {
				if (!in_array($file, $image_names)) {
					
					$extension = end(explode(".", strtolower($file)));
					$media_accepted = array('jpg', 'jpeg', 'gif', 'png');
					
					if (in_array($extension, $media_accepted)) {
						$title = str_replace(array('-','_'), ' ', $file);
						$title = str_ireplace('.'.$extension, '', $title);
						
						$new_item = new CataBlogItem();
						$new_item->setOrder($new_order);
						$new_item->setTitle($title);

						$new_item->setImage($file, false);
						$new_item->setSubImages(array());

						$new_item->setCategories($default_category);
						$new_item->save();
												
						$new_rows['ids'][]    = $new_item->getId();
						$new_rows['titles'][] = $new_item->getTitle();				
						$new_rows['images'][]  = $new_item->getImage();
						
					}
				}
				
				$new_order += 1;
			}
		}
		
		include_once($this->directories['template'] . '/admin-rescan.php');
	}
	
	public function admin_regenerate_images() {
		
		check_admin_referer('catablog-regenerate-images');
		
		delete_transient('dirsize_cache'); // WARNING!!! transient label hard coded.
		
		$items       = CataBlogItem::getItems();
		$image_names = array();
		
		foreach ($items as $item) {
			$image_names[] = $item->getImage();
			foreach ($item->getSubImages() as $image) {
				$image_names[] = $image;
			}
		}
		
		include_once($this->directories['template'] . '/admin-regenerate.php');
	}
	
	public function admin_remove_all() {
		check_admin_referer('catablog-remove');
		
		include_once($this->directories['template'] . '/admin-remove.php');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - ADMIN AJAX ACTIONS
	*****************************************************/
	public function ajax_micro_save() {
		check_ajax_referer('catablog-micro-save','security');
		
		$id = $_REQUEST['id'];
		$item = CataBlogItem::getItem($id);
		
		if ($item !== NULL) {
			$title        = $_REQUEST['title'];
			$description  = $_REQUEST['description'];
			$link         = $_REQUEST['link'];
			$price        = $_REQUEST['price'];
			$product_code = $_REQUEST['product_code'];;
			$order        = $_REQUEST['order'];

			$item->setTitle($title);
			$item->setDescription($description);
			$item->setLink($link);
			$item->setProductCode($product_code);
			
			if (is_numeric($price) && $price > 0) {
				$item->setPrice($price);
			}
			
			if (is_numeric($order) && $order > 0) {
				$item->setOrder($order);
			}
			
			$validate = $item->validate();
			if ($validate === true) {
				$item->save();
				$success_message = __('micro save successful','catablog');
				echo "{\"success\":true, \"message\":\"$success_message\"}";
			}
			else {
				echo "{\"success\":false, \"message\":\"$validate\"}";
			}
		}
		else {
			$error_message = __("Cannot save changes because the item id could not be found in the database.", "catablog");
			echo "{\"success\":false, \"message\":\"$error_message\"}";
		}
		
		exit;
	}
	public function ajax_update_screen_settings() {
		check_ajax_referer('catablog-update-screen-settings','security');
		$user          = wp_get_current_user();
		$user_settings = get_user_meta($user->ID, $this->custom_user_meta_name, true);
		
		$page = isset($_REQUEST['settings-page'])? $_REQUEST['settings-page'] : false;
		$page_settings = array();
		
		switch ($page) {
			case 'library':
				$fields = array('description', 'link', 'price', 'product_code', 'categories', 'order', 'date');
				
				$hide_array = array();
				if (isset($_REQUEST['hide'])) {
					$hide_array = $_REQUEST['hide'];
				}
				
				$page_settings['limit'] = isset($_REQUEST['entry-per-page'])? intval($_REQUEST['entry-per-page']) : 5;
				
				$page_settings['hide-columns'] = array();
				foreach ($fields as $field) {
					if (!in_array($field, $hide_array)) {
						$page_settings['hide-columns'][] = $field;
					}
				}
				
				break;
			case 'add-new':
			
				$fields = array('description', 'link', 'price', 'product_code', 'order');
				
				$hide_array = array();
				if (isset($_REQUEST['hide'])) {
					$hide_array = $_REQUEST['hide'];
				}
				
				$page_settings['hide-columns'] = array();
				foreach ($fields as $field) {
					if (!in_array($field, $hide_array)) {
						$page_settings['hide-columns'][] = $field;
					}
				}
			
				break;
			case 'gallery':
				$fields = array('description', 'size', 'shortcode', 'date');
				
				$hide_array = array();
				if (isset($_REQUEST['hide'])) {
					$hide_array = $_REQUEST['hide'];
				}

				$page_settings['limit'] = $_REQUEST['entry-per-page'];

				$page_settings['hide-columns'] = array();
				foreach ($fields as $field) {
					if (!in_array($field, $hide_array)) {
						$page_settings['hide-columns'][] = $field;
					}
				}
				
				break;
		}
		
		$user_settings[$page] = $page_settings;
		update_user_meta($user->ID, $this->custom_user_meta_name, $user_settings);
		
		echo "{\"success\":true, \"message\":\"".__('Screen Options updated successfully.', 'catablog')."\"}";
		
		die;
	}
	
	public function ajax_new_category() {
		check_ajax_referer('catablog-new-category', 'security');
		
		$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		
		$category_name = trim($_REQUEST['name']);
		
		$char_check = preg_match('/[\,\|\<\>\&\'\"]/', $category_name);
		if ($char_check > 0) {
			echo "{\"success\":false, \"error\":\"".__('Commas, Pipes and reserved HTML characters are not allowed in category names.', 'catablog')."\"}";
			die;
		};
		
		$string_length = $this->string_length($category_name);
		if ($string_length < 1) {
			echo "{\"success\":false, \"error\":\"".__('Please be a little more specific with your category name.', 'catablog')."\"}";
			die;
		}
		
		$name_exists = false;
		foreach ($this->get_terms() as $term) {
			// var_dump($term);
			if (strtolower($category_name) == strtolower($term->name)) {
				$name_exists = true;
				break;
			}
		}
		if ($name_exists) {
			echo "{\"success\":false, \"error\":\"".__('There already is a category with that name.', 'catablog')."\"}";
			die;
		}
		
		$category_slug = $this->string2slug($category_name);
		$attr = array('slug'=>$category_slug);
		$new_category_id = wp_insert_term($category_name, $this->custom_tax_name, $attr);
		
		if (isset($new_category_id['term_id'])) {
			echo "{\"success\":true, \"id\":{$new_category_id['term_id']}, \"slug\":\"$category_slug\", \"name\":\"$category_name\"}";
		}
		else {
			$error_string = "";
			foreach ($new_category_id->get_error_messages() as $error) {
				$error_string = $error . "  ";
			}
			echo "{\"success\":false, \"error\":\"$error_string\"}";
		}
		
		exit;
	}
	
	public function ajax_edit_category() {
		check_ajax_referer('catablog-edit-category', 'security');
		
		$id   = $_POST['term_id'];
		$name = $_POST['new_category_name'];
		$slug = $_POST['new_category_slug'];
		$slug = $this->string2slug($slug);
		
		$term = get_term_by('id', $id, $this->custom_tax_name);
		if ($term !== NULL) {
			$update_fields = array();
			$update_fields['name'] = $name;
			$update_fields['slug'] = $slug;
			wp_update_term($id, $this->custom_tax_name, $update_fields);
			
			echo "{\"success\":true, \"name\":\"$name\", \"slug\":\"$slug\", \"message\":\"".__("Term Edited Successfully", "catablog")."\"}";
		}
		else {
			echo "{\"success\":false, \"message\":\"".__("Term did not exist, please refresh page and try again.", "catablog")."\"}";
		}
		
		exit;
	}
	
	public function ajax_delete_category() {
		check_ajax_referer('catablog-delete-category', 'security');
		
		$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		
		$term_id = (integer) trim($_REQUEST['term_id']);
		if(wp_delete_term($term_id, $this->custom_tax_name)) {
			echo "{\"success\":true, \"message\":\"".__('Term Removed Successfully.', 'catablog')."\"}";
		}
		else {
			echo "{\"success\":false, \"error\":\"".__('Term did not exist, please refresh page and try again.', 'catablog')."\"}";
		}
		
		exit;
	}
	
	public function ajax_flush_fullsize() {
		check_ajax_referer('catablog-flush-fullsize', 'security');
		
		$this->remove_directories(array('fullsize'));
		
		$dir = 'fullsize';
		$is_dir  = is_dir($this->directories[$dir]);
		$is_file = is_file($this->directories[$dir]);
		if (!$is_dir && !$is_file) {
			mkdir($this->directories[$dir]);
		}
		
		$this->options['lightbox-render'] = false;
		$this->save_wp_options();
		
		exit;
	}
	
	public function ajax_render_images() {
		
		function catablog_shutdown() {
			$last_error = error_get_last();
			if ($last_error['type'] === E_ERROR) {
				echo "<strong>" . $_REQUEST['image'] . " Error:</strong> " . $last_error['message'];
			}
		}
		register_shutdown_function('catablog_shutdown');
		
		check_ajax_referer('catablog-render-images', 'security');
		
		$name  = $_REQUEST['image'];
		$type  = $_REQUEST['type'];
		$count = $_REQUEST['count'];
		$total = $_REQUEST['total'];
		
		if (isset($_REQUEST['key']) && $_REQUEST['key'] == 'id') {
			$id = (int) $name;
			$item = CataBlogItem::getItem($id);
			$image_names = array();
			$image_names[] = $item->getImage();
			foreach ($item->getSubImages() as $image) {
				$image_names[] = $image;
			}
		}
		else {
			$item = new CataBlogItem();
			$image_names = array();
			$image_names[] = $name;
		}
		
		foreach($image_names as $image_name) {
			switch ($type) {
				case 'thumbnail':
					$success = $item->MakeThumbnail($image_name);
					break;
				case 'fullsize';
					$success = $item->MakeFullsize($image_name);
					break;
				default:
					$success = __("unsupported image size type", 'catablog');
					break;
			}
		}
		
		if ($success !== true) {
			$message = $success;
			echo "{\"success\":false, \"error\":\"$message\"}";
		}
		else {
			$message = sprintf(__('Rendering... %s of %s', 'catablog'), $total - $count, $total);
			if ($count == 0) {
				$message = __('Image rendering is now complete.', 'catablog');
			}
			echo "{\"success\":true, \"message\":\"$message\"}";
		}
		
		
		exit;
	}
	
	public function ajax_delete_subimage() {
		check_ajax_referer('catablog-delete-subimage', 'security');
		
		$id    = $_POST['id'];
		$image = $_POST['image'];
		
		$result     = CataBlogItem::getItem($id);
		$sub_images = $result->getSubImages();		
		
		foreach ($sub_images as $key => $value) {
			if ($image == $value) {
				unset($sub_images[$key]);
			}
		}
		
		$to_delete = array();	
		$to_delete["original"]  = $this->directories['originals'] . "/$image";
		$to_delete["thumbnail"] = $this->directories['thumbnails'] . "/$image";
		$to_delete["fullsize"]  = $this->directories['fullsize'] . "/$image";
					
		foreach ($to_delete as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
		
		$result->setSubImages($sub_images);
		$result->save();
			
		delete_transient('dirsize_cache'); // WARNING!!! transient label hard coded.
		
		if (false) {
			echo "({'success':false, 'error':'".__('error', 'catablog')."'})";
		}
		else {
			echo "({'success':true, 'message':'".__('sub image deleted successfully', 'catablog')."'})";
		}
		
		exit;
	}

	public function ajax_delete_library() {
		check_ajax_referer('catablog-delete-library', 'security');
		
		$gallery_counts = wp_count_posts($this->custom_post_gallery_name);
		$item_counts    = wp_count_posts($this->custom_post_name);
		$limit  = 20;
		
		if ($gallery_counts->publish > 0) {
			$galleries = CataBlogGallery::getGalleries('title', 'asc',  0, $limit);
			foreach ($galleries as $gallery) {
				$gallery->delete();
			}
			
			// NOTE that the limit value is changed here.
			$limit = $limit - count($galleries);
		}
		
		if ($limit > 0) {
			$items = CataBlogItem::getItems(false, 'IN', 'date', 'asc', 0, $limit);
			foreach ($items as $item) {
				$item->delete(false);
			}
		}
		
		$gallery_counts = wp_count_posts($this->custom_post_gallery_name);
		$item_counts    = wp_count_posts($this->custom_post_name);
		$total_remaining = ($gallery_counts->publish) + ($item_counts->publish);
		
		if ($total_remaining > 0) {
			$message = sprintf(__('%s library items deleted successfully.', 'catablog'), ($limit) );
		}
		else {
			$message = sprintf(__('CataBlog Library Cleared Successfully.', 'catablog'));
		}
		
		echo "{\"success\":true, \"remaining\":$total_remaining, \"message\":\"$message\"}";
		
		exit;
	}
	
	public function ajax_delete_system() {
		check_ajax_referer('catablog-delete-system', 'security');
		
		$this->remove();
		
		$message =  __("CataBlog Options, Directories and Terms Deleted Successfully.", "catablog");
		$message2 = __("You may now go deactivate CataBlog and nothing will be left behind.", "catablog");
		$message3 = __("If you want to continue using CataBlog, go to the library to re-install necessary settings.", "catablog");
		echo "{\"success\":true, \"message\":\"$message\", \"message2\":\"$message2\", \"message3\":\"$message3\"}";
		
		exit;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - FRONTEND ACTIONS
	*****************************************************/
	public function frontend_init($load=false) {
		// $this->load_support_files is hard set to true as a private variable of this class.
		if ($this->load_support_files) {
			if ($this->options['lightbox-enabled']) {
				wp_enqueue_script('catablog-lightbox', $this->urls['javascript'] . '/catablog.lightbox.js', array('jquery'), $this->version);
			}
			
			wp_enqueue_style('catablog-stylesheet', $this->urls['css'] . '/catablog.css', false, $this->version);
			
			$path = get_stylesheet_directory() . '/catablog.css';
			$theme_catablog_stylesheet = get_stylesheet_directory_uri() . '/catablog.css';
			if (file_exists($path)) {
				wp_enqueue_style('catablog-custom-stylesheet', $theme_catablog_stylesheet, false, $this->version);
			}
		}
		
	}
	
	
	public function frontend_header() {
		if ($this->load_support_files) {
			$width  = $this->options['thumbnail-width'];
			$height = $this->options['thumbnail-height'];
			$size1  = $width + 10;
			$size2  = $width - 10;
			
			$inline_styles = array();
			
			$inline_styles[] = ".catablog-row {min-height:{$height}px; height:auto !important; height:{$height}px;}";
			$inline_styles[] = ".catablog-image {width:{$width}px;}";
			$inline_styles[] = ".catablog-title {margin:0 0 0 {$size1}px !important;}";
			$inline_styles[] = ".catablog-description {margin:0 0 0 {$size1}px; !important}";
			$inline_styles[] = ".catablog-images-column {width:{$width}px;} ";
			
			$inline_styles[] = ".catablog-gallery.catablog-row {width:{$width}px; height:{$height}px;}";
			$inline_styles[] = ".catablog-gallery.catablog-row .catablog-image {width:{$width}px; height:{$height}px;}";
			$inline_styles[] = ".catablog-gallery.catablog-row .catablog-image img {width:{$width}px; height:{$height}px;}";
			$inline_styles[] = ".catablog-gallery.catablog-row .catablog-title {width:{$size2}px;}";
			
			echo "<!-- ".sprintf(__('CataBlog %s LightBox Styles | %s'), $this->version, $this->blog_url)." -->\n";
			echo "<style type='text/css'>\n  ".implode(" ", $inline_styles)."\n</style>\n";
		}
	}
	
	
	public function frontend_footer() {
		
		if (!is_admin() && $this->options['lightbox-enabled']) {
			if (isset($this->load_support_files) && $this->load_support_files === true) {
				$selector = '.catablog-image';
				if (isset($this->options['lightbox-selector'])) {
					if ($this->string_length(trim($this->options['lightbox-selector'])) > 0) {
						$selector = $this->options['lightbox-selector'];
					}
				}
				
				$lightbox_navigation = ($this->options['lightbox-navigation'])? "{'navigation':'combine'}" : "";
				
				$javascript = array();
				
				$javascript[] = "var js_i18n=new Object;";
				$javascript[] = 'js_i18n.prev_tip="'.__("You may also press &quot;P&quot; or the left arrow on your keyboard", 'catablog').'";';
				$javascript[] = 'js_i18n.next_tip="'.__("You may also press &quot;N&quot; or the right arrow on your keyboard", 'catablog').'";';
				$javascript[] = "js_i18n.close_tip='".__('Close LightBox Now', 'catablog')."';";
				$javascript[] = "js_i18n.prev_label='".__('PREV', 'catablog')."';";
				$javascript[] = "js_i18n.next_label='".__('NEXT', 'catablog')."';";
				$javascript[] = "js_i18n.close_label='".__('CLOSE', 'catablog')."';";
				
				$javascript[] = "jQuery(document).ready(function(){ jQuery('$selector').catablogLightbox($lightbox_navigation); });";
				
				echo "<!-- ".sprintf(__('CataBlog %s LightBox JavaScript | %s'), $this->version, $this->blog_url)." -->\n";
				echo "<script type='text/javascript'>".implode(" ", $javascript)."</script>\n";
				echo "<!-- ".__('End CataBlog LightBox JavaScript')." -->\n\n";
			}
		}
	}
	
	
	// CAUSE FOUR EXTRA DATABASE HITS ON SINGLE AND ARCHIVE FRONTEND PAGES!!!
	public function frontend_shortcode_catablog($atts) {
		
		$shortcode_params = array('category'=>false, 'template'=>false, 'sort'=>'menu_order', 'order'=>'asc', 'operator'=>'IN', 'limit'=>-1, 'navigation'=>true);
		
		extract(shortcode_atts($shortcode_params, $atts));
		
		// if sort equals order, change it to menu_order
		$sort = ($sort == 'order')? 'menu_order' : $sort;
		
		// modify the operator if it is a possibly wrong format to work with WP.
		$operator = str_replace("-", " ", strtoupper($operator));
		
		// disable navigation if it is present in the turn off words array
		$turn_off_nav_words = array(false, 'no', 'off', 'disable', 'disabled', 'false');
		$navigation = (in_array(strtolower($navigation), $turn_off_nav_words))? false : true;
		
		// initialize pagination variables
		$paged = 1;
		$total = 0;
		
		if (isset($_REQUEST[$this->pagination_query_label])) {
			$paged = (is_numeric($_REQUEST[$this->pagination_query_label]))? intval($_REQUEST[$this->pagination_query_label]) : 1;
		}
		$offset = ($paged-1) * $limit;
		
		// get items from cache and start the output buffer
		if (isset($this->results_cache)) {
			$results = $this->results_cache;
		}
		else {
			
			// extract all category ids
			if (!empty($category)) {
				
				// separate categories and trim names
				$categories = explode(',', $category);
				array_walk($categories, create_function('&$val', '$val = trim($val);'));
				
				// load all category names and ids in the catalog
				$catalog_terms = array();
				foreach ($this->get_terms() as $term) {
					$lowercase_name = strtolower($term->name);
					$catalog_terms[$lowercase_name] = $term->term_id;
				}
				
				// loop over shortcode categories, matching names and setting ids if available
				$category_ids = array();
				foreach ($categories as $category) {
					$id = -1;
					$lowercase_name = strtolower($category);
					
					if (in_array($lowercase_name, array_keys($catalog_terms))) {
						$id = $catalog_terms[$lowercase_name];
					}
					
					$category_ids[] = $id;
				}
				
				// remove any duplicate category ids and set the ids array to $category
				$category_ids = array_unique($category_ids);
				$category = $category_ids;
			}
			
			// !! NOTE: Eventually $offset and $limit should be used here for better db performance
			$results = CataBlogItem::getItems($category, $operator, $sort, $order);
			
			$total = count($results);
			
			if ($limit > 0) {
				$results = array_slice($results, $offset, $limit, true);
			}
		}
		
		ob_start();
		
		if ($navigation) {
			if ($this->options['nav-location'] != 'bottom') {
				$this->frontend_build_navigation($paged, $limit, $total);
			}
		}
		
		echo "<div class='catablog-catalog'>";
		foreach ($results as $result) {
			
			// render all items if the category is not set
			// if ($category === false) {
				echo $this->frontend_render_catalog_row($result, $template);
			// }
			
			// render only the items in the set category
			// else if ($result->inCategory($category)) {
				// echo $this->frontend_render_catalog_row($result, $template);
			// }
		}
		echo "</div>";
		
		if ($navigation) {
			if ($this->options['nav-location'] != 'top') {
				$this->frontend_build_navigation($paged, $limit, $total);
			}
		}
		
		return ob_get_clean();
	}
	
	
	public function frontend_shortcode_catablog_gallery($atts) {
		$shortcode_params = array('id'=>false, 'template'=>false, 'limit'=>-1, 'navigation'=>true);
		
		extract(shortcode_atts($shortcode_params, $atts));
		
		if ($id === false) {
			return __("You must use the id attribute in the catablog_gallery Shortcode", "catablog");
		}
		
		
		// disable navigation if it is present in the turn off words array
		$turn_off_nav_words = array(false, 'no', 'off', 'disable', 'disabled', 'false');
		$navigation = (in_array(strtolower($navigation), $turn_off_nav_words))? false : true;
		
		// initialize pagination variables
		$paged = 1;
		$total = 0;
		
		if (isset($_REQUEST[$this->pagination_query_label])) {
			$paged = (is_numeric($_REQUEST[$this->pagination_query_label]))? intval($_REQUEST[$this->pagination_query_label]) : 1;
		}
		$offset = ($paged-1) * $limit;
		
		// !! NOTE: Eventually $offset and $limit should be used here for better db performance
		$gallery = CataBlogGallery::getGallery($id);
		if (!is_object($gallery)) {
			return sprintf(__("Could not find a CataBlog Gallery with id %s", "catablog"), $id);
		}
		
		$gallery_item_ids = $gallery->getItemIds();
		$gallery_items = $gallery->getCataBlogItems();
		
		foreach ($gallery_item_ids as $key => $item_id) {
			if (!isset($gallery_items[$item_id])) {
				unset($gallery_item_ids[$key]);
			}
		}
		
		$total = count($gallery_item_ids);
		
		if ($limit > 0) {
			$gallery_item_ids = array_slice($gallery_item_ids, $offset, $limit, true);
		}
		
		ob_start();
		
		if ($navigation) {
			if ($this->options['nav-location'] != 'bottom') {
				$this->frontend_build_navigation($paged, $limit, $total);
			}
		}
		
		echo "<div class='catablog-catalog'>";
		foreach ($gallery_item_ids as $item_id) {
			echo $this->frontend_render_catalog_row($gallery_items[$item_id], $template);
		}
		echo "</div>";
		
		if ($navigation) {
			if ($this->options['nav-location'] != 'top') {
				$this->frontend_build_navigation($paged, $limit, $total);
			}
		}
		
		return ob_get_clean();
	}
	
	
	
	private function frontend_build_navigation($paged, $limit, $total) {
		$total = ceil($total / $limit);
		
		$query_vars = $_GET;
		unset($query_vars[$this->pagination_query_label]);
		
		$prev_label = (isset($this->options['nav-prev-label']))? $this->options['nav-prev-label'] : __("Previous", "catablog");
		$next_label = (isset($this->options['nav-next-label']))? $this->options['nav-next-label'] : __("Next", "catablog");
		
		$args = array(
			'base'     => get_permalink() . "%_%",
			'add_args' => $query_vars,
			'format'   => "?" . $this->pagination_query_label . "=%#%",
			'total'    => $total,
			'current'  => max(1, $paged),
			'mid_size' => 2,
			'end_size' => 1,
			'prev_text' => $prev_label,
			'next_text' => $next_label,
		);
		
		echo "<div class='catablog-navigation'>";
		
		if ($paged < 2) {
			echo "<span class='catablog-navigation-link catablog-first-page-link catablog-disabled'>".$this->options['nav-prev-label']."</span> ";
		}
		
		echo paginate_links($args);
		
		if ($paged == $total) {
			echo " <span class='catablog-navigation-link catablog-first-page-link catablog-disabled'>".$this->options['nav-next-label']."</span>";
		}
		
		echo "</div>";
	}
	
	
	public function frontend_single_filter_content($content) {
		global $post;
		
		if (isset($post) && isset($post->post_type)) {
			if ($post->post_type == $this->custom_post_name) {
				$result  = CataBlogItem::postToItem($post);

				if (is_single()) {
					$content = $this->frontend_render_catalog_row($result, 'single');
				}
				elseif (is_archive() || is_search()) {
					$content = $this->frontend_render_catalog_row($result, 'archive');
				}
				else {
					$content = $this->frontend_render_catalog_row($result, 'default');
				}

			}
		}
		
		return $content;
	}
	
	
	public function frontend_render_catalog_row($result, $template_override=false) {
		
		// calculate and get values for usage in multiple tokens
		$thumbnail_width  = $this->options['thumbnail-width'];
		$thumbnail_height = $this->options['thumbnail-height'];
		
		$target = htmlspecialchars($this->options['link-target'], ENT_QUOTES, 'UTF-8');
		$target = ($this->string_length($target) > 0)? "target='$target'" : "";
		$rel    = htmlspecialchars($this->options['link-relationship'], ENT_QUOTES, 'UTF-8');
		$rel    = ($this->string_length($rel) > 0)? "rel='$rel'" : "";
		$link   = $result->getLink();
		
		
		$values = array();
		
		// system wide token values
		$values['image-width']  = $thumbnail_width;
		$values['image-height'] = $thumbnail_height;
		$values['link-target']  = $target;
		$values['link-rel']     = $rel;
		
		
		// filter description if neccessary
		$description = $result->getDescription();
		if ($this->options['filter-description']) {
			$pattern     = '/\[(catablog)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s';
			$description = preg_replace($pattern, '', $description);
			remove_filter('the_content', array(&$this, 'frontend_single_filter_content'), 12);
			$description = apply_filters('the_content', $description);
			add_filter('the_content', array(&$this, 'frontend_single_filter_content'), 12);
		}
		if ($this->options['nl2br-description']) {
			$description = nl2br($description);
		}
		
		
		// catalog item image paths
		$values['image-original']  = $this->urls['originals']  . "/" . $result->getImage();
		$values['image-thumbnail'] = $this->urls['thumbnails'] . "/" . $result->getImage();
		$values['image-lightbox']  = $this->urls['fullsize']   . "/" . $result->getImage();
		$values['image-path']      = $this->urls['fullsize'];
		if ($this->options['lightbox-render'] == false) {
			$values['image-path']      = $this->urls['originals'];
			$values['image-lightbox']  = $values['image-original'];
		}
		
		
		// catalog item title and content
		$values['title']           = $result->getTitle();
		$values['title-link']      = ($this->string_length($link) > 0)? "<a href='".$link."' $target $rel>".$values['title']."</a>" : $values['title'];
		$values['description']     = $description;
		$values['excerpt']         = $description;
		if ($this->string_length($values['excerpt']) > $this->options['excerpt-length']) {
			$values['excerpt'] = $this->sub_string($values['excerpt'], 0, $this->options['excerpt-length']);
			$cut_to_last_word  = strrpos($values['excerpt'], ' ');
			$values['excerpt'] = $this->sub_string($values['excerpt'], 0, $cut_to_last_word);
			$values['excerpt'] .= '...';
		}
		
		// catalog item attributes
		$date_mysql_string         = $result->getDate();
		$values['date']            = mysql2date(get_option('date_format'), $date_mysql_string);
		$values['time']            = mysql2date(get_option('time_format'), $date_mysql_string);
		$values['order']           = $result->getOrder();
		
		
		// catalog item field values
		$values['link']            = ($this->string_length($link) > 0)? $link : $values['image-lightbox'];
		$values['permalink']       = get_permalink($result->getId());
		$values['price']           = number_format(((float)($result->getPrice())), 2, '.', '');
		$values['product-code']    = $result->getProductCode();
		
		
		// catalog item category values
		$values['category']        = implode(', ', $result->getCategories());
		$values['category-slugs']  = implode(' ', $result->getCategorySlugs());
		
		
		// catalog item images
		$values['main-image']      = '<img src="'.$values['image-thumbnail'].'" alt="" />';
		$values['main-image']      = "<a href='".$values['image-lightbox']."' class='catablog-image' $target $rel>".$values['main-image']."</a>";
		$values['sub-images']      = "";
		foreach ($result->getSubImages() as $image) {
			$sub_image             = '<img src="'.$this->urls['thumbnails'].'/'.$image.'" />';
			$sub_image             = "<a href='".$values['image-path']."/$image' class='catablog-subimage catablog-image'>".$sub_image."</a>";
			$values['sub-images'] .= $sub_image;
		}
		
		
		// deprecatted values
		$values['title-text']     = $values['title'];
		$values['image']          = $values['image-thumbnail'];
		$values['image-fullsize'] = $values['image-lightbox'];
		
		
		// generate the buy now button if the price of the item is greater then 0
		$buy_now_button = '';
		if ($values['price'] > 0) {
			$buy_now_button_file = $this->directories['user_views'] . '/' . 'store.htm';
			if (is_file($buy_now_button_file)) {
				$buy_now_button = file_get_contents($buy_now_button_file);
			}
			elseif (WP_DEBUG){
				$buy_now_button  = "<p>";
				$buy_now_button .= sprintf(__("CataBlog Template Error: The store template does not exist. Please make sure their is a file with the name '%s.htm' in the <code>wp-content/uploads/catablog/templates</code> directory."), 'store');
				// $store_template .= " [<a href='http://catablog.illproductions.com/documentation/making-custom-templates/' target='_blank'>".__("Learn More")."</a>]";
				$buy_now_button .= "</p>";
			}
			
			foreach ($values as $key => $value) {
				$search         = "%" . strtoupper($key) . "%";
				$buy_now_button = str_replace($search, $value, $buy_now_button);
			}
		}
		$values['buy-now-button']  = $buy_now_button;
		
		
		// check if theme is empty, if so use default theme
		$template = "";
		if ($template_override !== false) {
			$template_file = $this->directories['user_views'] . '/' . $template_override . '.htm';
			
			if (is_file($template_file)) {
				$template = file_get_contents($template_file);
			}
			elseif (WP_DEBUG) {
				$template  = "<p>";
				$template .= sprintf(__("CataBlog ShortCode Parameter Error: The template attribute of this ShortCode points to a file that does not exist. Please make sure their is a file with the name '%s.htm' in the views directory."), $template_override);
				$template .= " [<a href='http://catablog.illproductions.com/documentation/making-custom-templates/' target='_blank'>".__("Learn More")."</a>]";
				$template .= "</p>";
			}
		}
		else {
			$template_file = $this->directories['user_views'] . '/default.htm';
			if (is_file($template_file)) {
				$template = file_get_contents($template_file);
			}
			else {
				$template_file = $this->directories['views'] . '/default.htm';
				$template = file_get_contents($template_file);
			}
		}
		
		
		// loop through each items array of values and replace tokens
		foreach($values as $key => $value) {
			$search  = "%" . strtoupper($key) . "%";
			$template  = str_replace($search, $value, $template);
		}
		
		
		$processed_row = $template;
		return $processed_row;
	}
	
	
	
	
	
	
	public function frontend_shortcode_catablog_categories($atts) {
		$shortcode_params = array('dropdown'=>false, 'count'=>false);
		
		extract(shortcode_atts($shortcode_params, $atts));
		
		$turn_on_words = array(true, 'yes', 'on', 'enable', 'enabled', 'true');
		$dropdown   = (in_array(strtolower($dropdown), $turn_on_words, true))? true : false;
		$count    = (in_array(strtolower($count), $turn_on_words, true))? true : false;
		
		return $this->frontend_render_categories($dropdown, $count);
	}
	
	
	public function frontend_render_categories($dropdown, $count) {
		global $wp_plugin_catablog_class;
		$html = "";
		
		$catablog_options = $wp_plugin_catablog_class->get_options();
		if (false === $catablog_options['public_posts']) {
			$error  = "<p><strong>";
			$error .= __("CataBlog Error:", "catablog");
			$error .= "</strong><br />";
			$error .= sprintf(__("CataBlog Categories require you to enable the %sCataBlog Public Option%s.", "catablog"), '<a href="'.get_admin_url(null, 'admin.php?page=catablog-options#public').'">', '</a>');
			$error .= "</p>";
			return $error;
		}

		$cat_args = array(
			'taxonomy'     => $wp_plugin_catablog_class->getCustomTaxName(),
			'title_li'     => '',
			'orderby'      => 'name',
			'show_count'   => $count,
			'hierarchical' => false,
			'echo'         => false,
		);

		if (!$dropdown) {
			$html .= "<ul>" . wp_list_categories($cat_args) . "</ul>";
		}
		else {
			$categories = $wp_plugin_catablog_class->get_terms();
			$html .= '<select id="catablog-terms" name="catablog-terms" class="postform">';
			$html .= '<option value="-1">'.__("Select Category").'</option>';
			foreach ($categories as $cat) {
				$cat_count = ($count) ? " ($cat->count)" : "";
				$html .= '<option value="'.$cat->slug.'">'.$cat->name.$cat_count.'</option>';
			}
			$html .= '</select>';
			
			$javascript = file_get_contents($this->directories['template'] . '/widget-javascript.php');
			$javascript = str_replace('%%HOME_URL%%', home_url(), $javascript);
			
			$html .= $javascript;
		}
		
		return $html;
	}
	
	













	
	
	
	
	/**
	 * Setup the required options, folders and terms for catablog,
	 * this function is run on all catablog admin pages and on the 
	 * admin plugins management pages automatically.
	 *
	 * @return void
	 */
	public function setup() {
		if (!$this->is_installed()) {
			$this->install();
		}
	}
	
	
	
	/*****************************************************
	**       - INSTALL METHODS
	*****************************************************/
	private function is_installed() {
		if ($this->options == false) {
			return false;
		}
		
		if ($this->get_default_term() === null) {
			return false;
		}
		
		$dirs = array(0=>'wp_uploads', 1=>'uploads', 2=>'thumbnails', 3=>'originals', 4=>'fullsize', 5=>'user_views');
		foreach ($dirs as $dir) {
			$is_dir = is_dir($this->directories[$dir]);
			if ($is_dir === false) {
				return false;
			}
		}
		
		return true;
	}
	
	private function install() {
		
		if ($this->options == false) {
			$this->install_options();
		}
		
		if ($this->get_default_term() === null) {
			$this->install_default_term();
		}
		
		$directories_missing = false;
		$dirs = array(0=>'wp_uploads', 1=>'uploads', 2=>'thumbnails', 3=>'originals', 4=>'fullsize', 5=>'user_views');
		foreach ($dirs as $dir) {
			$is_dir = is_dir($this->directories[$dir]);
			if ($is_dir === false) {
				$directories_missing = true;
				break;
			}
		}
		
		if ($directories_missing) {
			if ($this->install_directories() === false) {
				$this->wp_error(__('The CataBlog Upload Directory cannot be written. ', "catablog") . __('Please check your server file permissions and apache configuration.', "catablog"));
				return false;
			}
			
			if ($this->install_user_templates() === false) {
				$this->wp_error(__('The CataBlog Templates Directory cannot be written. ', "catablog") . __('Please check your server file permissions and apache configuration.', "catablog"));
				return false;
			}
		}
		
		
		$this->wp_message(sprintf(__('CataBlog options and directories have been successfully installed. Please, %srefresh now%s', 'catablog'), '<a href="">', '</a>'));
		
	}
	
	private function install_options() {
		$default_options = array();
		$default_options['version']             = $this->version;
		$default_options['thumbnail-width']     = $this->default_thumbnail_size;
		$default_options['thumbnail-height']    = $this->default_thumbnail_size;
		$default_options['image-size']          = $this->default_image_size;
		$default_options['background-color']    = $this->default_bg_color;
		$default_options['keep-aspect-ratio']   = false;
		$default_options['lightbox-enabled']    = false;
		$default_options['lightbox-navigation'] = false;
		$default_options['lightbox-render']     = false;
		$default_options['lightbox-selector']   = ".catablog-image";
		$default_options['link-target']         = "";
		$default_options['link-relationship']   = "";
		$default_options['filter-description']  = false;
		$default_options['nl2br-description']   = true;
		$default_options['excerpt-length']      = 55;
		$default_options['public_posts']        = false;
		$default_options['public_post_slug']    = $this->custom_post_name;
		$default_options['public_tax_slug']     = $this->custom_tax_name;
		$default_options['nav-prev-label']      = __('Previous', "catablog");
		$default_options['nav-next-label']      = __('Next', "catablog");
		$default_options['nav-location']        = "bottom";
		$default_options['nav-show-meta']       = true;
		
		$this->options = $default_options;
		$this->save_wp_options();
	}
	// BLAH
	private function install_directories() {
		$dirs = array(0=>'wp_uploads', 1=>'uploads', 2=>'thumbnails', 3=>'originals', 4=>'fullsize', 5=>'user_views');
		
		foreach ($dirs as $dir) {
			$is_dir  = is_dir($this->directories[$dir]);
			$is_file = is_file($this->directories[$dir]);
			if (!$is_dir && !$is_file) {
				if (@mkdir($this->directories[$dir]) == false) {
					return false;
				}
			}
		}
	}
	
	private function install_user_templates() {
		$system_templates_dir = $this->directories['views'];
		if (is_dir($system_templates_dir)) {
			$d = dir($system_templates_dir);
			while ($file = $d->read()) { 
				
				$extension = end(explode(".", strtolower($file)));
				$media_accepted = array('htm');
				
				if (in_array($extension, $media_accepted)) {
					
					$source = $system_templates_dir . '/' . $file;
					$dest   = $this->directories['user_views'] . '/' . $file;
					
					if (!copy($source, $dest)) {
						return false;
					}
				}
			}
			
			$d->close();
		}
	}
	
	private function install_default_term() {
		if ($this->get_default_term() !== NULL) {
			return false;
		}
		
		$category_slug = $this->string2slug($this->default_term_name);
		$attr          = array('slug'=>$category_slug);
		$insert_return = wp_insert_term($this->default_term_name, $this->custom_tax_name, $attr);
		if ($insert_return instanceof WP_Error) {
			foreach ($insert_return->get_error_messages() as $error) {
				$this->wp_error(sprintf(__("There was an error creating the default term: %s", 'catablog'), $error));
			}
		}
		
		$this->terms = $this->get_terms(true);
		
		return $insert_return;
	}
	
	
	
	
	
	
	/*****************************************************
	**       - UPGRADE METHODS
	*****************************************************/
	private function is_latest_version() {
		$old_version = $this->options['version'];
		$new_version = $this->version;
		
		$compare = version_compare($old_version, $new_version, '<');
		return !$compare;
	}
	
	public function upgrade() {
		// do not upgrade if latest version or options are not installed
		if ($this->is_latest_version() || $this->options == false) {
			return false;
		}
		
		$this->upgrade_user_options();
		$this->upgrade_options();
		$this->upgrade_directories();
		
		$this->wp_message(__('CataBlog options and directories have been successfully upgraded.', 'catablog'));
	}
	
	private function upgrade_user_options() {
		if (version_compare($this->options['version'], '1.2.9', '<')) {
			$screen_settings = array('limit'=>20);
			$user  = wp_get_current_user();
			update_user_meta($user->ID, $this->custom_user_meta_name, $screen_settings);
		}
		
		if (version_compare($this->options['version'], '1.5', '<')) {
			$user = wp_get_current_user();
			$settings = $this->getDefaultUserSettings();
			update_user_meta($user->ID, $this->custom_user_meta_name, $settings);
		}
		
		// !! MAKE SURE NEW UPDATES GO AT THE END OF THE VERSION LIST !!
	}
	
	private function upgrade_options() {
		
		// add new public post and taxonomy options
		if (version_compare($this->options['version'], '1.2.9', '<')) {
			$this->options['public_posts']     = false;
			$this->options['public_post_slug'] = $this->custom_post_name;
			$this->options['public_tax_slug']  = $this->custom_tax_name;
		}
		
		// add new thumbnail width and height options
		if (version_compare($this->options['version'], '1.2.9.8', '<')) {
			$this->options['thumbnail-width']  = $this->options['thumbnail-size'];
			$this->options['thumbnail-height'] = $this->options['thumbnail-size'];
			unset($this->options['thumbnail-size']);
		}
		
		// add new lightbox navigation option
		if (version_compare($this->options['version'], '1.2.9.9', '<')) {
			$this->options['lightbox-navigation'] = false;
		}
		
		// remove old template option settings
		if (version_compare($this->options['version'], '1.3', '<')) {
			
			// install the template directory and files so they may be overwritten
			$this->install_directories();
			$this->install_user_templates();
			
			// Save the old default template stored in catablog-options to default.htm
			if (isset($this->options['view-theme'])) {
				if ($this->string_length($this->options['view-theme']) > 0) {
					$fn = "default.htm";
					$filepath = $this->directories['user_views'] . '/' . $fn;
					$fp = fopen($filepath, 'w');
					fwrite($fp, $this->options['view-theme']);
					fclose($fp);
				}
			}
			
			// Save the old store template stored in catablog-options to store.htm
			if (isset($this->options['view-buynow'])) {
				if ($this->string_length($this->options['view-buynow']) > 0) {
					$fn = "store.htm";
					$filepath = $this->directories['user_views'] . '/' . $fn;
					$fp = fopen($filepath, 'w');
					fwrite($fp, $this->options['view-buynow']);
					fclose($fp);
				}
			}
			
			// Remove the old template information from catablog-options
			unset($this->options['view-theme']);
			unset($this->options['view-buynow']);
			unset($this->options['paypal-email']);
		}
		
		// add new archive template to user's template files
		if (version_compare($this->options['version'], '1.3.2', '<')) {
			if ($this->upgrade_user_templates() === false) {
				$this->wp_error(__('The New CataBlog Templates cannot be written. ', "catablog") . __('Please check your server file permissions and apache configuration.', "catablog"));
				return false;
			}
			
			$this->options['excerpt-length'] = 55;
			$this->options['nav-prev-label'] = __('Previous', "catablog");
			$this->options['nav-next-label'] = __('Next', "catablog");
			$this->options['nav-location']   = 'bottom';
			$this->options['nav-show-meta']  = true;
		}
		
		// !! MAKE SURE NEW UPDATES GO AT THE END OF THE VERSION LIST !!
		
		// update the version number
		$this->options['version']  = $this->version;
		$this->save_wp_options();
	}
	
	private function upgrade_directories() {
		// their are no upgrade instructions for the directories
	}
	
	private function upgrade_user_templates() {
		$system_templates_dir = $this->directories['views'];
		if (is_dir($system_templates_dir)) {
			$d = dir($system_templates_dir);
			while ($file = $d->read()) { 
				
				$extension = end(explode(".", strtolower($file)));
				$media_accepted = array('htm');
				
				if (in_array($extension, $media_accepted)) {
					
					$source = $system_templates_dir . '/' . $file;
					$dest   = $this->directories['user_views'] . '/' . $file;
					
					// only copy the file to the user templates directory if it doesn't exist
					if (!is_file($dest)) {
						if (!copy($source, $dest)) {
							return false;
						}
					}
				}
			}
			
			$d->close();
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - REMOVAL METHODS
	*****************************************************/
	
	public function deactivate() {
		
	}
	
	private function remove() {
		$this->remove_options();
		$this->remove_directories();
		$this->remove_terms();
	}
	
	private function remove_options() {
		if (delete_option($this->options_name)) {
			$this->options = false;
		}
	}
	
	private function remove_directories($dirs=null) {
		if ($dirs === null) {
			$dirs = array('user_views', 'fullsize', 'thumbnails', 'originals', 'uploads');
		}
		
		foreach ($dirs as $dir) {
			$mydir = $this->directories[$dir];
			if (is_dir($mydir)) {
				$d = dir($mydir);
				while ($entry = $d->read()) { 
					if ($entry != "." && $entry != "..") {
						unlink($mydir . '/' . $entry); 
					} 
				} 
				$d->close(); 

				rmdir($mydir);		
			}
			else {
				if (is_file($mydir)) {
					unlink($mydir);
				}
			}
		}
		
		delete_transient('dirsize_cache'); // WARNING!!! transient label hard coded.
	}
	
	private function remove_terms() {
		foreach ($this->get_terms(true) as $term) {
			$success = wp_delete_term($term->term_id, $this->custom_tax_name);
		}
		
		$this->terms = array();
	}
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - IMPORT METHODS
	*****************************************************/
	public function xml_to_array($filename='') {
		if(!file_exists($filename) || !is_readable($filename)) {
			return FALSE;
		}
		
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($filename);
		if ($xml === false) {
			return FALSE;
		}
		
		$data = array();
		foreach ($xml->item as $item) {
			$row = array();
			
			$row['id']           = (integer) $item->id;
			$row['order']        = (integer) ((isset($item->order))? $item->order : $item->ordinal);
			$row['date']         = (string) $item->date;
			$row['image']        = (string) $item->image;
			$row['title']        = (string) $item->title;
			$row['link']         = (string) $item->link;
			$row['description']  = (string) $item->description;
			$row['price']        = (float) $item->price;
			$row['product_code'] = (string) $item->product_code;
			
			$subimages = array();
			foreach ($item->subimages as $images) {
				foreach ($images as $image) {
					$subimages[] = (string) $image;
				}
			}
			
			$row['subimages']    = $subimages;
			
			$terms = array();
			if (isset($item->tags)) {
				$tags  = (string) $item->tags;
				$terms = explode(' ', trim($tags));
			}
			else {
				foreach($item->categories as $categories) {
					foreach ($categories as $category) {
						$terms[] = (string) $category;
					}
				}
			}
			$row['categories'] = implode('|', $terms);
			
			$row['date'] = date('Y-m-d G:i:s', strtotime($row['date']));
			
			$data[] = $row;
		}
		
		return $data;
	}
	
	public function csv_to_array($filename='', $delimiter=',') {
		if(!file_exists($filename) || !is_readable($filename)) {
			return __('Uploaded CSV File Could Not Be Parsed, Check That The File\'s Format Is Valid.', 'catablog');
		}
		
		$line_errors = array();
		
		ini_set('auto_detect_line_endings', true);
		
		$header = NULL;
		$data = array();
		if (($handle = fopen($filename, 'r')) !== FALSE) {
			
			$file_line = 1;
			while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
				
				if ($row[0] === NULL) {
					$file_line++;
					continue;
				}
				
				if(!$header) {
					$header = $row;
				}
				else {//if (count($row) == 9) {
					if (count($row) === count($header)) {
						$data[] = array_combine($header, $row);
					}
					else {
						$line_errors[] = $file_line;
					}
				}
				
				$file_line++;
			}
			fclose($handle);
		}
		
		foreach ($data as $key => $row) {
			$data[$key]['date'] = date('Y-m-d G:i:s', strtotime($row['date']));
		}
		
		if (count($line_errors) > 0) {
			return sprintf(__("There was an error parsing your CSV file on lines: %s.", "catablog"), implode($line_errors, ', '));
		}
		
		return $data;
	}
	
	private function load_array_to_database($data) {
		
		// extract a list of every category in the import
		$import_terms = array($this->default_term_name);
		foreach ($data as $key => $row) {
			$row_terms = array();
			if ($this->string_length($row['categories']) > 0) {
				$row_terms = explode('|', $row['categories']);
			}
			
			foreach ($row_terms as $row_term) {
				$import_terms[] = $row_term;
			}
		}
		$import_terms = array_intersect_key($import_terms,array_unique(array_map('strtolower',$import_terms)));
		
		// extract a list of every category that is not already created
		$make_terms = $import_terms;
		if (isset($_REQUEST['catablog_clear_db']) === false) {
			$existant_terms = $this->get_terms(true);
			foreach ($existant_terms as $existant_term) {
				foreach ($make_terms as $key => $make_term) {				
					if ($make_term == $existant_term->name) {
						unset($make_terms[$key]);
						unset($import_terms[$key]);
						
						$import_terms[$existant_term->term_id] = $existant_term->name;
					}
				}
			}
		}
		
		// create the neccessary new categories from the previous extraction
		$new_term_made = false;
		foreach ($make_terms as $key => $make_term) {
			$category_slug = $this->string2slug($make_term);
			$attr          = array('slug'=>$category_slug);
			$insert_return = wp_insert_term($make_term, $this->custom_tax_name, $attr);
			
			if ($insert_return instanceof WP_Error) {
				foreach ($insert_return->get_error_messages() as $error) {
					echo '<li class="error">' . __("Error:", 'catablog') . sprintf(' %s %s', "<strong>$make_term</strong>", $error) . '</li>';
				}
			}
			else {
				if (isset($insert_return['term_id'])) {
					$new_term_id = $insert_return['term_id'];
					$new_term_name = $make_term;
					
					if ($new_term_id > 0) {
						unset($import_terms[$key]);
						unset($make_terms[$key]);
						
						$import_terms[$new_term_id] = $new_term_name;
						$new_term_made = true;
						echo '<li class="updated">' . __("Success:", 'catablog') . sprintf(__(' %s inserted into catalog categories.', 'catablog'), "<em>$make_term</em>") . '</li>';
					}
				}							
			}
		}
		
		// render complete making new categories message
		if ($new_term_made) {
			echo '<li class="updated"><strong>'.__('All New Categories Created', 'catablog').'</strong></li>';
			echo '<li>&nbsp;</li>';
		}
		else {
			echo '<li class="updated"><strong>'.__('No New Categories Created', 'catablog').'</strong></li>';
			echo '<li>&nbsp;</li>';
		}
		
		// load all existing catalog item ids
		$existant_ids = CataBlogItem::getItemIds();
		
		// set new order to the number of catablog posts plus one
		// $new_order = wp_count_posts($this->custom_post_name)->publish + 1;
		
		// import each new catalog item
		foreach ($data as $row) {
			
			// set error and confermation variables
			$error = false;
			$success_message = '<li class="updated">' . __('Insert:', 'catablog') . sprintf(__(' %s inserted into the database.', 'catablog'), '<em>'.$row['title'].'</em>') . '</li>';
//			$success_message = '<li class="updated">' . __('Update:', 'catablog') . sprintf(__(' %s updated in database.', 'catablog'), '<em>'.$row['title'].'</em>') . '</li>';
			$error_message   = '<li class="error">' . __('Error:', 'catablog') . sprintf(__(' %s was not inserted into the database.', 'catablog'), '<strong>'.$row['title'].'</strong>') . '</li>';
			
			// Validate that the title and image values are set
			if ($this->string_length($row['title']) < 1) {
				$error = '<li class="error"><strong>' . __('Error:', 'catablog') . "</strong> " . __('Item had no title and could not be made.', 'catablog') . '</li>';
			}
			if ($this->string_length($row['image']) < 1) {
				$error = '<li class="error"><strong>' . __('Error:', 'catablog') . "</strong> " . __('Item had no primary image name and could not be made.', 'catablog') . '</li>';
			}
			
			if ($error) {
				echo $error;
			}
			else {
				
				// transform categories array
				if (isset($row['categories'])) {
					$categories = $row['categories'];
					if (is_array($categories) === false) {
						$categories = explode('|', $categories);
						$row['categories'] = array();
					}
					foreach ($categories as $cat) {
						foreach ($import_terms as $term_id => $term_name) {
							if (strtolower($term_name) == strtolower($cat)) {
								$row['categories'][] = $term_id;
							}
						}
					}
				}
				
				// transform subimages array
				if (isset($row['subimages'])) {
					$subimages = $row['subimages'];
					if (is_array($subimages) === false) {
						if ($this->string_length($subimages) > 0) {
							$subimages = explode('|', $subimages);
						}
						else {
							$subimages = array();
						}
					}
					$row['sub_images'] = $subimages;
					unset($row['subimages']);
				}
				
				// unset id if it is not already in the database.
				if (isset($row['id'])) {
					if (in_array($row['id'], $existant_ids)) {
						$success_message = '<li class="updated">' . __('Update:', 'catablog') . sprintf(__(' %s updated in database.', 'catablog'), '<em>'.$row['title'].'</em>') . '</li>';
					}
					else {
						$success_message = '<li class="updated">' . __('Insert:', 'catablog') . sprintf(__(' %s inserted into the database.', 'catablog'), '<em>'.$row['title'].'</em>') . '</li>';
						unset($row['id']);
					}
				}
				
				$item = new CataBlogItem($row);
				
				// $item->setOrder($new_order);
				// var_dump($item);
				$results = $item->save();
				
				if ($results === true) {
					// if (!isset($row['id'])) {
						// $existant_ids[] = $item->getId();
					// }
					echo $success_message;
				}
				else {
					echo $results;
				}
			}
			
			// $new_order += 1;
			// return false;
		}
		
		echo '<li class="updated"><strong>' . sprintf(__('%s Catalog Items Inserted', 'catablog'), count($data)) . '</strong></li>';
		
	}
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - ADMINISTRATION METHODS
	*****************************************************/
	private function unlock_directories() {
		$success = true;
		$dirs = array(1=>'uploads', 2=>'thumbnails', 3=>'originals', 4=>'fullsize');
		
		foreach ($dirs as $dir) {
			$is_dir  = is_dir($this->directories[$dir]);
			if ($is_dir) {
				if (chmod($this->directories[$dir], 0777) === false) {
					$success = false;
				}
			}
		}
		
		return $success;
	}
	
	private function lock_directories() {
		$success = true;
		$dirs = array(1=>'uploads', 2=>'thumbnails', 3=>'originals', 4=>'fullsize');
		
		foreach ($dirs as $dir) {
			$is_dir  = is_dir($this->directories[$dir]);
			if ($is_dir) {
				if (chmod($this->directories[$dir], 0755) === false) {
					$success = false;
				}
			}
		}
		
		return $success;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - HELPER METHODS
	*****************************************************/
	private function check_file_upload_errors() {
		foreach($_FILES as $file) {
			if ($file['error'] > 0) {
				switch($file['error']) {
					case UPLOAD_ERR_INI_SIZE:
						return __("Uploaded File Exceeded The PHP Configurations Max File Size.", 'catablog');
						break;
					case UPLOAD_ERR_FORM_SIZE:
						return __("Upload File Exceeded The HTML Form's Max File Size.", 'catablog');
						break;
					case UPLOAD_ERR_PARTIAL:
						return __("File Only Partially Uploaded, Please Try Again.", 'catablog');
						break;
					case UPLOAD_ERR_NO_FILE:
						return __("No File Selected For Upload. Please Resubmit The Form.", 'catablog');
						break;
					case UPLOAD_ERR_NO_TMP_DIR:
						return __("Your Server's PHP Configuration Does Not Have A Temporary Folder For Uploads, Please Contact The System Admin.", 'catablog');
						break;
					case UPLOAD_ERR_CANT_WRITE:
						return __("Your Server's PHP Configuration Can Not Write To Disc, Please Contact The System Admin.", 'catablog');
						break;
					case UPLOAD_ERR_EXTENSION:
						return __("A PHP Extension Is Blocking PHP From Excepting Uploads, Please Contact The System Admin.", 'catablog');
						break;
					default:
						return __("An Unknown Upload Error Has Occurred", 'catablog');
				}
			}
		}
		
		return true;
	}
	private function save_wp_options() {
		update_option($this->options_name, $this->options);
	}
	
	public function get_options() {
		return get_option($this->options_name);
	}
	
	public function get_custom_user_meta_name() {
		return $this->custom_user_meta_name;
	}
	
	public function get_terms($reload=false) {
		if ($this->terms == NULL || $reload) {
			$database_fetch = get_terms($this->custom_tax_name, 'hide_empty=0');
			if ($database_fetch == NULL) {
				$this->terms = array();
			}
			else {
				$this->terms = $database_fetch;
			}
		}
		
		return $this->terms;
	}
	
	public function get_default_term() {
		if ($this->default_term == NULL) {
			$terms = $this->get_terms();
			foreach ($terms as $term) {
				if ($term->name == $this->default_term_name) {
					$this->default_term = $term;
				}
			}
		}
		return $this->default_term;
	}
	
	private function getDefaultUserSettings() {
		$screen_settings = array();
		
		$screen_settings['library'] = array();
		$screen_settings['library']['limit'] = 20;
		$screen_settings['library']['hide-columns'] = array();
		
		$screen_settings['add-new'] = array();
		$screen_settings['add-new']['hide-columns'] = array();
		
		$screen_settings['gallery'] = array();
		$screen_settings['gallery']['limit'] = 20;
		$screen_settings['gallery']['hide-columns'] = array();
		
		return $screen_settings;
	}
	
	private function string2slug($string) {
		$slug = strtolower($string);
		$slug = sanitize_title($slug);
		$slug = @wp_unique_term_slug($slug);
		return $slug;
	}
	
	private function string_length($string) {
		if (function_exists('mb_strlen')) {
			return mb_strlen($string);
		}
		else {
			return strlen($string);
		}
	}
	
	private function sub_string($string, $start, $length) {
		if (function_exists('mb_substr')) {
			return mb_substr($string, $start, $length);
		}
		else {
			return substr($string, $start, $length);
		}
	}
	
	private function wp_message($message) {
		$this->wp_messages[] = $message;
	}
	
	private function wp_error($message) {
		$this->wp_error_messages[] = $message;
	}
	
	public function render_catablog_admin_message() {
		foreach ($this->wp_messages as $message) {
			echo "<div id='message' class='updated'><p>";
			echo "	<strong>$message</strong>";
			echo "</p></div>";
		}
		
		foreach ($this->wp_error_messages as $message) {
			echo "<div id='message' class='error'><p>";
			echo "	<strong>$message</strong>";
			echo "</p></div>";
		}
	}
	

	
	




		
}