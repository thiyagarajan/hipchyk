<?php
/*
Plugin Name: WP Social Links
Plugin URI: http://www.bundy.ca/wp-social-links
Description: Hook into any post type. Social links everywhere! Great for company profiles, radio or TV programs, post author pages, and more!
Version: 0.3.1
Author: Mitchell Bundy
Author URI: http://www.bundy.ca/
*/
/*  Copyright 2008  Mitchell Bundy  (email : mitch@bundy.ca)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
	
	Links to the author's website should remain where they are and cannot be
	removed without permission from the author.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (!class_exists("SocialLinksMB")) {
	class SocialLinksMB {
		
		// DEFAULT SITES
				
		var $sites = array(
			// TODO : URL regex
			 /* 
		 	'slug' => array(
		 		'name' => Name of the Social Link (required),
				'class' => Override the use of 'slug' as the class (optional),
				'title' => HTML title tag, %s for the post_type/site name,
				'href_pre' => before the link (ex. mailto:),
				'href_post' => after the link (ex. ?ref=blah),
				'active' => default true.
		 	), */
			'facebook_page' => array(
				'name' =>'Facebook Page',
				'class' => 'facebook',
				'title' => 'Join %s\'s group on Facebook',
				'active' => true
			),
			'facebook_group' => array(
				'name' =>'Facebook Group',
				'class' => 'facebook',
				'title' => 'Become a Fan of %s on Facebook',
				'active' => true
			),
			'twitter' => array(
				'name' => 'Twitter',
				'title' => 'Follow %s on Twitter',
				'active' => true
			),
			'linkedin' => array(
				'name' => 'LinkedIn',
				'title' => 'Add %s to your LinkedIn network',
				'active' => true
			),
			'myspace' => array(
				'name' => 'MySpace',
				'title' => 'Add %s as a friend on MySpace',
				'active' => true
			),
			'tumblr' => array(
				'name' => 'Tumblr',
				'title' => 'Follow %s on Tumblr',
				'active' => true
			),
			'flickr' => array(
				'name' => 'Flickr',
				'title' => 'Add %s on Flickr',
				'active' => true
			),
			'blogger' => array(
				'name' => 'Blogger',
				'title' => 'Follow %s on Blogger',
				'active' => true
			),
			'youtube' => array(
				'name' => 'YouTube',
				'title' => 'Subscribe to %s on YouTube',
				'active' => true
			),
			'vimeo' => array(
				'name' => 'Vimeo',
				'title' => 'Subscribe to %s on Vimeo',
				'active' => true
			),
			'soundcloud' => array(
				'name' => 'Soundcloud',
				'title' => 'Follow %s on Soundcloud',
				'active' => true
			),
			'email' => array(
				'name' => 'Email Address',
				'title' => 'Send %s an email',
				'href_pre' => 'mailto:',
				'active' => true
			)
		);
		
		var $options = array(
			'show_name' => true, // Show social site's name in link, useful for CSS using background.
			'show_div' => true, // Show the wrapper DIV
			'div_class' => 'wp-social-links', // Class for the wrapper DIV
			'version' => '0.3.1', // Plugin Version
			'limit' => 0 // 0 = unlimited
		);
		
		private $post_types = array();
		
		private $special_post_types = array(
			'users'
		);
				
		function __construct() {
			// Check database for default setting overrides
			//delete_option('wp-social-links');
			$options = get_option('wp-social-links');
			$options = maybe_unserialize($options);
			
			// Do updates
			if (is_array($options)) {
				// nothing to do for version 0.3.1, update version number.
				
				// change version number. We didn't use a string in 0.3, so we gotta fix that...
				if ((string)$options['version'] != $this->options['version']) {
					$options['version'] = $this->options['version'];
				}
			}
			
			if ($options && is_array($options)) {
				$this->sites = $options['sites'];
				$this->post_types = $options['post_types'];
				unset($options['sites'], $options['post_types']);
				$this->options = $options;
				ksort($this->sites);
			}
			// Set option to defaults if it doesn't exist. Update any changes
			$this->update_options();
			
			// Action Hooks
			add_action('admin_menu', array($this,'meta_boxes') );
			add_action('save_post', array($this, 'save_postdata'), 10, 2 );
			add_action('init', array($this, 'set_post_type_supports') );
			add_action('wp_print_styles', array($this, 'enqueue_style') );
			
			// User Profile Hooks
			add_action( 'show_user_profile', array($this, 'profile_fields') );
			add_action( 'edit_user_profile', array($this, 'profile_fields') );
			add_action( 'personal_options_update', array($this, 'save_profile_fields') );
			add_action( 'edit_user_profile_update', array($this, 'save_profile_fields') );
			
			// AJAX Hooks
			add_action('wp_ajax_save_wp_social_links_collapse_state', array($this, 'save_wp_social_links_collapse_state'));
			add_action('wp_ajax_save_wp_social_links_screen_options', array($this, 'save_wp_social_links_screen_options'));
			
			// Filters
			add_filter('screen_settings', array($this, 'screen_options'), 10, 2);
			add_filter('plugin_row_meta', array($this, 'plugin_links'), 10, 2);
		}
		
		function plugin_links($links, $file) {
			if ( $file == plugin_basename(__FILE__) )
			{
				$links[] = '<a href="http://www.bundy.ca/wp-social-links" target="_blank">' . __('Docs') . '</a>';
				$links[] = '<a href="http://wordpress.org/tags/wp-social-links?forum_id=10" target="_blank">' . __('Support') . '</a>';
				$links[] = '<a href="http://www.bundy.ca/contact" target="_blank">' . __('Donate') . '</a>';
			}
			
			return $links;
		}
		
		function set_post_type_supports() {
			//print_r($this->post_types);
			$post_types = get_post_types();
			foreach ($this->post_types as $pt => $vals) {
				if ($vals['active'])
					add_post_type_support($pt, 'wp-social-links');
				$key = array_search($pt, $post_types);
				unset($post_types[$key]);
				//echo $vals;
				if ($vals['sites'] == 'all') {
					$this->post_types[$pt]['sites'] = array();
					foreach ($this->sites as $site => $val) {
					//	if ($val['active'])
							$this->post_types[$pt]['sites'][] = $site;
					}
					$this->update_options();
				}
			}
			
			if ($_GET['page'] == 'wp-social-links') {
				global $wp_post_types;
				foreach ($this->special_post_types as $pt) {
					$wp_post_types[$pt] = new stdClass;
					$wp_post_types[$pt]->name = $pt;
				}
			}
			
			// Register Style
			wp_register_style('wp-social-links', plugins_url('wp-social-links.css', __FILE__), array(), $this->options['version']);
		}
		
		function enqueue_style() {
			wp_enqueue_style('wp-social-links');
		}
		
		function post_type_supports($post_type, $supports = 'wp-social-links') {
			if (in_array($post_type, $this->special_post_types) && $this->post_types[$post_type]['active'])
				return true;
			return post_type_supports($post_type, $supports);
		}
		
		function post_type_site($post_type, $site) {
			if (is_array($this->post_types[$post_type]['sites']) && in_array($site, $this->post_types[$post_type]['sites']) && $this->post_type_supports($post_type, 'wp-social-links') && $this->sites[$site]['active'])
				return true;
			else return false;
		}
		
		function get_post_type_sites($post_type) {
			$sites = $this->sites;
			foreach ($sites as $slug => $site) {
				if (!$this->post_type_site($post_type, $slug))
					unset($sites[$slug]);
			}
			return $sites;
		}
		
		function update_options() {
			ksort($this->sites);
			$insert = $this->options;
			$insert['sites'] = $this->sites;
			$insert['post_types'] = $this->post_types;
			update_option('wp-social-links', $insert);
		}
		
		function get_type_meta($id, $post_type) {
			if ($post_type == 'users') {
				// Get User Meta, because it's not a real post type.
				return get_user_meta($id, 'wpsociallinks', true);
			} else {
				// Otherwise, we'll get the the post data
				return get_post_meta($id, 'wp-social-links', true);
			}
		}
		
		function links_box($pt = NULL, $pid = NULL) {
			global $post_type;
			if (empty($post_type) && $pt) $post_type = $pt;
			if (!empty($pt) && !empty($pid) && !is_array($pid)) {
				$id = $pid;
			} else {
				$id = $_GET['post'];
			}
			$links = $this->get_type_meta($id, $post_type);

			$links = maybe_unserialize($links);
			$sites = $this->get_post_type_sites($post_type);
			echo '<input type="hidden" name="wp-social-links_noncename" id="wp-social-links_noncename" value="' . 
    wp_create_nonce( plugin_basename(__FILE__) ) . '" />'; ?>
    		<style type="text/css">
			#wp-social-links td.input input.url {
				width:500px;
			}
			</style>
			<table width="100%" cellpadding="0" cellspacing="0" border="0"<?=(($post_type == 'users') ? ' class="form-table"' : '')?>>
			<tbody id="wp-social-links-inner">
            <?php
			if ($links) {
				foreach ($links as $link => $meta) {
					if ($this->post_type_site($post_type, $link)) {
						echo '<tr id="wp-social-links-'.$link.'">
							<td class="name">'.$sites[$link]['name'].'</td> 
							<td class="input"><input type="text" class="url regular-text" name="wp-social-links['.$link.'][url]" value="'.$meta['url'].'" /> <input type="button" class="button wp-social-links-remove" value="'.__('Remove').'" />
							</td>
						</tr>';
					}
					unset($sites[$link]);
				}
			}
			echo '</tbody>';
			if (count($sites)) {
				echo '<tr><'.(($post_type == 'users') ? 'th' : 'td').'>';
				echo '<select id="wp-social-links-type">';
				foreach ($sites as $slug => $meta) {
					echo '<option value="'.$slug.'" id="wp-social-links-option-'.$slug.'">'.$meta['name'].'</option>';
				}
				echo '</select>';
				echo '</'.(($post_type == 'users') ? 'th' : 'td').'>
			<td class="input"><input type="text" id="wp-social-links-new" class="url regular-text" /> <input type="button" class="button" id="wp-social-links-add" value="'.__('Add').'" /></td>';
			echo '</tr>';
			}
			echo '</table>';
			?><script type="text/javascript">
			jQuery('#wp-social-links-add').click(function() {
				var type = jQuery('#wp-social-links-type').val();
				var url = jQuery('#wp-social-links-new').val();
				var name = jQuery('#wp-social-links-option-'+type).html();
				if (url != '') {
					jQuery('#wp-social-links-inner').append('<tr id="wp-social-links-'+type+'"><td class="name">'+name+'</td> <td class="input"><input type="text" class="url regular-text" name="wp-social-links['+type+'][url]" value="'+url+'" /> <input type="button" class="button wp-social-links-remove" value="<?=__('Remove')?>" /></td></tr>');
					jQuery('#wp-social-links-option-'+type).remove();
					jQuery('#wp-social-links-new').val('');
				}
			});
			
			jQuery('.wp-social-links-remove').live('click', function() {
				var name = jQuery(this).parent().parent().children('.name').text();
				var type = jQuery(this).parent().parent().attr('id').substr(16, jQuery(this).parent().parent().attr('id').length);
				//alert(name+'a');
				jQuery('#wp-social-links-type').append('<option value="'+type+'" id="wp-social-links-option-'+type+'">'+name+'</option>');
				jQuery(this).parent().parent().remove();
			});
			</script><?php
		}
		
		function meta_boxes() {
			add_options_page(__('WP Social Links'), __('WP Social Links'), 'manage_options', 'wp-social-links', array($this, 'pages'));
			//add_screen_option('layout_columns', array('max' => 2) );
			if (count($this->post_types)):
				foreach ($this->post_types as $post_type => $vals) {
					$sites = $this->get_post_type_sites($post_type);
					if (count($sites)) {
						add_meta_box(
							'wp-social-links', 
							__('Social Links'),
							array(&$this,'links_box'), 
							$post_type, 
							'normal',
							'high'
						);
					}
				}
			endif;
		}
		
		function save_postdata($post_id, $post) {
			if ( !wp_verify_nonce( $_POST['wp-social-links_noncename'], plugin_basename(__FILE__) )) {
				return $post_id;
			  }
			
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
				 return $post_id;
			if ( !$this->post_type_supports($_POST['post_type'], 'wp-social-links') ) {
				  return $post_id;
			}
			
			add_post_meta($post_id, 'wp-social-links', $_POST['wp-social-links'], true) or update_post_meta($post_id, 'wp-social-links', $_POST['wp-social-links']);
		}
		
		function pages() {
			// Security - nonce
			if (isset($_POST['submit'])  || isset($_REQUEST['enable']) || isset($_REQUEST['disable']) || isset($_REQUEST['action'])) {
				if ( !wp_verify_nonce( $_REQUEST['_noncename'], plugin_basename(__FILE__) )) {
					wp_die(__('Invalid Nonce'),__('Nu uh.'), true);
				}
			}
			$notices = array(
				0 => '',
				1 => __('Slug already in use.'),
				2 => __('Please include a slug and name.'),
				3 => __('An Error Occured.'),
				4 => __('Invalid Post Type.'),
				5 => __('Invalid Slug. May only contain letters, hyphens or underscores.')
			);
			$messages = array(
				0 => '',
				1 => __('Site Added.'),
				2 => __('Site Deleted.'),
				3 => __('Site Updated.'),
				4 => __('Sites Deleted.'),
				5 => __('Sites Deactivated.'),
				6 => __('Site Deactivated.'),
				7 => __('Sites Activated.'),
				8 => __('Site Activated.'),
				9 => sprintf(__('%s Enabled'), $_GET['enable']),
				10 => sprintf(__('%s Disabled'), $_GET['disable'])
			);
			
			?>
		<div class="wrap">
        	<?php screen_icon();?>
            <h2 class="nav-tab-wrapper">
            	<?=__('WP Social Links')?>
            	<a href="<?=admin_url('options-general.php?page=wp-social-links')?>" class="nav-tab <?=(empty($_GET['a']) ? 'nav-tab-active' : '')?>"><?=__('Sites')?></a>
                <a href="<?=admin_url('options-general.php?page=wp-social-links&a=post_types')?>" class="nav-tab<?=($_GET['a'] == 'post_types' ? ' nav-tab-active' : '')?>"><?=__('Post Types')?></a>
            <? /*    <a href="<?=admin_url('options-general.php?page=wp-social-links&a=options')?>" class="nav-tab<?=($_GET['a'] == 'options' ? ' nav-tab-active' : '')?>"><?=__('Options')?></a>*/?>
            </h2>
            <?php
				// TODO : Settings page
				if ($_GET['a'] == 'post_types') {
					$this->post_types_page($notices, $messages);
				} else {
					$this->sites_page($notices, $messages);
				}
			?>
        </div>
		<?php }
		
		function post_types_page($notices, $messages) {
			$nonce = wp_create_nonce( plugin_basename(__FILE__) );
			$message = false;
			$notice = false;
			// Disable Post Type
			if (!empty($_GET['disable'])) {
				if (post_type_exists($_GET['disable'])) {
					remove_post_type_support($_GET['disable'], 'wp-social-links');
					$this->post_types[$_GET['disable']]['active'] = false;
					$this->update_options();
					$message = $messages[10];
				} else {
					$notice = $notices[4];
				}
			}
			
			// Enable Post Type
			if (!empty($_GET['enable'])) {
				if (post_type_exists($_GET['enable'])) {
					add_post_type_support($_GET['enable'], 'wp-social-links');
					$this->post_types[$_GET['enable']]['active'] = true;
					$this->update_options();
					$message = $messages[9];
				} else {
					$notice = $notices[4];
				}
			}
			
			// Deactivate Site in Post Type
			if (!empty($_GET['edit']) && $_REQUEST['action'] == 'deactivate' && !empty($_REQUEST['sites'])) {
				$this->deactivate_sites($_REQUEST['sites'], $_GET['edit']);
				if (count($_REQUEST['sites']) > 1) {
					$message = $messages[5];
				} else {
					$message = $messages[6];
				}
			}
			
			// Activate Site in Post Type
			if (!empty($_GET['edit']) && $_REQUEST['action'] == 'activate' && !empty($_REQUEST['sites'])) {
				$this->activate_sites($_REQUEST['sites'], $_GET['edit']);
				if (count($_REQUEST['sites']) > 1) {
					$message = $messages[7];
				} else {
					$message = $messages[8];
				}
			}
			
			// Invalid Post Type
			if (isset($_GET['edit']) && !post_type_exists($_GET['edit'])) {
				$notice = $notices[4];
			}
		
			$post_types = get_post_types();
			sort($post_types);
			
			$active = array();
			foreach ($post_types as $key => $pt) {
				if ($this->post_type_supports($pt, 'wp-social-links')) {
					unset($post_types[$key]);
					$active[] = $pt;
				}
			}
			$post_types = array_merge($active, $post_types);
			?>
            <style type="text/css">
				.widget {
					width:90%;
					margin-left: auto;
					margin-right: auto;
				}
				.widget h3 {
					margin:0;
					padding:0;
				}
				.widget h3 a {
					display:block;
					color:inherit;
					text-decoration:none;
					padding:10px;
				}
				.post_type .handlept {
					background:url(images/arrows.png) no-repeat;
					background-position:right -98px;
					float:right;
					width:27px;
					height:30px;
					margin-right:10px;
				}
				.post_type.active .handlept,
				.post_type:hover .handlept {
					background-image:url(images/arrows-dark.png);
				}
				.sites.disabled {
					opacity:0.7;
				}
				.site.disabled {
					color:grey;
				}
				.site .widget {
					padding:5px;
					display:block;
					color:inherit;
					text-decoration:none;
				}
				.site input {
					float:left;
					position:relative;
					top:5px;
					left:7px;
				}
				.postbox h3 {
					cursor:auto;
				}
				.site .actions {
					float:right;
				}
				.bulk_actions {
					margin-left:7px;
				}

			</style><br />
            <?php if ( $notice ) : ?>
            <div id="notice" class="error"><p><?php echo $notice ?></p></div>
            <?php endif; ?>
            <?php if ( $message ) : ?>
            <div id="message" class="updated"><p><?php echo $message; ?></p></div>
            <?php endif; ?>
        	<div id="col-container">
                	<div id="col-right">
                    	<div class="col-wrap">
                        <?php if (!empty($_GET['edit']) && post_type_exists($_GET['edit'])): 
							$pt_support = $this->post_type_supports($_GET['edit'], 'wp-social-links');?>
                        	<h2>
							<?=$_GET['edit'].(in_array($_GET['edit'], $this->special_post_types)?' ('.__('special').')':'')?> 
                            <?php if ($pt_support): ?>
                            <a href="<?=admin_url('options-general.php?page=wp-social-links&a=post_types&edit='.$_GET['edit'].'&disable='.$_GET['edit'].'&_noncename='.$nonce)?>" class="add-new-h2">Disable</a>
                            <?php else: ?>
                            <a href="<?=admin_url('options-general.php?page=wp-social-links&a=post_types&edit='.$_GET['edit'].'&enable='.$_GET['edit'].'&_noncename='.$nonce)?>" class="add-new-h2">Enable</a>
                            <?php endif; ?>
                            </h2>
                            <form action="<?=admin_url('options-general.php?page=wp-social-links&a=post_types&edit='.$_GET['edit'])?>" method="post">
                            <?php if ($pt_support): ?>
                            <input type="hidden" name="_noncename" value="<?=$nonce?>" />
                            <div class="alignleft bulk_actions">
                            	<input type="checkbox" />
                            	<select name="action">
                                	<option><?=__('Bulk Actions')?></option>
                                	<option value="activate"><?=__('Activate')?></option>
                                    <option value="deactivate"><?=__('Deactivate')?></option>
                                </select>
                                <input type="submit" class="button-secondary action" value="<?=__('Apply')?>" />
                            </div>
                            <div style="clear:both"><br /></div>
                            <?php endif; ?>
                            <div class="sites<?=(!$pt_support ? ' disabled' : '')?>">
                            <?php
							$disabled = array();
							$post_type_disabled = array();
							$sites = $this->sites;
							foreach ($sites as $site => $m) {
								if ($m['active'] && !$this->post_type_site($_GET['edit'], $site)) {
									$post_type_disabled[$site] = $m;
									unset($sites[$site]);
								} else if (!$m['active']) {
									$disabled[$site] = $m;
									unset($sites[$site]);
								}
							}
							$list_sites = array_merge($sites, $post_type_disabled);
							
							foreach ($list_sites as $site => $meta) {
								if ($meta['active']) {
									$pt_active = $this->post_type_site($_GET['edit'], $site);
									echo '<div class="site'.(!$pt_active ? ' disabled' : '').'"><input type="checkbox" name="sites[]" value="'.$site.'"'.(!$pt_support ? ' disabled="disabled"' : '').' />';
									echo '<div class="widget">';
									echo '<span>'.$meta['name'].'</span>';
									if ($pt_support) {
										echo '<span class="actions"><a href="'.admin_url('options-general.php?page=wp-social-links&a=post_types&edit='.$_GET['edit'].'&action='.($pt_active ? 'deactivate' : 'activate').'&sites[]='.$site.'&_noncename='.$nonce).'">'.($pt_active ? 'Deactivate' : 'Activate').'</a></span>';
									}
									echo '</div>';
									echo '</div>';
								}
							}
							?>
                            </div>
                            </form>
                        <?php elseif (isset($_GET['edit']) && !post_type_exists($_GET['edit'])): ?>
                        	<h2><?=__('Invalid Post Type')?></h2>
                        <?php endif; ?>
                        </div>
                    </div>
                    <div id="col-left">
                    	<div class="col-wrap">
                        	<?php
								global $current_user;
								$user_options = get_user_meta($current_user->ID, 'wp_social_link_opts', true);
								$i=0;
								foreach ($post_types as $pt) {
									if (!$this->post_type_supports($pt, 'wp-social-links') && !$first_inactive) {
										$first_inactive = true;
										?>
                                        <div class="metabox-holder meta-box-sortables">
                                            <div class="postbox<?=($user_options['disabled_pt_state'] == 'closed' ? ' closed' : '')?>" id="disabled_post_type">
                                                <div class="handlediv" title="Click to toggle"><br></div>
                                                <h3><?=__('Disabled Post Types')?></h3>
                                                <div class="inside"><br /><?php
									}
									?>
                                    <div class="widget post_type<?=(($_GET['edit'] == $pt) ? ' active' : '')?>">
                                 	   <div class="handlept"><br /></div>
                                       <h3><a href="<?=admin_url('options-general.php?page=wp-social-links&a=post_types&edit='.$pt)?>"><?=$pt.(in_array($pt, $this->special_post_types)?' ('.__('special').')':'')?></a></h3>
                                    </div>
									<?php
									$i++;
									if ($i == count($post_types) && $first_inactive)  {
										?></div>
                                        </div>
                                    </div><?php
									}
									
								}
							?>
                        </div>
                    </div>
            </div>
            <script type="text/javascript">
			
			jQuery('.bulk_actions input[type="checkbox"]').click(function() {
				checked = jQuery(this).attr('checked');
				if (checked)
					jQuery('.site input[type="checkbox"]').attr('checked', true);
				else
					jQuery('.site input[type="checkbox"]').attr('checked', false);
			});
			
			jQuery('.postbox .handlediv').click(function() {
				if (jQuery(this).parent().hasClass('closed')) {
					jQuery(this).parent().removeClass('closed');
					state = 'open';
				} else {
					jQuery(this).parent().addClass('closed');
					state = 'closed';
				}
				// Save Collapse State
				var data = {
					action: 'save_wp_social_links_collapse_state',
					state: state,
					nonce: '<?=$nonce?>'
				};
				jQuery.post(ajaxurl, data);
			});
			</script>
			
		<?php }
		
		function save_wp_social_links_collapse_state() {
			global $current_user;
			if ( !wp_verify_nonce( $_POST['nonce'], plugin_basename(__FILE__) )) {
				wp_die(__('Invalid Nonce'),__('Nu uh.'), true);
			}
			$options = get_user_meta($current_user->ID, 'wp_social_link_opts', true);
			if (isset($_POST['state'])) {
				$options['disabled_pt_state'] = $_POST['state'];
				update_usermeta($current_user->ID, 'wp_social_link_opts', $options);
			}
			die();
		}
		
		function save_wp_social_links_screen_options() {
			global $current_user;
			
			if ( !wp_verify_nonce( $_POST['nonce'], plugin_basename(__FILE__) )) {
				wp_die(__('Invalid Nonce'),__('Nu uh.'), true);
			}
			$options = get_user_meta($current_user->ID, 'wp_social_link_opts', true);
			
			if (isset($_POST['state']) && isset($_POST['id'])) {
				if ($_POST['state'] == 'on') {
					$options['screen_opt-sites'][$_POST['id'].'-hide'] = $_POST['id'];
				} else if (isset($options['screen_opt-sites'][$_POST['id'].'-hide'])) {
					unset($options['screen_opt-sites'][$_POST['id'].'-hide']);
				}
				update_usermeta($current_user->ID, 'wp_social_link_opts', $options);
			}
			die();
		}
		
		function deactivate_sites($sites, $post_type = NULL) {
			if (!is_array($sites)) $sites = array($sites);
			if ($post_type === NULL) {
				foreach ($sites as $site) {
					$this->sites[$site]['active'] = false;
				}
			} else {
				if (post_type_exists($post_type)) {
					foreach ($sites as $site) {
						$key = array_search($site, $this->post_types[$post_type]['sites']);
						if ($key !== false)
							unset($this->post_types[$post_type]['sites'][$key]);
					}
				}
			}
			
			$this->update_options();
		}
		
		function activate_sites($sites, $post_type = NULL) {
			if (!is_array($sites)) $sites = array($sites);
			if ($post_type === NULL) {
				foreach ($sites as $site) {
					$this->sites[$site]['active'] =  true;
				}
			} else {
				if (post_type_exists($post_type)) {
					foreach ($sites as $site) {
						if (!is_array($this->post_types[$post_type]['sites'])) $this->post_types[$post_type]['sites'] = array();
						
						$key = array_search($site, $this->post_types[$post_type]['sites']);
						if (!$key)
							$this->post_types[$post_type]['sites'][] = $site;
					}
				}
			}
			
			$this->update_options();
		}
		
		function screen_options($current, $screen){
			global $current_user;
			$options = get_user_meta($current_user->ID, 'wp_social_link_opts', true);
			// Save Screen Options
			if (isset($_POST['save_screen_options'])) {
				$options['screen_opt-sites'] = $_POST['screen_opt'];
				update_usermeta($current_user->ID, 'wp_social_link_opts', $options);
			}
			$nonce = wp_create_nonce( plugin_basename(__FILE__) );
			$desired_screen = convert_to_screen('settings_page_wp-social-links');
			if ( $screen->id == $desired_screen->id && empty($_GET['a']) ){
				$current .= "<h5>".__('Show advanced site properties')."</h5>";
				$current .= '<form action="'.admin_url('options-general.php?page=wp-social-links').'" id="screen_opts" method="post"><div class="metabox-prefs">';
				$current .= '<input type="hidden" name="nonce" value="'.$nonce.'" />';
				$current .= '<input type="hidden" name="action" value="save_wp_social_links_screen_options" />';
				$current .= '<label for="class-hide">
				<input type="checkbox" class="hide-column-tog" name="screen_opt[class-hide]" id="class-hide" value="class"'.(is_array($options) && $options['screen_opt-sites']['class-hide'] ? ' checked="checked"' : '').' />'.__('CSS Class').'
				</label>';
				$current .= '<label for="href_pre-hide">
				<input type="checkbox" class="hide-column-tog" name="screen_opt[href_pre-hide]" id="href_pre-hide" value="href_pre"'.(is_array($options) && $options['screen_opt-sites']['href_pre-hide'] ? ' checked="checked"' : '').' />'.__('Before URL').'
				</label>';
				$current .= '<label for="href_post-hide">
				<input type="checkbox" class="hide-column-tog" name="screen_opt[href_post-hide]" id="href_post-hide" value="href_post"'.(is_array($options) && $options['screen_opt-sites']['href_post-hide'] ? ' checked="checked"' : '').' />'.__('After URL').'
				</label>';
				$current .= '<input type="submit" class="button hide-if-js" value="Apply" name="save_screen_options" /></div></form>';
			}
			return $current;
		}
		
		function sites_page($notices, $messages) {
			global $current_user;
			$nonce = wp_create_nonce( plugin_basename(__FILE__) );
			$message = false;
			$notice = false;
			
			// Delete Site(s)
			if ( $_REQUEST['action'] == 'delete' && isset($_REQUEST['sites']) ) {
				if (!is_array($_REQUEST['sites'])) $_REQUEST['sites'] = array($_REQUEST['sites']);
				foreach ($_REQUEST['sites'] as $delete) {
					unset($this->sites[$delete]);
				}
				if (count($_REQUEST['sites']) > 1) {
					$message = $messages[4];
				} else {
					$message = $messages[2];
				}
				$this->update_options();
			}
			
			// Deactivate Site(s)
			if ( $_REQUEST['action'] == 'deactivate' && isset($_REQUEST['sites']) ) {
				$this->deactivate_sites($_REQUEST['sites']);
				
				if (count($_REQUEST['sites']) > 1) {
					$message = $messages[5];
				} else {
					$message = $messages[6];
				}
			}
			
			// Activate Site(s)
			if ( $_REQUEST['action'] == 'activate' && isset($_REQUEST['sites']) ) {
				$this->activate_sites($_REQUEST['sites']);
				
				if (count($_REQUEST['sites']) > 1) {
					$message = $messages[7];
				} else {
					$message = $messages[8];
				}
			}
			
			// Save Site
			if ( isset($_POST['submit']) && !empty($_POST['name']) && ( !empty($_POST['slug']) || !empty($_REQUEST['edit']) ) ) {	
				// Edit the 'sites' array
				$post = array(
					'name' => $_POST['name'],
					'title' => $_POST['title'],
					'class' => $_POST['class'],
					'href_pre' => $_POST['href_pre'],
					'href_post' => $_POST['href_post']
				);
				$regex = "/^[a-zA-Z]+([-_][a-zA-Z]+)*$/i";
				if (!empty($_REQUEST['edit'])) {
					if (preg_match($regex, $_REQUEST['edit']))
						$this->sites[$_REQUEST['edit']] = array_merge($this->sites[$_REQUEST['edit']], $post);
					else // Doesn't match REGEX
						$notice = $notices[5];
				} else {
					$post['active'] = true;
					if (preg_match($regex, $_POST['slug'])) {
						if (is_array($this->sites[$_POST['slug']])) {
							// SLUG ALREADY IN USE
							$notice = $notices[1];
						} else {
							$this->sites[$_POST['slug']] = $post;
						}
					} else // Doesn't match REGEX
						$notice = $notices[5];
				}
				
				if (!$notice) {
					$this->update_options();
					if (!empty($_REQUEST['edit'])) {
						$message = $messages[3];
					} else {
						$message = $messages[1];
					}
				}
			} else if (isset($_POST['submit']) && (empty($_POST['title']) || empty($_POST['slug']))) {
				$notice = $notices[2];
			}
			
			if (!empty($_REQUEST['edit'])) {
				if (empty($_POST['name'])) {
					$_POST = $this->sites[$_GET['edit']];
				}
				$_POST['slug'] = $_REQUEST['edit'];
			}
			
			$num_sites = count($this->sites);
			$per_page = 10;
			$page = ($_GET['p'] > 1) ? $_GET['p'] : 1;
			$start = ($page - 1)* $per_page;
			$num_pages = ceil($num_sites / $per_page);
			
			$prev_page = ($page <= 1) ? 1 : $page - 1;
			$next_page = ($page >= $num_pages) ? $num_pages : $page + 1;
			
			$sites = array_slice($this->sites, $start, $start + $per_page);
			?>
            <style type="text/css">
			.hidden-field {
				display:none;
			}
			</style>
        <br />
        <?php if ( $notice ) : ?>
        <div id="notice" class="error"><p><?php echo $notice ?></p></div>
        <?php endif; ?>
        <?php if ( $message ) : ?>
        <div id="message" class="updated"><p><?php echo $message; ?></p></div>
        <?php endif; ?>
        <div id="col-container">
                	<div id="col-right">
                    	<div class="col-wrap">
                        <form action="<?=admin_url('options-general.php?page=wp-social-links')?>" method="post" id="action_form">
                        <input type="hidden" name="_noncename" id="_noncename" value="<?=$nonce ?>" />
                        <div class="tablenav top">
                            <div class="alignleft bulk_actions">
                                <select name="action" id="action">
                                    <option><?=__('Bulk Actions')?></option>
                                    <option value="activate"><?=__('Activate')?></option>
                                    <option value="deactivate"><?=__('Deactivate')?></option>
                                    <option value="delete"><?=__('Delete')?></option>
                                </select>
                                <input type="submit" class="button-secondary action" value="<?=__('Apply')?>" />
                            </div>
                            
                            <div class="tablenav-pages">
                                <span class="displaying-num"><?php printf(__('%s items'), $num_sites)?></span>
                                <span class="pagination-links">
                                	<a class="first-page <?=($page == 1) ? 'disabled' : ''?>" title="<?=__('Go to the first page')?>" href="<?=admin_url('options-general.php?page=wp-social-links&p=1')?>">«</a>
									<a class="prev-page <?=($page == 1) ? 'disabled' : ''?>" title="<?=__('Go to the previous page')?>" href="<?=admin_url('options-general.php?page=wp-social-links&p='.$prev_page)?>">‹</a>
									<span class="paging-input">
                                    	1 <?=__('of')?> <span class="total-pages"><?=$num_pages;?></span>
                                   	</span>
									<a class="next-page <?=($page == $num_pages) ? 'disabled' : ''?>" title="<?=__('Go to the next page')?>" href="<?=admin_url('options-general.php?page=wp-social-links&p='.$next_page)?>">›</a>
									<a class="last-page <?=($page == $num_pages) ? 'disabled' : ''?>" title="<?=__('Go to the last page')?>" href="<?=admin_url('options-general.php?page=wp-social-links&p='.$num_pages)?>">»</a></span>
                            </div>
                        </div>
                        
        <table class="wp-list-table widefat" cellspacing="0">
	<thead>
	<tr>
		<th scope="col" id="cb" class="check-column"><input type="checkbox"></th>
        <th scope="col" id="name"><?=__('Name')?></th>
        <th scope="col" id="slug"><?=__('Slug')?></th>
    </tr>
	</thead>
    
    <tfoot>
	<tr>
		<th scope="col" class="manage-column column-cb check-column"><input type="checkbox"></th>
        <th scope="col" class="manage-column column-name"><?=__('Name')?></th>
        <th scope="col" class="manage-column column-slug"><?=__('Slug')?></th>
    </tr>
	</tfoot>

	<tbody id="the-list" class="list:tag">
    <?php foreach ($sites as $slug => $site) { ?>
				<tr<?=(!$site['active'] ? ' style="background-color:#DFDFDF;"' : '')?> valign="top">
                    <th scope="row" class="check-column"><input type="checkbox" name="sites[]" value="<?=$slug?>"></th>
                    <td class="post-name site-name column-name"><strong><a class="row-name" href="<?=admin_url('options-general.php?page=wp-social-links&edit='.$slug)?>" title="Edit \"<?=$site['name']?>\""><?=stripslashes($site['name'])?></a></strong>
    <div class="row-actions">
        <span class="edit"><a href="<?=admin_url('options-general.php?page=wp-social-links&p='.$page.'&edit='.$slug)?>" title="Edit this item">Edit</a> | </span>
        <?php if ($site['active']): ?>
        <a href="<?=admin_url('options-general.php?page=wp-social-links&p='.$page.'&action=deactivate&sites[]='.$slug.'&_noncename='.$nonce) ?>" class="deactivate" title="Deactivate this item">Deactivate</a> |
        <?php else: ?>
        <a href="<?=admin_url('options-general.php?page=wp-social-links&p='.$page.'&action=activate&sites[]='.$slug.'&_noncename='.$nonce)?>" class="activate" title="Activate this item">Activate</a> |
        <?php endif;?> 
        <span class="trash"><a class="submitdelete" title="Delete this item" href="<?=admin_url('options-general.php?page=wp-social-links&p='.$page.'&action=delete&sites[]='.$slug.'&_noncename='.$nonce)?>">Delete</a></span>
    </div>
    </td>			
                    <td class="slug column-slug"><?=$slug?></td>
            </tr>
      <?php } ?>
		</tbody>
</table>
</form>
</div>
</div>
<?php $user_options = get_user_meta($current_user->ID, 'wp_social_link_opts', true); ?>
					<div id="col-left">
                    	<div class="col-wrap">
                        	<div class="form-wrap">
                            	<form action="<?=admin_url('options-general.php?page=wp-social-links'.(!empty($_REQUEST['edit']) ? '&edit='.$_REQUEST['edit'] :''))?>" method="post">
                                <input type="hidden" name="_noncename" id="_noncename" value="<?=$nonce ?>" />
                                <input type="hidden" name="edit" value="<?=$_REQUEST['edit']?>" />
                            	<h3><?=(empty($_REQUEST['edit']) ? __('Add New Site') : __('Edit Site'))?></h3>
                                <div class="form-field">
                                    <label for="name"><?=__('Name')?></label>
                                    <input type="text" name="name" size="20" value="<?=stripslashes($_POST['name'])?>" />
                                    <p><?php _e('The name is how it appears on your site.') ?></p>
                                </div>
                                <div class="form-field">
                                    <label for="slug"><?=__('Slug')?></label>
                                    <input type="text" name="slug" size="20" value="<?=stripslashes($_POST['slug'])?>" <?=(!empty($_REQUEST['edit']) ? ' disabled="disabled"' : '')?> />
                                    <p><?php _e('This is used to identify the site.') ?></p>
                                </div>
                                <div class="form-field">
                                    <label for="title"><?=__('Title')?></label>
                                    <input type="text" name="title" size="20" value="<?=stripslashes($_POST['title'])?>" />
                                    <p><?php _e('This will be used in the title attribute of the site link. Use %s to indicate the post title.') ?></p>
                                </div>
                                <div class="form-field field-class <?=(empty($user_options['screen_opt-sites']['class-hide']) ?  'hidden-field' : '')?>">
                                    <label for="class"><?=__('CSS Class')?></label>
                                    <input type="text" name="class" size="20" value="<?=stripslashes($_POST['class'])?>" />
                                    <p><?php _e('The CSS class of the post link, will replace the slug as the CSS class.') ?></p>
                                </div>
                                <div class="form-field field-href_pre <?=(empty($user_options['screen_opt-sites']['href_pre-hide']) ?  'hidden-field' : '')?>">
                                    <label for="href_pre"><?=__('Before URL')?></label>
                                    <input type="text" name="href_pre" size="20" value="<?=stripslashes($_POST['href_pre'])?>" />
                                    <p><?php _e('What appears ahead of the link attribute. (ex. mailto:)') ?></p>
                                </div>
                                <div class="form-field field-href_post <?=(empty($user_options['screen_opt-sites']['href_post-hide']) ?  'hidden-field' : '')?>">
                                    <label for="href_pre"><?=__('After URL')?></label>
                                    <input type="text" name="href_post" size="20" value="<?=stripslashes($_POST['href_post'])?>" />
                                    <p><?php _e('What appears after of the link attribute. (ex. ?subject=Subject)') ?></p>
                                </div>
                                <p class="submit">
                                	<input type="submit" name="submit" id="submit" class="button" value="<?=(empty($_REQUEST['edit']) ? __('Add New Site') : __('Save Site'))?>">
                                </p>
                                </form>
                            </div>
                        </div>
                    </div>
		</div>
        <script type="text/javascript">
		jQuery('.submitdelete').click(function() {
			return confirm('<?=__('Are you sure?')?>');
		});
		jQuery('#action_form').submit(function() {
			val = jQuery('#action option:selected').val();
			if (val == 'delete')
				return confirm('<?=__('Are you sure?')?>');
			return true;
		});
		jQuery('.advanced').click(function() {
			if (jQuery('.advanced_options').hasClass('hidden')) {
				jQuery('.advanced_options').removeClass('hidden');
			} else {
				jQuery('.advanced_options').addClass('hidden');
			}
			return false;
		});
		jQuery('.hide-column-tog').click(function(){
			id = jQuery(this).attr('id').substr(0, jQuery(this).attr('id').length - 5);
			checked = jQuery(this).attr('checked');
			if (checked) {
				jQuery('.field-'+id).removeClass('hidden-field');
				state = 'on';
			} else {
				jQuery('.field-'+id).addClass('hidden-field');
				state = 'off';
			}
			var data = {
				action: 'save_wp_social_links_screen_options',
				id: id,
				state: state,
				nonce: '<?=$nonce?>'
			};
			jQuery.post(ajaxurl, data);
		});
		</script>
		<?php }
		
		// User Profile Function
		
		function profile_fields($user) {
			if ($this->post_type_supports('users')) {
				echo '<h3>'.__('Social Links').'</h3>';
				$this->links_box('users', $user->ID);
			}
		}
		
		function save_profile_fields($user_id) {
			if ( !wp_verify_nonce( $_POST['wp-social-links_noncename'], plugin_basename(__FILE__) )) {
				return false;
			  }
			 
			if ( !current_user_can( 'edit_user', $user_id ) )
				return false;

			update_usermeta($user_id, 'wpsociallinks', $_POST['wp-social-links']);
		}
		
		
		// End Class
	}
}

