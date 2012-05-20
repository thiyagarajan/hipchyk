<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e("Edit CataBlog Entry", 'catablog'); ?></h2>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<form id="catablog-edit" class="catablog-form clear_float" method="post" action="admin.php?page=catablog-save" enctype="multipart/form-data">
		
		<div id="catablog-edit-main">
			<div id="catablog-edit-main-content">
			<fieldset>
				<h3><span><?php _e("Main", "catablog"); ?></span></h3>
				
				<div>
					<div id="catablog-edit-main-image">
						<label id="catablog-edit-images-label"><?php _e("Images", 'catablog'); ?></label>
						<div id="catablog-edit-images-column">
							
							<img src="<?php echo $this->urls['thumbnails'] . "/" . $result->getImage() ?>" class="catablog-image-preview" />
							<input type="hidden" name="image" id="image" value="<?php echo $result->getImage() ?>" />
							
							<hr />
							
							<span id="catablog-edit-image-controls" class="hide-if-no-js">
								<a href="#replace-main-image" id="show-image-window"><small style="font-size:10px;"><?php _e("Replace Main Image", 'catablog'); ?></small></a>
								<br />
								<a href="#add-subimage" id="show-subimage-window"><small style="font-size:10px;">[+] <?php _e("Add Sub Image", 'catablog'); ?></small></a>	
							</span>
							
							<noscript><div class="error" style="border-width:1px;">
								<strong><small><?php _e("JavaScript is required to add images.", 'catablog'); ?></small></strong>
							</div></noscript>
							
							<hr />
							
							<?php if (count($result->getSubImages()) > 0): ?>
								<ul id="catablog-sub-images">
									<?php foreach ($result->getSubImages() as $sub_image): ?>
										<li>
											<img src="<?php echo $this->urls['thumbnails'] . "/$sub_image" ?>" class="catablog-image-preview" />
											<input type="hidden" name="sub_images[]" class="sub-image" value="<?php echo $sub_image ?>" />
											<a href="#delete" class="catablog-delete-subimage" title="<?php _e('Delete this sub image permanently.', 'catablog'); ?>">X</a>
										</li>
									<?php endforeach ?>
								</ul>
							<?php else: ?>
								<p><small class="nonessential"><?php _e("No Sub Images", 'catablog'); ?></small></p>
							<?php endif ?>
							
							
						</div>
					</div><!-- END div#catablog-edit-main-image -->
					
					
					<div id="catablog-edit-main-text">
						<label for="catablog-title"><?php _e("Title", 'catablog'); ?></label>
						<input type="text" name="title" id="catablog-title" maxlength="200" class="catablog-entry-title" value="<?php echo htmlspecialchars($result->getTitle(), ENT_QUOTES, 'UTF-8') ?>" />

						<?php if ($this->options['public_posts']): ?>
							<p><small>Permalink: <a href="<?php echo $result->getPermalink() ?>"><?php echo $result->getPermalink() ?></a></small></p>
						<?php else: ?>
							<p>&nbsp;</p>
						<?php endif ?>

						<?php wp_editor($result->getDescription(), 'description', array('media_buttons'=>false, 'teeny'=>false)); ?>
					</div>
					
					<div id="catablog-edit-main-save" class="clear_float">
						<input type="hidden" id="save" name="save" value="yes" />
						<?php wp_nonce_field( 'catablog_save', '_catablog_save_nonce', false, true ) ?>
						
						<input type="hidden" id="saved_image" name="saved_image" value="<?php echo $result->getImage() ?>" />
						<input type="hidden" id="id" name="id" value="<?php echo $result->getId() ?>" />
						<input type="hidden" id="order" name="order" value="<?php echo $result->getOrder() ?>" />
						
						<input type="submit" class="button-primary" id="save_changes" value="<?php _e("Save Changes", 'catablog'); ?>" />
						<span><?php printf(__("or %sback to list%s", 'catablog'), '<a href="admin.php?page=catablog">', '</a>'); ?></span>
					</div>
					
				</div>
		
			</fieldset>
			</div>
		</div>
		
		
		
		
		<div id="catablog-edit-params">
			<fieldset>
				<h3><label for="date"><?php _e("Attributes", 'catablog'); ?></label></h3>
				<div>
					<p>
						<span><?php printf(__("Date: %s", 'catablog'), date('M. jS, Y - H:i', strtotime($result->getDate()))) ?></span>
						<a href="#edit-date" id="activate-date-editor" class="alignright"><?php _e("edit", "catablog") ?></a>
					</p>
					<p id="date-editor" class="hide">
						<input type="text" name="date" id="date" class="text-field" value="<?php echo htmlspecialchars($result->getDate(), ENT_QUOTES, 'UTF-8') ?>" />
						<small>
							<?php _e("Format: YYYY-MM-DD HH:MM:SS", 'catablog'); ?>
						</small>
					</p>
					
					<p>
						<label for="order"><?php printf(__("Order: %s", 'catablog'), $result->getOrder()); ?></label>
						<a href="#edit-order" id="activate-order-editor" class="alignright"><?php _e("edit", "catablog") ?></a>
					</p>
					<p id="order-editor" class="hide">
						<input type="text" name="order" id="order" class="text-field" value="<?php echo htmlspecialchars($result->getOrder(), ENT_QUOTES, 'UTF-8') ?>" />
						<small>
							<?php _e("Enter a integer value to be assigned as this catalog item's order. There is only one order value per catalog item.", "catablog") ?>
						</small>
					</p>
				</div>
			</fieldset>
			
			<fieldset>
				<h3><?php _e('Fields', 'catablog'); ?></h3>
				<div id="catablog-fields">
					<p>
						<label for="link"><?php _e("Link", 'catablog'); ?></label>
						<a href="#help" class="show-help alignright"><?php _e("help", "catablog"); ?></a>
						<br />
						<input type="text" name="link" id="link" class="text-field" value="<?php echo htmlspecialchars($result->getLink(), ENT_QUOTES, 'UTF-8') ?>" />
						<small>
							<?php _e("Enter a relative or absolute web address to make this item a hyperlink. External links should start with http://.", 'catablog'); ?>
						</small>
					</p>
					<p>
						<label for="price"><?php _e("Item Price", 'catablog'); ?></label>
						<a href="#help" class="show-help alignright"><?php _e("help", "catablog"); ?></a>
						<br />
						<input type="text" name="price" id="price" class="text-field" value="<?php echo $result->getPrice() ?>">
						<small>
							<?php _e("If your catalog item has a price above zero, it will generate a buy now button you may display.", 'catablog'); ?>
						</small>
					</p>
					
					<p>
						<label for="product_code"><?php _e("Product Code", 'catablog'); ?></label>
						<a href="#help" class="show-help alignright"><?php _e("help", "catablog"); ?></a>
						<br />
						<input type="text" name="product_code" id="product_code" class="text-field" value="<?php echo htmlspecialchars($result->getProductCode(), ENT_QUOTES, 'UTF-8') ?>">
						<small>
							<?php _e("This field is intended for usage as a product code for the PayPal buy now buttons.", 'catablog'); ?>
						</small>
					</p>

					<p><small class="hide">
						<?php printf(__("If you want to make a shopping cart you should make sure you are using a %sStore Template%s that uses these values.", 'catablog'), '<a href="admin.php?page=catablog-options#store">', '</a>'); ?>
					</small></p>
				</div>
			</fieldset>
			
			<fieldset>
				<h3><?php _e("Categories", 'catablog'); ?></h3>
				<div id="catablog-category" class="tabs-panel">
					
					<ul id="catablog-category-checklist" class="list:category categorychecklist form-no-clear">
						
						<?php $categories = $this->get_terms() ?>
						
						<?php if (count($categories) < 1): ?>
							<li><span><?php _e("You currently have no categories.", 'catablog'); ?></span></li>
						<?php endif ?>
						
						<?php foreach ($categories as $category): ?>
						<li id="catablog-category-<?php echo $category->term_id; ?>">
							<label class="catablog-category-row">
								<?php $checked = (in_array($category->term_id, array_keys($result->getCategories())))? 'checked="checked"' : '' ?>
								<input id="in-category-<?php echo $category->term_id ?>" class="term-id" type="checkbox" <?php echo $checked ?> name="categories[]"  value="<?php echo $category->term_id ?>" />
								<input class="term-slug" type="hidden" value="<?php echo $category->slug; ?>" />
								<?php $default_term = $this->get_default_term() ?>
								<?php if ($category->name != $default_term->name): ?>
									<a href="#edit-category-<?php echo $category->term_id; ?>" class="catablog-category-edit hide"><small><?php _e("EDIT", 'catablog'); ?></small></a>
								<?php endif ?>
								<span class="term-name"><?php echo $category->name ?></span>

							</label>
						</li>
						<?php endforeach ?>
					</ul>
					
					<div id="catablog-new-category">
						<noscript>
							<div class="error">
								<strong><small><?php _e("You must have a JavaScript enabled browser to create new categories.", 'catablog'); ?></small></strong>
							</div>
						</noscript>
						
						<span class="hide">
							<input id="catablog-new-category-input" type="text" value="" />
							<a href="#new-category" id="catablog-new-category-submit" class="button"><?php _e('New', 'catablog'); ?></a>
							<img src="<?php echo $this->urls['images'] ?>/ajax-loader-small.gif" id="catablog-new-category-load" class="hide" />
						</span>
					</div>
					<p><small>
						<?php _e("Put your items into categories to easily display subsets of your catalog on different pages.", 'catablog'); ?><br />
						<strong><?php _e('ex:', 'catablog'); ?></strong> [catablog category="dogs,cats" ]
					</small></p>
				</div>
			</fieldset>
			
		</div>
		
		
	</form>
	
