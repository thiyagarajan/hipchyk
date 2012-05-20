<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e("CataBlog Rescan Original Images Results", "catablog"); ?></h2>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<?php if (count($new_rows['images']) < 1): ?>
		<ul id="catablog-console">
			<li><?php _e("No new images where found in your originals folders.", 'catablog'); ?></li>
			<li><?php _e("Please make sure that you have successfully uploaded new images via FTP before running this command.", "catablog"); ?></li>
			<li><?php _e("New images should be uploaded into the following folder:", "catablog"); ?></li>
			<li><code><?php echo $this->directories['originals'] ?></code></li>
		</ul>
	<?php else: ?>
		<noscript>
			<div class="error">
				<strong><?php _e("You must have a JavaScript enabled browser to regenerate your images.", "catablog"); ?></strong>
			</div>
		</noscript>
		
		<div id="catablog-progress-thumbnail" class="catablog-progress">
			<div class="catablog-progress-bar"></div>
			<h3 class="catablog-progress-text"><?php _e("Processing Thumbnail Images...", "catablog"); ?></h3>
		</div>

		<?php if ($this->options['lightbox-render']): ?>
			<div id="catablog-progress-fullsize" class="catablog-progress">
				<div class="catablog-progress-bar"></div>
				<h3 class="catablog-progress-text"><?php _e("Waiting For Thumbnail Rendering To Finish...", "catablog"); ?></h3>
			</div>
		<?php endif ?>
		
		<ul id="catablog-console">
			<?php foreach ($new_rows['titles'] as $title): ?>
				<li class="message"><?php printf(__("New Image Found, creating catalog item %s", "catablog"), "<strong>$title</strong>"); ?></li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		<?php if (count($new_rows['images']) < 1): ?>
			return false;
		<?php endif ?>
		
		discourage_leaving_page('<?php _e("Please allow the rendering to complete before leaving this page. Click cancel to go back and let the rendering complete.", "catablog"); ?>');
		
		/****************************************
		** CALCULATE NEW IMAGES
		****************************************/
		var nonce   = '<?php echo wp_create_nonce("catablog-render-images") ?>';		
		var images  = ["<?php echo implode('", "', $new_rows['images']) ?>"];
		
		var thumbs = images.slice(0);
		renderCataBlogItems(thumbs, 'thumbnail', nonce, function() {
			
			<?php if ($this->options['lightbox-render']): ?>
				
				var fullsize = images.slice(0);
				renderCataBlogItems(fullsize, 'fullsize', nonce, function() {

					unbind_discourage_leaving_page();
					var t = setTimeout(function() {
						jQuery('#catablog-progress-thumbnail').hide('medium');
						jQuery('#catablog-progress-fullsize').hide('medium');
						jQuery('#message').hide('medium');
					}, 2000);
					$('#save_changes').attr('disabled', false);
					
				});
				
			<?php else: ?>
				
				unbind_discourage_leaving_page();
				var t = setTimeout(function() {
					jQuery('#catablog-progress-thumbnail').hide('medium');
					jQuery('#message').hide('medium');
				}, 2000);
				$('#save_changes').attr('disabled', false);
				
			<?php endif ?>
		});
	});
</script>