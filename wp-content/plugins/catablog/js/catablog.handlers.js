		
function catablog_swfupload_loaded() {
	
}

function catablog_swfupload_file_dialog_complete(numFilesSelected, numFilesQueued, totalFilesQueued) {
	try {
		if (numFilesSelected > 0) {
			
			var total_uploads = this.getStats().successful_uploads + this.getStats().files_queued;
			
			jQuery('#current_number').html(this.getStats().successful_uploads);
			jQuery('#total_number').html(total_uploads);
			jQuery('#upload-form-right-col').removeClass('hide');
			
			// jQuery('#cancel-upload').attr('disabled', false);
			
			this.startUpload();
		}
	} catch (error) {
		this.debug(error);
	}
}

function catablog_swfupload_file_queued(file) {
	
}



function catablog_swfupload_upload_start(file) {
	try {
		var total_uploads = this.getStats().successful_uploads + this.getStats().files_queued;
		var percent = Math.ceil((this.getStats().successful_uploads / total_uploads) * 100) + '%';

		jQuery('#catablog-progress-all-uploads .catablog-progress-bar').width(percent);
		jQuery('#catablog-progress-all-uploads .catablog-progress-text').html(percent);
		
		jQuery('#catablog-progress-current-upload .catablog-progress-bar').width(0);
		jQuery('#catablog-progress-current-upload .catablog-progress-text').html(file.name);
		
		jQuery('#current_number').html(this.getStats().successful_uploads + 1);
		jQuery('#total_number').html(total_uploads);
		// jQuery('#upload-form-right-col').removeClass('hide');
		
		
	} catch(error) {
		this.debug(error);
	}
}

function catablog_swfupload_upload_progress(file, bytesLoaded, bytesTotal) {
	var percent = Math.ceil((bytesLoaded/bytesTotal) * 100) + '%';
	jQuery('#catablog-progress-current-upload .catablog-progress-bar').width(percent);
}

function catablog_swfupload_upload_success(file, serverData) {
	try {
		var total_uploads = this.getStats().successful_uploads + this.getStats().files_queued;
		var percent = Math.ceil((this.getStats().successful_uploads / total_uploads) * 100) + '%';
		
		jQuery('#catablog-progress-all-uploads .catablog-progress-bar').width(percent);
		jQuery('#catablog-progress-all-uploads .catablog-progress-text').html(percent);
		
		jQuery('#catablog-progress-current-upload .catablog-progress-bar').width('100%');
		
		jQuery('#new-items-editor').append(serverData);
		var new_item = jQuery('#new-items-editor li:last');
		
		new_item.find('input').bind('keypress', catablog_micro_save);
		
		new_item.find('input.title').bind('keyup', catablog_verify_title);
		new_item.find('input.price').bind('keyup', catablog_verify_price);
		new_item.find('input.order').bind('keyup', catablog_verify_order);
		
		new_item.find('input.button-primary').unbind('keypress').bind('click', catablog_micro_save);
		
		new_item.show(800);
		
	} catch(error) {
		this.debug(error);
	}
}

function catablog_swfupload_upload_complete(file) {
	try {
		var total_uploads = this.getStats().successful_uploads + this.getStats().files_queued;
		var percent = Math.ceil((this.getStats().successful_uploads / total_uploads) * 100) + '%';
		
		jQuery('#catablog-progress-all-uploads .catablog-progress-bar').width(percent);
		jQuery('#catablog-progress-all-uploads .catablog-progress-text').html(percent);
		
		jQuery('#catablog-progress-current-upload .catablog-progress-bar').width('100%');
		
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		}
		
	} catch(error) {
		this.debug(error);
	}
}


function catablog_swfupload_file_queued_error(file, error_code, message) {
	var string = "File Queue Error:\n";
	// for (p in message) {
	// 	string += p + ": " + message[p];
	// }
	string += message;
	alert(string);
}

function catablog_swfupload_upload_error(file, error_code, message) {
	var string = "Upload Error:\n";
	string += message;
	alert(string);
}
