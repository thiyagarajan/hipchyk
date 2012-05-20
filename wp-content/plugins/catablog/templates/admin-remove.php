<div class="wrap">
	
	<div id="icon-catablog" class="icon32"><br /></div>
	<h2><?php _e("CataBlog Remove", "catablog"); ?></h2>
	
	<noscript>
		<div class="error">
			<strong><?php _e("You must have a JavaScript enabled browser to remove your catalog installation, sorry...", "catablog"); ?></strong>
		</div>
	</noscript>
	
	<?php $this->render_catablog_admin_message() ?>
	
	<div id="catablog-progress" class="catablog-progress">
		<div class="catablog-progress-bar">&nbsp;</div>
		<h3 class="catablog-progress-text"><?php _e("Removing CataBlog...", "catablog"); ?></h3>
	</div>
	
	<ul id="catablog-console"></ul>
	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		var progress_bar = $('#catablog-progress div.catablog-progress-bar');
		
		if (!confirm("Are you sure you want to remove all CataBlog data from your WordPress site? You cannot undo this.")) {
			return false;
		}
		
		
		delete_library_part();
		
		function delete_library_part() {
			var params  = {
				'action':   'catablog_delete_library',
				'security': '<?php echo wp_create_nonce("catablog-delete-library") ?>'
			}


			$.post(ajaxurl, params, function(data) {
				try {
					var json = $.parseJSON(data);
					if (json.success == false) {
						alert(json.error);
					}
					else {
						if (json.remaining > 0) {
							var console_message = "<li>" + json.message + "</li>";
							$('#catablog-console').append(console_message);
							progress_bar.width((progress_bar.width() + 20));
							delete_library_part();
						}
						else {
							var console_message = "<li><strong>" + json.message + "</strong></li>";
							$('#catablog-console').append(console_message);
							progress_bar.css('width', '50%');
							delete_system();
						}
					}
				}
				catch(error) {alert(error);}
			});
		}
		
		
		function delete_system() {
			var params = {
				'action':   'catablog_delete_system',
				'security': '<?php echo wp_create_nonce("catablog-delete-system")?>'
			}
			
			$.post(ajaxurl, params, function(data) {
				try {
					var json = $.parseJSON(data);
					if (json.success == false) {
						alert(json.error);
					}
					else {
						progress_bar.css('width', '100%');
						var console_message = "<li><strong>" + json.message + "</strong></li>";
						$('#catablog-console').append(console_message);
						var console_message = "<li><strong>" + json.message2 + "</strong></li>";
						$('#catablog-console').append(console_message);
						var console_message = "<li><strong>" + json.message3 + "</strong></li>";
						$('#catablog-console').append(console_message);
					}
				}
				catch(error) { alert(error); }
			});
		}
		
		

		
	});
</script>