if (class_exists("SocialLinksMB")) {
	$social_links = new SocialLinksMB();
	
	if (!function_exists('get_wp_social_links')) {
		function get_wp_social_links(&$args = array()) {
			global $post, $social_links;
			$args = array_merge($social_links->options, $args);
			$args = apply_filters('get_wp_social_links_args', $args);
			$sites = $social_links->sites;
			//is in loop??
			if (!empty($args['post_id']) && !empty($args['post_type'])) {
				// post_type is included in argument, special post type?
				$_post = new stdClass;
				$_post->post_type = $args['post_type'];
				$_post->ID = $args['post_id'];
			} else if (!empty($args['post_id'])) {
				// no, use argument
				$_post = get_post($args['post_id']);
			} else if (!empty($post->ID)) {
				// yes, use $post global
				$_post = $post;
				$args['post_id'] = $_post->ID;
			} else {
				// neither, throw error
				wp_die(__('Outside loop, no post_id specified'));
				exit;
			}
			
			if (!$social_links->post_type_supports($_post->post_type, 'wp-social-links')) {
				return ;			
			}
			
			$links = $social_links->get_type_meta($_post->ID, $_post->post_type, true);
			$links = maybe_unserialize($links);
			if (is_array($links)) {
				foreach ($links as $slug => $meta) {
					if (is_array($sites[$slug]) && !empty($sites[$slug])) {
						if ($social_links->post_type_site($_post->post_type, $slug))
							$links[$slug] = array_merge($meta, $sites[$slug]);
					}
				}
				ksort($links);
			}
			return apply_filters('get_wp_social_links', $links, $args);
		}
	}
	
	if (!function_exists('the_wp_social_links')) {
		function the_wp_social_links($args = array()) {
			global $post;
			$links = get_wp_social_links($args);
			
			if (is_array($links)) {
				if ($args['show_div']) $return = '<div class="'.$args['div_class'].'">';
				
				foreach ($links as $slug => $meta) {
					$meta = apply_filters('the_wp_social_links_meta', $meta, $args);
					$meta = apply_filters('the_wp_social_links_meta-'.$slug, $meta, $args);
					
					$return .= '<a href="';
					if ($meta['href_pre']) $return .= $meta['href_pre'];
					$return .= $meta['url'];
					if ($meta['href_post']) $return .= $meta['href_post'];
					$return .= '" id="social_link-'.$slug.'" class="social_link';
					if ($meta['class']) $return .= ' '.$meta['class'];
					else $return .= ' '.$slug;
					$return .= '" title="'.sprintf($meta['title'], get_the_title($post_id)).'">';
					if ($args['show_name']) $return .= $meta['name'];
					$return .= "</a>\n";
				}
				
				if ($args['show_div']) $return .= '</div>';
				echo apply_filters('the_wp_social_links', $return, $args);
			}
		}
	}
}
?>