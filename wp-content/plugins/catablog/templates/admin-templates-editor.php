<div class="wrap">
	
	<?php screen_icon(); ?>
	<h2><?php _e('CataBlog Templates') ?></h2>
	
	<noscript>
		<div class="error">
			<strong><?php _e("You must have a JavaScript enabled browser to use the CataBlog Templates Editor. You may always edit the CataBlog template file directly using FTP.", "catablog"); ?></strong>
		</div>
	</noscript>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<div id="message" class="updated hide">
		<strong>&nbsp;</strong>
	</div>
	
	<div id="catablog-template-management">
		<ul id="catablog-template-filelist" class="catablog-panel-tabs">
			
			<?php if ($views->isDirectory()): ?>
				
				<li><strong><?php _e("Views", "catablog") ?></strong></li>
				
				<?php $file_array = $views->getFileArray(); ?>
				<?php $standard_templates = array('default.htm', 'single.htm', 'archive.htm', 'store.htm'); ?>
				
				<?php foreach ($standard_templates as $standard_template): ?>
					<?php if (in_array($standard_template, $file_array)): ?>
						<li><a href="#<?php echo $standard_template; ?>" class="catablog-load-code"><?php echo pathinfo($standard_template, PATHINFO_FILENAME) ?></a></li>
					<?php else:?>
						<li class="error"><?php printf("%s", $standard_template); ?></li>
					<?php endif ?>
				<?php endforeach ?>
				
				<li><strong><?php _e("Templates", "catablog") ?></strong></li>
				
				<?php foreach($file_array as $key => $view): ?>
					<?php if (!in_array($view, $standard_templates)): ?>
						<li><a href="#<?php echo $view ?>" class="catablog-load-code catablog-template"><?php echo pathinfo($view, PATHINFO_FILENAME) ?></a></li>
					<?php endif ?>
				<?php endforeach ?>
				
				<li><a href="#new" id="catablog-template-new"><?php printf("+ %s", __("new", "catablog")); ?></a></li>
				
			<?php else: ?>
				
				<li class="error"><?php _e("Could not locate the CataBlog Templates directory. Please reinstall CataBlog.", "catablog"); ?></li>
				
			<?php endif ?>
		</ul>
		
		<div id="catablog-template-editor">
			<h2 id="catablog-template-name" class="disabled">
				<span><?php _e("select a template to edit", "catablog"); ?></span>
				<a class="delete-link" href="#"><?php _e("[DELETE]", "catablog") ?></a>
				<input type="text" class="hide" />
			</h2>
			
			<form action="admin.php?page=catablog-templates-save" method="post">
				<textarea name="template-code" id="catablog-template-code" class="catablog-code" rows="10" cols="30" disabled="disabled"></textarea>
				
				<fieldset style="float:left; width:80%;">
					<input type="hidden" id="catablog-template-filename" name="catablog-template-filename" value="" />
					<?php wp_nonce_field( 'catablog_templates_save', '_catablog_templates_save_nonce', false, true ) ?>
					<input type="submit" id="catablog-template-save-button" name="save" class="button-primary button-disabled" disabled="disabled" value="<?php _e('Save Changes', 'catablog') ?>" />
					<span><?php printf(__('or %sundo current changes%s', 'catablog'), '<a href="admin.php?page=catablog-templates">', '</a>'); ?></span>
				</fieldset>
			</form>
			
			<form id="catablog-template-delete-form" action="admin.php?page=catablog-templates-delete" method="post" style="margin-left:81%; text-align:right;">
				<input type="hidden" id="catablog-template-filename-delete" name="catablog-template-filename" value="" />
				<?php wp_nonce_field( 'catablog_templates_delete', '_catablog_templates_delete_nonce', false, true ) ?>
				<input type="submit" id="catablog-template-delete-button" name="delete" class="button catablog-button-delete button-disabled" disabled="disabled" value="<?php _e("Delete Template", "catablog"); ?>" />
			</form>
			
			<p><small>
				<?php _e("You may change the HTML rendered by CataBlog with this control panel, allowing you to make fundamental changes to how catalogs will appear in your posts. You may choose a template to edit from the list of templates to the right . If you want to setup a photo gallery I would recommend that you use the <strong>Gallery Template</strong> by adding the template parameter to the ShortCode. Example: <code>[catablog template=\"gallery\"]</code>", "catablog"); ?>
			</small></p>

			<ul>
				<li><small>
					<?php _e("If you specify a template in your CataBlog ShortCodes the <strong>default template will be ignored</strong>.", "catablog"); ?>
				</small></li>
				<li><small>
					<?php _e("If you do not specify a template in your CataBlog ShortCodes then the <strong>default template will be used</strong>.", "catablog"); ?>
				</small></li>
				<li><small>
					<?php _e("If you do not specify a template and the default template cannot be found then CataBlog will not be able to render your catalog.", "catablog"); ?>
				</small></li>
				<li><small>
					<?php printf(__("If you need more help read the %shelp panel%s.", "catablog"), '<a href="#contextual-help-link" id="catablog-click-contextual-help-link">', '</a>'); ?>
				</small></li>
			</ul>
			
		</div>
		
		
	</div>
	
	

