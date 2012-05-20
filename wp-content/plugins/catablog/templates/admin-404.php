<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e('CataBlog Item Not Found', 'catablog'); ?></h2>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<p><?php _e("You attempted to edit an item that doesn't exist. Perhaps it was deleted?", 'catablog'); ?></p>
	<ul>
		<li><strong><?php _e("Go To:", 'catablog'); ?></strong></li>
		<li><a href="admin.php?page=catablog"><?php _e("CataBlog Library", 'catablog'); ?></a></li>
		<li><a href="index.php"><?php _e("Dashboard", 'catablog'); ?></a></li>
	</ul>
</div>