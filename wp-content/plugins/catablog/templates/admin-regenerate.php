<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e("CataBlog Images Rendering", "catablog"); ?></h2>
	
	<noscript>
		<div class="error">
			<strong><?php _e("You must have a JavaScript enabled browser to regenerate your images.", "catablog"); ?></strong>
		</div>
	</noscript>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<?php if (count($image_names) < 1): ?>
		<p><?php _e("Your CataBlog library is empty, there are no images to render.", "catablog") ?></p>
		</div><!-- END div.wrap -->
		<?php return false; ?>
	<?php endif ?>
	
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
		<li><?php _e('Rendering all images from their original files, this may take awhile so please be patient.', 'catablog') ?></li>
	</ul>
	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		var images  = ["<?php echo implode('", "', $image_names) ?>"];
		var nonce   = '<?php echo wp_create_nonce("catablog-render-images") ?>';
		var message = '<?php _e("Image rendering is now complete, you may now go to any other admin panel you may want.", "catablog"); ?>';
		
		discourage_leaving_page('<?php _e("Please allow the rendering to complete before leaving this page. Click cancel to go back and let the rendering complete.", "catablog"); ?>');
		
		renderCataBlogItems(images, 'thumbnail', nonce, 'img-name', function() {
			
			<?php if ($this->options['lightbox-render']): ?>
				var images = ["<?php echo implode('", "', $image_names) ?>"];
				renderCataBlogItems(images, 'fullsize', nonce, 'img-name', function() {
					jQuery('#catablog-console').append('<li class="updated">'+message+'</li>');
					unbind_discourage_leaving_page();
				});
			<?php else: ?>	
				jQuery('#catablog-console').append('<li class="updated">'+message+'</li>');
				unbind_discourage_leaving_page();
			<?php endif ?>
		});
		
	});
</script>