	<table class="widefat post" cellspacing="0">
		<thead>
			<tr>
				<th class="column-cb check-column"><input type="checkbox" /></th>
				<th class="cb_icon_column"><?php _e("Image", "catablog"); ?></th>
				<?php $css_sort = ($sort=='title')? "sorted" : "sortable" ?>
				<?php $sort_url = ($order=='asc')? "&amp;order=desc" : "&amp;order=asc" ?>
				<?php $cat_url  = (isset($_GET['category']))? "&amp;category=".intval($_GET['category']) : "" ?>
				<th class="<?php echo "$css_sort $order" ?>" style="width:120px;">
					<a href="admin.php?page=catablog&amp;sort=title<?php echo $sort_url . $cat_url ?>">
						<span><?php _e("Title", "catablog"); ?></span>
						<span class="sorting-indicator">&nbsp;</span>
					</a>
				</th>
				
				
				<th class="column-description <?php echo $description_col_class ?>"><?php _e("Description", "catablog"); ?></th>
				<th class="column-link <?php echo $link_col_class ?>"><?php _e("Link", "catablog"); ?></th>
				<th class="column-price <?php echo $price_col_class ?>"><?php _e("Price", "catablog"); ?></th>
				<th class="column-product_code <?php echo $product_code_col_class ?>"><?php _e("Product Code", "catablog"); ?></th>

				<th class="column-categories <?php echo $categories_col_class ?>"><?php _e("Categories", "catablog"); ?></th>				
				<?php $css_sort = ($sort=='menu_order')? "sorted" : "sortable" ?>
				<?php $sort_url = ($order=='asc')? "&amp;order=desc" : "&amp;order=asc" ?>
				<?php $cat_url  = (isset($_GET['category']))? "&amp;category=".intval($_GET['category']) : "" ?>
				<th class="column-order <?php echo "$css_sort $order" ?> <?php echo $order_col_class ?>" style="width:80px;">
					<a href="admin.php?page=catablog&amp;sort=menu_order<?php echo $sort_url . $cat_url ?>">
						<span><?php _e("Order", "catablog"); ?></span>
						<span class="sorting-indicator">&nbsp;</span>
					</a>
				</th>
				
				<?php $css_sort = ($sort=='date')? "sorted" : "sortable" ?>
				<?php $sort_url = ($order=='asc')? "&amp;order=desc" : "&amp;order=asc" ?>
				<?php $cat_url  = (isset($_GET['category']))? "&amp;category=".intval($_GET['category']) : "" ?>
				<th class="column-date <?php echo "$css_sort $order" ?> <?php echo $date_col_class ?>" style="width:100px;">
					<a href="admin.php?page=catablog&amp;sort=date<?php echo $sort_url . $cat_url ?>">
						<span><?php _e("Date", "catablog"); ?></span>
						<span class="sorting-indicator">&nbsp;</span>
					</a>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th class="column-cb check-column"><input type="checkbox" /></th>
				<th class="cb_icon_column"><?php _e("Image", "catablog"); ?></th>
				<th class=""><?php _e("Title", "catablog"); ?></th>

				<th class="column-description <?php echo $description_col_class ?>"><?php _e("Description", "catablog"); ?></th>
				<th class="column-link <?php echo $link_col_class ?>"><?php _e("Link", "catablog"); ?></th>
				<th class="column-price <?php echo $price_col_class ?>"><?php _e("Price", "catablog"); ?></th>
				<th class="column-product_code <?php echo $product_code_col_class ?>"><?php _e("Product Code", "catablog"); ?></th>
				
				<th class="column-categories <?php echo $categories_col_class ?>"><?php _e("Categories", "catablog"); ?></th>
				<th class="column-order <?php echo $order_col_class ?>"><?php _e("Order", "catablog"); ?></th>
				<th class="column-date <?php echo $date_col_class ?>"><?php _e("Date", "catablog"); ?></th>
			</tr>
		</tfoot>
		
		<tbody id="catablog_items">
			
			<?php if (count($results) < 1): ?>
				<tr>
					<td colspan='10'><p>
						<p><?php _e("No catalog items found", 'catablog'); ?></p>

						<?php if ($selected_term !== false): ?>
							<p><?php _e("Use the category drop down above to switch category views.", 'catablog'); ?></p>
						<?php endif ?>
					</td>
				</tr>
			<?php endif ?>
			
			<?php foreach ($results as $result): ?>
				<?php $edit   = 'admin.php?page=catablog&amp;id='.$result->getId() ?>
				<?php $remove = wp_nonce_url(('admin.php?page=catablog-delete&amp;id='.$result->getId()), "catablog-delete") ?>
				
				<tr>
					<th class="check-column">
						<input type="checkbox" class="bulk_selection" name="bulk_action_id" value="<?php echo $result->getId() ?>" />
					</th>
					<td class="cb_icon_column">
						<a href="<?php echo $edit ?>"><img src="<?php echo $this->urls['thumbnails'] . "/" . $result->getImage() ?>" class="cb_item_icon" alt="" /></a>
					</td>
					<td>
						<strong><a href="<?php echo $edit ?>" title="Edit CataBlog Item"><?php echo ($result->getTitle()) ?></a></strong>
						<div class="row-actions">
							<span><a href="<?php echo $edit ?>"><?php _e("Edit", "catablog"); ?></a></span>
							<span> | </span>
							<span class="trash"><a href="<?php echo $remove ?>" class="remove_link"><?php _e("Delete", "catablog"); ?></a></span>
						</div>
					</td>
					
					
					<td class="column-description <?php echo $description_col_class ?>"><?php echo $result->getDescriptionSummary() ?></td>
					<td class="column-link <?php echo $link_col_class ?>"><?php echo $result->getLink() ?></td>
					<td class="column-price <?php echo $price_col_class ?>"><?php echo $result->getPrice() ?></td>
					<td class="column-product_code <?php echo $product_code_col_class ?>"><?php echo $result->getProductCode() ?></td>
					
					<td class="column-categories <?php echo $categories_col_class ?>"><?php echo implode(', ', $result->getCategories())?></td>
					
					<td class="column-order <?php echo $order_col_class ?>">&nbsp;&nbsp;<?php echo htmlspecialchars($result->getOrder(), ENT_QUOTES, 'UTF-8') ?></td>
					
					<td class="column-date <?php echo $date_col_class ?>">
						<span><?php echo str_replace('-', '/', substr($result->getDate(), 0, 10)) ?></span>
						<br />
						<span><?php echo substr($result->getDate(), 11) ?></span>
					</td>
				</tr>
			<?php endforeach; ?>

		</tbody>
	</table>