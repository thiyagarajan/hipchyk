<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e("About CataBlog", 'catablog'); ?></h2>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<p>
		<?php printf(__("%sCataBlog%s is written by %sZachary Segal%s in his spare time.", "catablog"),"<a href='http://catablog.illproductions.com/' target='_blank'>", "</a>", "<a href='http://catablog.illproductions.com/about-author/' target='_blank'>", "</a>"); ?>
		<?php printf(__('It is a cataloging tool for %sWordPress%s that allows you to easily manage a list of items with automatically generated thumbnail images.', "catablog"), "<a href='http://wordpress.org' target='_blank'>", "</a>"); ?>
		<?php printf(__('Use of CataBlog is completely free, even commercial sites for now, all that I ask is that you rate the plugin at the %sWordPress Plugin Repository%s.', "catablog"), "<a href='http://wordpress.org/extend/plugins/catablog/' target='_blank'>", "</a>"); ?>
	</p>
	
	<p>
		<?php printf(__("%sCSS Modifications:%s You may always override CataBlog's CSS settings to create custom looks. If you make a catablog.css file in your active theme's directory it will be automatically loaded and applied. This makes it easy to prepare your custom theme for CataBlog integration and will also protect your customization for future version to come.", 'catablog'), "<strong>", "</strong>"); ?> 
	</p>
	
	<table class="catablog_stats wide" cellspacing="5">
		<thead>
			<tr><td colspan="2"><h3><strong><?php _e("Server Statistics", 'catablog'); ?></strong></h3></td></tr>
		</thead>
		<tbody>
		<?php foreach ($stats as $label => $value): ?>
			<tr>
				<?php $transformed_label = str_replace("_", " ", $label) . ":" ?>
				<td><strong><?php _e($transformed_label, 'catablog'); ?></strong></td>
				<td><?php echo $value ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>

</div>

<?php

// CataBlog About Panel System Versions and Info Labels
__('CataBlog Version:');
__('MySQL Version:');
__('PHP Version:');
__('GD Version:');
__('PHP Memory Usage:');
__('PHP Memory Limit:');
__('Max Uploaded File Size:');
__('Max Post size:');
__('Thumbnail Disc Usage:');
__('Full Size Disc Usage:');
__('Original Upload Disc Usage:');
__('Total Disc Usage:');

?>