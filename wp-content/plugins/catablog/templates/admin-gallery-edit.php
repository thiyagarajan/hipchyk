<div class="wrap">

		<div id="icon-catablog" class="icon32"><br /></div>
		<h2>
			<span><?php _e("Edit CataBlog Gallery", 'catablog'); ?></span>
		</h2>
		
		<?php $this->render_catablog_admin_message() ?>
		
		<form action="admin.php?page=catablog-gallery-save" method="post" id="catablog-edit" class="catablog-form clear_float">
			<fieldset>
				<h3><span>&nbsp;</span></h3>
				<div id="catablog-gallery-items">
					<label for="catablog-gallery-form-title"><?php _e("Title", "catablog"); ?></label><br />
					<input type="text" id="catablog-gallery-form-title" name="title" value="<?php echo $gallery->getTitle(); ?>" />
					
					<label for="catablog-gallery-form-description"><?php _e("Description", "catablog"); ?></label><br />
					<textarea id="catablog-gallery-form-description" name="description"><?php echo $gallery->getDescription(); ?></textarea>
					
					
					
					<ul>
					<?php foreach($gallery->getItemIds() as $item_id): ?>
						<?php if (in_array($item_id, array_keys($gallery_items))): ?>
							<li class="catablog-gallery-item">
								<img src="<?php echo $this->urls['thumbnails']; ?>/<?php echo $gallery_items[$item_id]->getImage(); ?>" />
								<small><?php echo $gallery_items[$item_id]->getTitle(); ?></small>
								<input type="hidden" name="item_ids[]" value="<?php echo $gallery_items[$item_id]->getId(); ?>" />
								<a href="#delete" class="catablog-delete-gallery-item" title="<?php _e("Remove Catalog Item from Gallery", "catablog"); ?>">x</a>
							</li>
						<?php endif ?>
					<?php endforeach ?>
					</ul>
					
					<hr />
					
					<?php wp_nonce_field( 'catablog_save_gallery', '_catablog_save_gallery_nonce', false, true ) ?>
					<input type="hidden" name="id" value="<?php echo $gallery->getId(); ?>" />
					<input type="submit" id="catablog-gallery-save" name="save" class="button-primary" value="<?php _e('Save Changes', 'catablog') ?>" />
					<span><?php printf(__("or %sback to galleries%s", 'catablog'), '<a href="admin.php?page=catablog-gallery">', '</a>'); ?></span>
				</div>
			</fieldset>
		</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		// initialize the sortables
		var catablog_items_path = "#catablog-gallery-items > ul";
		$(catablog_items_path).sortable({
			forcePlaceholderSize: true,
			opacity: 0.7
		});
		
		// initialize the remove button
		$('.catablog-delete-gallery-item').click(function(event) {
			$(this).closest('li').animate({opacity:0, width:0, height:0, margin:0}, 300, function() {
				$(this).remove();
			});
		});
		
	});
</script>
