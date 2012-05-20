<?php
	$page = 'gallery';
	$fields = array('description', 'size', 'shortcode', 'date');
	$user = wp_get_current_user();
	$user_settings = get_user_meta($user->ID, $this->custom_user_meta_name, true);
	
	if ($user_settings === "") {
		$user_settings = $this->getDefaultUserSettings();
		update_user_meta($user->ID, $this->custom_user_meta_name, $user_settings);
	}
	
	$screen_settings = $user_settings[$page];
	
	// processes screen options form if submitted
	if (isset($_POST['screen-options-apply'])) {
		$nonce_verified = wp_verify_nonce( $_REQUEST['_catablog_screen_settings_nonce'], 'catablog_screen_settings' );
		if (isset($_POST['entry-per-page']) && $nonce_verified) {
		
			$screen_settings['hide-columns'] = array();
			foreach ($fields as $field) {
				if (!in_array($field, $_REQUEST['hide'])) {
					$screen_settings['hide-columns'][] = $field;
				}
			}
			
			if (is_numeric($_REQUEST['entry-per-page'])) {
				if ($_REQUEST['entry-per-page'] > 1) {
					$screen_settings['limit'] = $_REQUEST['entry-per-page'];
				}
			}
		
			$user_settings[$page] = $screen_settings;
			update_user_meta($user->ID, $this->custom_user_meta_name, $user_settings);
		}
	}
	
?>

<?php $screen_settings['hide-columns'] = (is_array($screen_settings['hide-columns']))? $screen_settings['hide-columns'] : array() ?>

<h5><?php _e('Show on screen', 'catablog') ?></h5>
<div class="metabox-prefs">
	<?php foreach ($fields as $field): ?>
		<?php $checked = (!in_array($field, $screen_settings['hide-columns']))? ' checked="checked"' : '' ?>
		<label for="hide-column-<?php echo $field ?>"><input class="hide-catablog-column-tog" type="checkbox" name="hide[]" value="<?php echo $field ?>" id="hide-column-<?php echo $field ?>"<?php echo $checked ?>><?php _e(ucwords(str_replace('_', ' ', $field)), 'catablog') ?></label>
	<?php endforeach ?>
	<br class="clear">
</div>

<h5><?php _e('Show on screen', 'catablog') ?></h5>
<div class='screen-options'>
	<input type='text' class='screen-per-page' name='entry-per-page' id='entry_per_page' maxlength='3' value='<?php echo $screen_settings['limit'] ?>' />
	<label for='entry_per_page'><?php _e('Catalog items', 'catablog') ?></label>
	<input type="hidden" name="settings-page" value="<?php echo $page; ?>" />
	<?php wp_nonce_field( 'catablog_screen_settings', '_catablog_screen_settings_nonce', false, true ) ?>
	<input type="submit" name="screen-options-apply" id="screen-options-apply" class="button" value="<?php _e('Apply', 'catablog') ?>"  />
</div>

