
		<ul id="catablog_items" class="catablog-grid-view">
			
			<?php if (count($results) < 1): ?>
				<p><?php _e("No catalog items found", 'catablog'); ?></p>
				
				<?php if ($selected_term !== false): ?>
					<p><?php _e("Use the category drop down above to switch category views.", 'catablog'); ?></p>
				<?php endif ?>
			<?php endif ?>
			
			<?php foreach ($results as $result): ?>
				<?php $edit   = 'admin.php?page=catablog&amp;id='.$result->getId() ?>
				<?php $remove = 'admin.php?page=catablog-delete&amp;id='.$result->getId() ?>
				<li>
					<a href="<?php echo $edit ?>" class="catablog-admin-thumbnail"><img src="<?php echo $this->urls['thumbnails'] . "/" . $result->getImage() ?>" class="catablog-grid-thumbnail" width="100" height="100" alt="" /></a>
					
					<a href="<?php echo $edit ?>" class="catablog-title"><small>
						<?php $truncated_title = (function_exists('mb_substr'))? mb_substr($result->getTitle(), 0, 30) : substr($result->getTitle(), 0, 30) ?>
						<?php $title = ($result->string_length($result->getTitle()) > 30)? trim($truncated_title).'...' : $result->getTitle() ?>
						<?php echo htmlentities($title, ENT_QUOTES, 'UTF-8') ?>
					</small></a>
					
					<input type="checkbox" class="bulk_selection" name="bulk_action_id" value="<?php echo $result->getId() ?>" />
					
				</li>
			<?php endforeach; ?>
			<li class="clear">&nbsp;</li>
		</ul>