</div><!-- END #wrap -->


<div id='catablog_load_curtain'>&nbsp;</div>

<div id="add-subimage-window" class="catablog-modal">
	<form id="catablog-add-subimage" class="catablog-form" method="post" action="admin.php?page=catablog-add-subimage" enctype="multipart/form-data">
		<h3 class="catablog-modal-title">
			<span style="float:right;"><a href="#" class="hide-modal-window"><?php _e("[close]", 'catablog'); ?></a></span>
			<strong><?php _e("Upload A New Sub Image", 'catablog'); ?></strong>
		</h3>
		<div class="catablog-modal-body">
			<p><strong><?php _e("Save Other Changes Before Uploading A New Image.", 'catablog'); ?></strong></p>
			
			<input type="file" id="new_sub_image" name="new_sub_image"  />
			<span class="nonessential"> | </span>
			<input type="hidden" name="id" value="<?php echo $result->getId() ?>" >

			<?php wp_nonce_field( 'catablog_add_subimage', '_catablog_add_subimage_nonce', false, true ) ?>
			<input type="submit" name="save" value="<?php _e("Upload", 'catablog'); ?>" class="button-primary" />
			<p><small>
				<?php _e("Select an image to add as a sub image, this upload will not replace this item's main image.", 'catablog'); ?><br />
				<?php _e("You may upload JPEG, GIF and PNG graphic formats only.", "catablog"); ?><br />
				<strong><?php _e("No animated GIFs please.", "catablog"); ?></strong>
			</small></p>			
		</div>
	</form>
