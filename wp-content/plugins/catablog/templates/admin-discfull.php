<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e('Storage Space Full!', 'catablog'); ?></h2>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<p>
		<?php _e("CataBlog can't make a new entry because your site has run out of storage space.", 'catablog'); ?>
	</p>
	<p>
		<?php $current_usage = round((get_dirsize(BLOGUPLOADDIR) / 1024 / 1024), 2); ?>
		<?php sprintf(_e('You are currently using %sMB of %sMB of storage space.', 'catablog'), $current_usage, get_space_allowed()); ?>
	</p>
	<p>
		<?php _e('Please talk to your WordPress Administrator to have more space allocated to your site or delete some previous uploaded content.', 'catablog'); ?>
	</p>
	<ul>
		<li><strong><?php _e('Go To:', 'catablog'); ?></strong></li>
		<li><a href="index.php"><?php _e('Dashboard', 'catablog'); ?></a></li>
		<li><a href="upload.php"><?php _e('Media', 'catablog'); ?></a></li>
		<li><a href="admin.php?page=catablog"><?php _e('CataBlog', 'catablog'); ?></a></li>
	</ul>
</div>