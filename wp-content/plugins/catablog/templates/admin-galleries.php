<div class="wrap">

		<div id="icon-catablog" class="icon32"><br /></div>
		<h2>
			<span><?php _e("CataBlog Galleries", 'catablog'); ?></span>
		</h2>
		
		<?php $this->render_catablog_admin_message() ?>
		
		


		
		
		
		<div class="tablenav">
			
			<form action="admin.php?page=catablog-gallery-create" method="post" id="catablog-new-gallery-form" class="alignleft actions">
				<input type="text" id="catablog-new-gallery-title" name="title" value="<?php _e("+ New Gallery Name", "catablog"); ?>" />
				<input type="submit" class="button" value="<?php _e("Make New Gallery", "catablog"); ?>" />
				<?php wp_nonce_field( 'catablog_create_gallery', '_catablog_create_gallery_nonce', false, true ) ?>
			</form>
			
			<form class="tablenav-pages" action="" method="get">
				<span class="displaying-num"><?php printf(__('%s galleries', 'catablog'), $total_gallery_count) ?></span> <!-- elementos -->

				<?php if ($total_gallery_count > $limit): ?>

				<a class='first-page <?php echo ($paged < 2)? 'disabled' : '' ?>' title='<?php _e('Go to the first page', 'catablog') ?>' href='<?php echo $first_gallery_page_link ?>'>&laquo;</a>
				<a class='prev-page <?php echo ($paged < 2)? 'disabled' : '' ?>' title='<?php _e('Go to the previous page', 'catablog') ?>' href='<?php echo $prev_gallery_page_link ?>'>&lsaquo;</a>

				<span class="paging-input">
					<input type="hidden" name="page" value="catablog-gallery" />
					<input class='current-page' title='<?php _e('Current page') ?>' type='text' name='paged' value='<?php echo $paged ?>' size='1' />
					<?php _e('of', 'catablog') ?>
					<span class='total-pages'><?php echo $total_gallery_pages ?></span>
				</span>

				<a class='next-page <?php echo ($paged >= $total_gallery_pages)? 'disabled' : '' ?>' title='<?php _e('Go to the next page', 'catablog') ?>' href='<?php echo $next_gallery_page_link ?>'>&rsaquo;</a>
				<a class='last-page <?php echo ($paged >= $total_gallery_pages)? 'disabled' : '' ?>' title='<?php _e('Go to the last page', 'catablog') ?>' href='<?php echo $last_gallery_page_link ?>'>&raquo;</a>
				<?php endif ?>
			</form>
			
		</div>
		
		<table class="widefat post" cellspacing="0">
			<thead>
				<tr>
					<th class="column-cb check-column"><input type="checkbox" /></th>
					
					<?php $css_sort = ($sort=='title')? "sorted" : "sortable" ?>
					<?php $sort_url = ($order=='asc')? "&amp;order=desc" : "&amp;order=asc" ?>
					<th class="column-title <?php echo "$css_sort $order" ?>" style="width:120px;">
						<a href="admin.php?page=catablog-gallery&amp;sort=title<?php echo $sort_url ?>">
							<span><?php _e("Title", "catablog"); ?></span>
							<span class="sorting-indicator">&nbsp;</span>
						</a>
					</th>
					
					<th class="column-description <?php echo $description_col_class ?>"><?php _e("Description", "catablog"); ?></th>
					<th class="column-size <?php echo $size_col_class ?>"><?php _e("Size", "catablog"); ?></th>
					<th class="column-shortcode <?php echo $shortcode_col_class ?>"><?php _e("Shortcode", "catablog"); ?></th>
					
					<?php $css_sort = ($sort=='date')? "sorted" : "sortable" ?>
					<?php $sort_url = ($order=='asc')? "&amp;order=desc" : "&amp;order=asc" ?>
					<th class="column-date <?php echo "$css_sort $order" ?> <?php echo $date_col_class ?>" style="width:100px;">
						<a href="admin.php?page=catablog-gallery&amp;sort=date<?php echo $sort_url ?>">
							<span><?php _e("Date", "catablog"); ?></span>
							<span class="sorting-indicator">&nbsp;</span>
						</a>
					</th>
				</tr>
			</thead>

			<tfoot>
				<tr>
					<th class="column-cb check-column"><input type="checkbox" /></th>
					<th class="column-title"><?php _e("Title", "catablog"); ?></th>
					<th class="column-description <?php echo $description_col_class ?>"><?php _e("Description", "catablog"); ?></th>
					<th class="column-size <?php echo $size_col_class ?>"><?php _e("Size", "catablog"); ?></th>
					<th class="column-shortcode <?php echo $shortcode_col_class ?>"><?php _e("Shortcode", "catablog"); ?></th>
					<th class="column-date <?php echo $date_col_class ?>"><?php _e("Date", "catablog"); ?></th>
				</tr>
			</tfoot>

			<tbody id="catablog_items">

				<?php if (count($galleries) < 1): ?>
					<tr>
						<td colspan='8'><p>
							<p><?php _e("No CataBlog Galleries found", 'catablog'); ?></p>
						</td>
					</tr>
				<?php endif ?>

				<?php foreach ($galleries as $gallery): ?>
					<?php $edit   = 'admin.php?page=catablog-gallery&amp;id='.$gallery->getId() ?>
					<?php $remove = wp_nonce_url(('admin.php?page=catablog-gallery-delete&amp;id='.$gallery->getId()), "catablog-gallery-delete") ?>
					
					<tr>
						<th class="check-column">
							<input type="checkbox" class="bulk_selection" name="bulk_action_id" value="<?php echo $gallery->getId() ?>" />
						</th>
						<td class="column-title">
							<strong><a href="<?php echo $edit ?>" title="Edit CataBlog Item"><?php echo ($gallery->getTitle()) ?></a></strong>
							<div class="row-actions">
								<span><a href="<?php echo $edit ?>"><?php _e("Edit", "catablog"); ?></a></span>
								<span> | </span>
								<span class="trash"><a href="<?php echo $remove ?>" class="remove_link"><?php _e("Delete", "catablog"); ?></a></span>
							</div>
						</td>
						<td class="column-description <?php echo $description_col_class ?>"><?php echo $gallery->getDescription() ?></td>
						<td class="column-size <?php echo $size_col_class ?>"><?php echo count($gallery->getItemIds()); ?></td>
						<td class="column-shortcode <?php echo $shortcode_col_class ?>"><input type="text" value='[catablog_gallery id="<?php echo $gallery->getId(); ?>"]' readonly="readonly" /></td>
						<td class="column-date <?php echo $date_col_class ?>">
							<span><?php echo str_replace('-', '/', substr($gallery->getDate(), 0, 10)) ?></span>
							<br />
							<span><?php echo substr($gallery->getDate(), 11) ?></span>
						</td>
					</tr>
				<?php endforeach; ?>

			</tbody>
			
		</table>
		
		<?php if ($total_gallery_count > $limit): ?>
			<div class="tablenav">
				<form class="tablenav-pages" action="" method="get">
					<span class="displaying-num"><?php printf(__('%s items', 'catablog'), $total_gallery_count) ?></span> <!-- elementos -->
			
					<a class='first-page <?php echo ($paged < 2)? 'disabled' : '' ?>' title='<?php _e('Go to the first page', 'catablog') ?>' href='<?php echo $first_gallery_page_link ?>'>&laquo;</a>
					<a class='prev-page <?php echo ($paged < 2)? 'disabled' : '' ?>' title='<?php _e('Go to the previous page', 'catablog') ?>' href='<?php echo $prev_gallery_page_link ?>'>&lsaquo;</a>
			
					<span class="paging-input">
						<input type="hidden" name="page" value="catablog-gallery" />
						<input class='current-page' title='<?php _e('Current page') ?>' type='text' name='paged' value='<?php echo $paged ?>' size='1' />
						<?php _e('of', 'catablog') ?>
						<span class='total-pages'><?php echo $total_gallery_pages ?></span>
					</span>
				
					<a class='next-page <?php echo ($paged >= $total_gallery_pages)? 'disabled' : '' ?>' title='<?php _e('Go to the next page', 'catablog') ?>' href='<?php echo $next_gallery_page_link ?>'>&rsaquo;</a>
					<a class='last-page <?php echo ($paged >= $total_gallery_pages)? 'disabled' : '' ?>' title='<?php _e('Go to the last page', 'catablog') ?>' href='<?php echo $last_gallery_page_link ?>'>&raquo;</a>
				</form>
			</div>
		<?php endif ?>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		// BIND TRASH CATALOG ITEM WARNING
		$('.remove_link').bind('click', function(event) {
			return (confirm('<?php _e("Are you sure you want to permanently delete this gallery?", "catablog"); ?>'));
		});
		
		// BIND NEW GALLERY FORM
		var make_gallery_button = $('#catablog-new-gallery-form').find('input[type="submit"]');
		var make_gallery_title  = $('#catablog-new-gallery-title');
		var new_name_string     = $('#catablog-new-gallery-title').val();
		
		make_gallery_button.attr('disabled', true);
		make_gallery_title.focus(function() {
			var element = $(this);
			if (element.val() == new_name_string) {
				element.val('');
				make_gallery_button.attr('disabled', false);
			}
		});
		make_gallery_title.blur(function() {
			var element = $(this);
			element.val(element.val().trim());
			if (element.val() == '') {
				element.val(new_name_string);
				make_gallery_button.attr('disabled', true);
			}
		});
		

		
		// BIND AUTO SELECT FOR SHORTCODE VALUE
		$('#catablog_items input').click(function(event) {
			this.focus();
			this.select();
		});
		
		// FLASH THE NEW FORM RED WHEN YOU CLICK THE NEW BUTTON
		$('#catablog-gallery-create-form').append('<div class="catablog-red-curtain">&nbsp;</div>');
		$('#catablog-new-gallery-button').click(function(event) {
			$('#catablog-gallery-create-form .catablog-red-curtain').css({display:'block'}).fadeOut(800);
		});
		
		
		
		
		//// BIND THE SCREEN SETTINGS AJAX SAVE
		var nonce = '<?php echo wp_create_nonce("catablog-update-screen-settings") ?>';
		$('.hide-catablog-column-tog').bind('change', function(event) {
			var column_class = "." + this.id.replace("hide-", "");
			
			if (!this.checked) {
				$(column_class).hide();
			}
			else {
				$(column_class).show();
			}
			
			saveScreenSettings('#adv-settings input', nonce);
		});
		$('#entry_per_page').bind('change', function(event) {
			saveScreenSettings('#adv-settings input', nonce);
		});
		
	});
</script>