</div>

<div id="replace-image-window" class="catablog-modal">
	<form id="catablog-replace-image" class="catablog-form" method="post" action="admin.php?page=catablog-replace-image" enctype="multipart/form-data">
		<h3 class="catablog-modal-title">
			<span style="float:right;"><a href="#" class="hide-modal-window"><?php _e("[close]", 'catablog'); ?></a></span>
			<strong><?php _e("Replace The Main Image", 'catablog'); ?></strong>
		</h3>
		<div class="catablog-modal-body">
			<p><strong><?php _e("Save Other Changes Before Uploading A New Image.", 'catablog'); ?></strong></p>
			<input type="file" id="new_image" name="new_image"  />
			<span class="nonessential"> | </span>
			<input type="hidden" name="id" value="<?php echo $result->getId() ?>" >

			<?php wp_nonce_field( 'catablog_replace_image', '_catablog_replace_image_nonce', false, true ) ?>
			<input type="submit" name="save" value="<?php _e("Upload", 'catablog'); ?>" class="button-primary" />
			
			<p><small>
				<?php _e("Select an image on your computer to upload and replace this item's main image with.", 'catablog'); ?><br />
				<?php _e("You may upload JPEG, GIF and PNG graphic formats only.", "catablog"); ?><br />
				<strong><?php _e("No animated GIFs please.", "catablog"); ?></strong><br /><br >
				<strong><?php _e("This will replace the main image, forever deleting the current main image.", 'catablog'); ?></strong>
				
			</small></p>			
		</div>
	</form>
</div>