</div><!-- .wrap -->

<div id='catablog_load_curtain'>&nbsp;</div>

<div id="add-template-window" class="catablog-modal">
	<form id="catablog-add-template" class="catablog-form" method="post" action="admin.php?page=catablog-templates-create" enctype="multipart/form-data">
		<h3 class="catablog-modal-title">
			<span style="float:right;"><a href="#" class="hide-modal-window"><?php _e("[close]", 'catablog'); ?></a></span>
			<strong><?php _e("Create a New Template File", 'catablog'); ?></strong>
		</h3>
		<div class="catablog-modal-body">
			<p><strong><?php _e("Save Other Changes Before Creating A New Template File.", 'catablog'); ?></strong></p>
			
			<input type="text" id="new_template_name" name="new_template_name"  />
			<span class="nonessential"> | </span>

			<?php wp_nonce_field( 'catablog_add_template', '_catablog_add_template_nonce', false, true ) ?>
			<input type="submit" name="save" value="<?php _e("Create Template", 'catablog'); ?>" class="button-primary" />
			<p><small>
				<?php _e("Please enter the name of the new CataBlog Template you wish to create.", 'catablog'); ?><br />
				<?php _e("If you are missing any system templates you may make them here by typing in their name, such as default.", "catablog"); ?><br />
				<strong><?php _e("Please only use underscores, hyphens and alphanumeric characters only.", "catablog"); ?></strong>
			</small></p>			
		</div>
	</form>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		// bind the load buttons for the template file list
		$('.catablog-load-code').bind('click', function(event) {
			var filename = this.hash.substring(1);
			var tab_link = $(this);
			var url = "<?php echo $this->urls['user_views'] ?>" + "/" + filename;
			
			$('.selected').removeClass('selected');
			$(this).closest('li').addClass('selected');

			var d = new Date();
			
			$.get(url, {t: d.getTime()}, function(data) {
				$('#catablog-template-code').val(data).attr('disabled', false).focus();
				$('#catablog-template-filename').val(filename);
				
				$('#catablog-template-save-button').removeClass('button-disabled').attr('disabled', false);
				
				$('#catablog-template-name').removeClass('disabled');
				$('#catablog-template-name span').html(tab_link.html());
				
				if (tab_link.hasClass('catablog-template')) {
					$('#catablog-template-name input').val("[catablog template=\"" + tab_link.html() + "\"]").show();
					$('#catablog-template-filename-delete').val(filename);
					$('#catablog-template-delete-button').removeClass('button-disabled').attr('disabled', false);
				}
				else {
					$('#catablog-template-name input').hide();
					$('#catablog-template-filename-delete').val('');
					$('#catablog-template-delete-button').addClass('button-disabled').attr('disabled', true);
				}
			});
			
		});
		
		$('#catablog-template-delete-form').submit(function() {
			if (false == confirm('<?php _e("Are you sure you want to delete this template?", "catablog"); ?>')) {
				return false;
			}
		})
		
		var path = '';
		var hash = window.location.hash;
		if (hash.length > 0) {
			path = '#catablog-template-filelist li a[href="'+hash+'"]';
		}
		$(path).click();
				
		// bind the textareas in the catablog options form to accept tabs
		$('#catablog-template-code').bind('keydown', function(event) {
			var item = this;
			if(navigator.userAgent.match("Gecko")){
				c = event.which;
			}else{
				c = event.keyCode;
			}
			if(c == 9){
				replaceSelection(item,String.fromCharCode(9));
				$("#"+item.id).focus();	
				return false;
			}
		});
		
		$('#catablog-template-controls-new-button').bind('click', function(event) {
			$(this).closest('form').animate({'margin-top':-45, 'height':90}, 300, function() {
				$('#catablog-new-template-name').focus();
			});
		});
		
		$('#catablog-new-template-name').bind('keyup', function(event) {
			if (this.value.length > 0) {
				$('#catablog-new-template-save').removeClass('button-disabled').attr('disabled', false);
			}
			else {
				$('#catablog-new-template-save').addClass('button-disabled').attr('disabled', true);
			}
		})
		
		
		$('#catablog-template-new').click(function(event) {
			jQuery('#add-template-window').show().find('input[type="text"]').focus();
			jQuery('#catablog_load_curtain').fadeTo(200, 0.8);
			return false;
		});
		$('.hide-modal-window').bind('click', function(event) {
			jQuery('.catablog-modal:visible').hide();
			jQuery('#catablog_load_curtain').fadeOut(200);
			return false;
		});

		
		
	 	$("#catablog-template-name input").focus(function() { $(this).select(); } );
		$("#catablog-template-name input").mouseup(function(event){ event.preventDefault(); });
		
		$('#catablog-click-contextual-help-link').click(function(event) {
			$('#contextual-help-link').click();
		})
		
	}); // end onready
</script>