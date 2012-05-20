<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e("CataBlog Import Results", 'catablog'); ?></h2>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<h3><strong><?php _e("Import Console", 'catablog'); ?></strong></h3>

	<?php if ($error): ?>
		<p>
			<?php _e("You must select a valid XML or CSV file to be used for import.", 'catablog'); ?>
		</p>
		<p>
			<?php printf(__("You may choose to read more about %simporting and exporting data from CataBlog%s.", 'catablog'), '<a href="http://catablog.illproductions.com/documentation/importing-and-exporting-catalogs/" target="_blank">', '</a>'); ?>
			<br />
			<?php printf(__("Once you have fixed your file and its format please %stry again%s.", 'catablog'), '<a href="admin.php?page=catablog-options#import">', '</a>'); ?>
		</p>
	<?php else: ?>
		<ul id="catablog-import-messages">
			<?php if (isset($_REQUEST['catablog_clear_db'])): ?>
				
				<li class="updated"><em><?php _e("removing catalog items...", 'catablog'); ?></em></li>
				<?php $items = CataBlogItem::getItems() ?>
				<?php foreach ($items as $item): ?>
					<?php $item->delete(false) ?>
				<?php endforeach ?>
				<li class="updated"><?php _e("Success: <em>All</em> catalog items removed successfully", 'catablog'); ?></li>
				
				<li class="updated"><em><?php _e("removing catalog categories...", 'catablog'); ?></em></li>
				<?php $this->remove_terms() ?>
				<li class="updated"><?php _e("Success: <em>All</em> catalog categories removed successfully", 'catablog'); ?></li>
				
				<li class="updated"><strong><?php _e("DataBase Cleared Successfully", 'catablog'); ?></strong></li>
				<li>&nbsp;</li>
			<?php endif ?>
			
			<?php $this->load_array_to_database($data) ?>
		</ul>	
	<?php endif ?>	
	
</div>