<div id="edit-category-window" class="catablog-modal">
	<form id="catablog-edit-category" class="catablog-form" method="post" action="admin.php?page=catablog-edit-category">
		<h3 class="catablog-modal-title">
			<span style="float:right;"><a href="#" class="hide-modal-window"><?php _e("[close]", 'catablog'); ?></a></span>
			<strong><?php _e("Edit Category", 'catablog'); ?></strong>
		</h3>
		<div class="catablog-modal-body">
			<p><strong><?php _e("Save Other Changes Before Editing a Category.", 'catablog'); ?></strong></p>
			
			<p>
				<label for="category_name"><?php _e("Category Name:", "catablog"); ?></label><br />
				<input type="text" id="category_name" name="new_category_name" value="" /><br />
				<small class="hidden"><?php _e("Commas, Pipes and reserved HTML characters are not allowed in category names.", "catablog"); ?></small>
			</p>
			
			<p>
				<label for="category_slug"><?php _e("Category Slug:", "catablog"); ?></label><br />
				<input type="text" id="category_slug" name="new_category_slug" value="" /><br />
				<small class="hidden"><?php _e("The category slug may only contain alphanumeric characters and hyphens.", "catablog"); ?></small>
			</p>
			
			<?php wp_nonce_field( 'catablog_edit_category', '_catablog_edit_category_nonce', false, true ) ?>
			<input type="hidden" name="term_id" id="term_id" value="" />
			<input type="hidden" name="redirect_id" value="<?php echo $result->getId(); ?>" />
			<input type="submit" name="save" value="<?php _e("Save Category Changes", 'catablog'); ?>" class="button-primary" />
			<span><?php printf(__("or %sDelete Category%s", 'catablog'), '<a href="#delete-category" class="catablog-delete-action">', '</a>'); ?></span>
			<p><small>
				<?php _e("You may change the category name or slug here, you may also delete the category here.", 'catablog'); ?><br />
				<?php _e("the slug is used in the public feature to build an archive page's url, so it should be url friendly.", "catablog"); ?><br />
			</small></p>
		</div>
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		// FOCUS ON THE TITLE INPUT BOX
		$('#catablog-title').focus();
		
		// SHOW THE NEW CATEGORY FORM IF JAVASCRIPT IS ENABLED
		$('#catablog-new-category span').show();
		
		// BIND SORTABLE IMAGES
		$('#catablog-sub-images').sortable({
			cursor: 'crosshair',
			forcePlaceholderSize: true,
			opacity: 0.7,
			revert: 200
		})
		
		// BIND DATE EDITOR
		$('#activate-date-editor').click(function() {
			$('#date-editor').slideDown();
			$(this).fadeOut();
		});
		
		// BIND ORDER EDITOR
		$('#activate-order-editor').click(function() {
			$('#order-editor').slideDown();
			$(this).fadeOut();
		});
		
		// 
		$('#catablog-fields small').css('display','block').hide();
		$('#catablog-fields .show-help').click(function() {
			$(this).siblings('small').slideDown();
			$(this).fadeOut();
		});
		
		
		
		// BIND CATEGORY LIST HOVERS
		$('#catablog-category-checklist li label').live('mouseover', function(event) {
			$(this).addClass('hover');
			if (!catablog_category_is_loading()) {
				$('a.catablog-category-edit', this).show();
			}
		});
		$('#catablog-category-checklist li label').live('mouseout', function(event) {
			$(this).removeClass('hover');
			$('a.catablog-category-edit', this).hide();
		});
		
		
		
		// BIND EDIT CATEGORY LINKS
		$('#catablog-category-checklist li label a.catablog-category-edit').live('click', function(event) {

			// stop javascript event propagation and set this variable
			event.stopPropagation();
			
			// make sure category changes aren't still loading
			if (catablog_category_is_loading()) {
				return false;
			}
			
			var term_id       = $(this).siblings('.term-id').val();
			var term_slug     = $(this).siblings('.term-slug').val();
			var category_name = $(this).siblings('.term-name').html();
			var editor        = $('#edit-category-window');
			
			$('#term_id').val(term_id);
			
			var submit_button = editor.find('input[type="submit"]');
			
			editor.find('#category_name').val(category_name).bind('keyup', function() {
				if (null == this.value.match(/^[^\,\<\>\'\"\&\|]+$/i)) {
					if (false == $(this).parent().hasClass('error')) {
						$(this).parent().addClass('error');
						$(this).siblings('small').removeClass('hidden');
						submit_button.attr('disabled', true).attr('class', 'button');
					}
				}
				else {
					if (true == $(this).parent().hasClass('error')) {
						$(this).parent().removeClass('error');
						$(this).siblings('small').addClass('hidden');
						submit_button.attr('disabled', false).attr('class', 'button-primary');
					}
				}
			});
			
			editor.find('#category_slug').val(term_slug).bind('keyup', function() {
				if (null == this.value.match(/^[a-z0-9\-]+$/i)) {
					if (false == $(this).parent().hasClass('error')) {
						$(this).parent().addClass('error');
						$(this).siblings('small').removeClass('hidden');
						submit_button.attr('disabled', true).attr('class', 'button');
					}
				}
				else {
					if (true == $(this).parent().hasClass('error')) {
						$(this).parent().removeClass('error');
						$(this).siblings('small').addClass('hidden');
						submit_button.attr('disabled', false).attr('class', 'button-primary');
					}
				}
			});
			
			
			// show the edit category window
			editor.show();
			jQuery('#catablog_load_curtain').fadeTo(200, 0.8);
			
			
			
			return false;
		});
		
		
		
		
		// BIND NEW CATEGORY TEXT INPUT BOX
		$('#catablog-new-category-input').bind('keypress', function(event) {
			var key_code = (event.keyCode ? event.keyCode : event.which);
			if (key_code == 13) {
				$('#catablog-new-category-submit').click();
				return false;
			}
		});
		
		
		
		// BIND SAVE CATEGORY FORM
		$("#catablog-edit-category").bind('submit', function() {
			var form = this;
			$(form).find('input[type="submit"]').attr('disabled', true).attr('class', 'button');
			
			catablog_category_show_load();
			
			var term_id   = $('#term_id').val();
			var term_name = $('#category_name').val();
			var term_slug = $('#category_slug').val();
			
			var params  = {
				'action':   'catablog_edit_category',
				'security': '<?php echo wp_create_nonce("catablog-edit-category") ?>',
				'term_id':  term_id,
				'new_category_name': term_name,
				'new_category_slug': term_slug
			}

			// make AJAX call
			$.post(ajaxurl, params, function(data) {
				try {
					var json = $.parseJSON(data);
					if (json.success == false) {
						alert(json.error);
					}
					else {
						jQuery('.catablog-modal:visible').hide();
						jQuery('#catablog_load_curtain').fadeOut(200);
						
						$(form).find('input[type="submit"]').attr('disabled', false).attr('class', 'button-primary');
						
						var edited_category = '#catablog-category-'+term_id;
						$(edited_category).find('.term-name').html(json.name);
						$(edited_category).find('.term-slug').val(json.slug);
						
						$(edited_category).addClass('highlight');
						
						catablog_category_hide_load();
						
						var pause = setTimeout(function() {
							$(edited_category).switchClass( "highlight", "", 1000);
						}, 2000);
						
					}
				}
				catch(error) {
					alert(error);
				}

				// hide load indicator and enable new category button
				catablog_category_hide_load();
			});
			
			
			return false;
		});
		
		
		
		// BIND DELETE CATEGORY
		$('.catablog-delete-action').bind('click', function(event) {
			
			// confirm the deletion of the category
			if (!confirm('<?php _e("Are you sure you want to delete this category? You can not undo this.", "catablog") ?>')) {
				return false;
			}
			
			// show the load indicator and disable new category button
			catablog_category_show_load();

			// setup AJAX params
			var term_id = $('#term_id').val();
			var params  = {
				'action':   'catablog_delete_category',
				'security': '<?php echo wp_create_nonce("catablog-delete-category") ?>',
				'term_id':  term_id
			}

			// make AJAX call
			$.post(ajaxurl, params, function(data) {
				try {
					var json = $.parseJSON(data);
					if (json.success == false) {
						alert(json.error);
					}
					else {
						jQuery('.catablog-modal:visible').hide();
						jQuery('#catablog_load_curtain').fadeOut(200);
						
						var deleted_category = '#catablog-category-'+term_id;
						$(deleted_category).animate({'opacity':0, 'height':0, 'padding':0, 'margin':0}, 800, function() {
							$(deleted_category).remove();
						});
					}
				}
				catch(error) {
					alert(error);
				}

				// hide load indicator and enable new category button
				catablog_category_hide_load();
			});
			
			return false;
			
		});
		
		
		
		// BIND NEW CATEGORY FORM
		$('#catablog-new-category-submit').bind('click', function(event) {
			// if button disabled don't do anything
			if (catablog_category_is_loading()) {
				return false;
			}
			
			// check if category name is set
			var category_name = $('#catablog-new-category-input').val();
			if (category_name == '') {
				alert('<?php _e("Please make sure to enter a category name", "catablog") ?>');
				return false;
			}
			
			// show load indicators and disable button
			catablog_category_show_load();
			
			// set AJAX params
			var params = {
				'action':   'catablog_new_category',
				'security': '<?php echo wp_create_nonce("catablog-new-category") ?>',
				'name':     category_name
			}
			
			// make AJAX call
			$.post(ajaxurl, params, function(data) {
				try {
					var json = $.parseJSON(data);
					if (json.success == false) {
						alert(json.error);
					}
					else {
						var html = '<li id="catablog-category-'+json.id+'" class="highlight"><label class="catablog-category-row">';
						html    += ' <input id="in-category-'+json.id+'" class="term-id" type="checkbox" name="categories[]" value="'+json.id+'" /> ';
						html    += ' <input class="term-slug" type="hidden" value="'+json.slug+'" />'
						html    += ' <a href="#edit-category-'+json.id+'" class="catablog-category-edit hide"><small><?php _e("EDIT", "catablog"); ?></small></a>';
						html    += ' <span class="term-name">'+json.name+'</span> ';
						html    += '</label></li>';
						
						$('#catablog-category-checklist').append(html);
						
						var pause = setTimeout(function() {
							$("#catablog-category-"+json.id+"").switchClass( "highlight", "", 1000);
						}, 2000);
						
						$('#catablog-new-category-input').val('');
					}
				}
				catch(error) {
					alert(error);
				}
				
				// hide load indicators and enable button
				catablog_category_hide_load();
			});
			
			return false;
		});
		
		// BIND REPLACE MAIN IMAGE MODAL WINDOW
		$('#show-image-window').bind('click', function(event) {
			jQuery('#replace-image-window').show();
			jQuery('#catablog_load_curtain').fadeTo(200, 0.8);
			return false;
		});
		
		// BIND ADD SUB IMAGE MODAL WINDOW
		$('#show-subimage-window').bind('click', function(event) {
			jQuery('#add-subimage-window').show();
			jQuery('#catablog_load_curtain').fadeTo(200, 0.8);
			return false;
		});
		
		// BIND HIDE MODAL WINDOW
		$('.hide-modal-window').bind('click', function(event) {
			jQuery('.catablog-modal:visible').hide();
			jQuery('#catablog_load_curtain').fadeOut(200);
			return false;
		});
		
		// BIND DELETE SUB IMAGE
		$('#catablog-sub-images .catablog-delete-subimage').bind('click', function(event) {
			if (!confirm('<?php _e("Are you sure you want to permanently delete this image?", "catablog") ?>')) {
				return false;
			}
			
			var self  = this;
			var id    = $('#id').val();
			var image = $(this).siblings('input').val();
			
			var params = {
				'action':   'catablog_delete_subimage',
				'security': '<?php echo wp_create_nonce("catablog-delete-subimage") ?>',
				'id':       id,
				'image':    image
			}
			
			disable_save_button();
			
			// make AJAX call
			$.post(ajaxurl, params, function(data) {
				try {
					var json = eval(data);
					if (json.success == false) {
						alert(json.error);
					}
					else {
						$(self).parent().animate({opacity:0, height:0, margin:0}, 800, function() {
							$(this).remove();
							enable_save_button();
						});
					}
				}
				catch(error) {
					alert(error);
				}
				
			});
		});
		
		
		
		function catablog_category_show_load() {
			$('#catablog-new-category-load').show();
			$('#catablog-new-category-submit').addClass('disabled');
		}
		
		function catablog_category_hide_load() {
			$('#catablog-new-category-load').hide();
			$('#catablog-new-category-submit').removeClass('disabled');
		}
		
		function catablog_category_is_loading() {
			return $('#catablog-new-category-submit').hasClass('disabled');
		}
		
		
	});
</script>