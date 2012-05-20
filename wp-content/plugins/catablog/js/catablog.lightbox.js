jQuery(function($) {
	jQuery.fn.catablogLightbox = function(config) {
		// PlugIn Variables
		var size     = this.size();
		var settings = {'size': size};
		if (config) jQuery.extend(settings, config);
		
		var timeout = null;
		
		var image_extensions = ['jpg', 'jpeg', 'gif', 'png'];
		var hold_click = false;
		var lightbox_images = [];
		var current_image   = -1;
		
		// PlugIn Construction applied across each selected jQuery object
		this.each(function(i) {
			
			lightbox_images[i] = this;
			
			if (jQuery(this).get(0).nodeName.toLowerCase() == "a") {
				// selected element is an achor tag
				
				var href             = jQuery(this).attr('href');
				var extension        = href.split('.').pop().toLowerCase();
				
				if (in_array(extension, image_extensions)) {
					
					jQuery(this).bind('click', function(event) {
						current_image = i;
						open_lightbox(lightbox_images[i]);
						
						jQuery('.catablog-selected').removeClass('catablog-selected');
						jQuery(this).addClass('catablog-selected');
						
						event.stopPropagation();
						return false;
					});
				}
				
			}
			else if (jQuery(this).get(0).nodeName.toLowerCase() == "img") {
				// selected element is an image tag
				if (typeof(console) !== 'undefined' && console != null) {
					console.log('You are using the CataBlog LightBox in an incorrect way, please set the lightbox selector to point at anchor tags surrounding your thumbnail images.');
				}
				
				jQuery(this).css('cursor','pointer').bind('click', function(event) {
					current_image = i;
					open_lightbox(lightbox_images[i]);
					
					jQuery('.catablog-selected').removeClass('catablog-selected');
					jQuery(this).addClass('catablog-selected');
					
					event.stopPropagation();
					return false;
				});
				
			}
			else {
				// selected element is of an unsupported tag type
				if (typeof(console) !== 'undefined' && console != null) {
					console.log('You are using the CataBlog LightBox in an incorrect way, please set the lightbox selector to point at anchor tags surrounding your thumbnail images.');
				}
			}


		});
		
		// Private Functions
		function open_lightbox(element) {
			
			var support_fixed   = supportPositionFixed();
			var curtain_density = 0.85;
			var fadein_speed    = 0;
			var page_top        = jQuery(document).scrollTop() + 30;
			
			// add the curtain div into the DOM
			jQuery('body').append("<div id='catablog-curtain'>&nbsp;</div>");
			var curtain = jQuery('#catablog-curtain');

			// bind the curtain click and fade the curtain into view
			curtain.bind('click', function() {
				close_lightbox();
			});
			curtain.css('opacity', curtain_density);
			
			if (!support_fixed) {
				var window_height   = jQuery(window).height();
				var document_height = jQuery(document).height();
				curtain.css('position', 'absolute');
				curtain.height(document_height);
			}
			
			// add the lightbox div into the DOM
			jQuery('body').append("<div id='catablog-lightbox'><div id='catablog-whiteboard' class='loading'></div></div>");
			var lightbox = jQuery('#catablog-lightbox');
			lightbox.css('top', page_top);	

			
			// MAKE NOTE HERE
			lightbox.bind('click', function() {
				close_lightbox();
			});
			jQuery('#catablog-whiteboard').bind('click', function(event) {
				event.stopPropagation();
				// do not return false, break a:links in whiteboard 
			});
			
			
			lightbox.show();
			
			load_catablog_image(element);
			
		}
		
		
		
		function load_catablog_image(element) {
			var load_attempts = 0;
			
			var fullsize_pic = new Image();
			fullsize_pic.onload = function() {
				var row  = jQuery(element).closest('.catablog-row').get(0);
				var meta = calculateMeta(row);
				expand_lightbox(this, meta);
			};
			fullsize_pic.onerror = function() {
				load_attempts++;
				if (load_attempts < 2) {
					this.src = this.src.replace("/catablog/fullsize", "/catablog/originals");
				}

			};
			
			if (element.nodeName.toLowerCase() == 'a') {
				fullsize_pic.src = element.href;
			}
			else {
				fullsize_pic.src = element.src.replace("/catablog/thumbnails", "/catablog/fullsize");;
			}
		}
		
		
		
		function expand_lightbox(img, meta) {
			
			var lightbox = jQuery('#catablog-whiteboard');
			
			var w = img.width;
			var h = img.height;
			var s = img.src;
			
			var window_width  = jQuery(window).width();
			var window_height = jQuery(window).height();
						
			if (w > window_width || h > window_height) {
				w_ratio = window_width / w - 0.1;
				h_ratio = window_height / h - 0.1;
				
				if (w_ratio < h_ratio) {
					w = w * w_ratio;
					h = h * w_ratio;
				}
				else {
					w = w * h_ratio;
					h = h * h_ratio;					
				}
			}
			
			var title       = "<h4 class='catablog-lightbox-title'>" + meta.title + "</h4>";
			var description = "<div class='catablog-lightbox-desc'>" + meta.description + "</div>";
			var nav         = meta.nav;
			var close       = meta.close
			
			// attach image and navigation
			jQuery(lightbox).append("<div id='catablog-lightbox-image' />");
			jQuery('#catablog-lightbox-image').height(h);
			
			if (!jQuery('#catablog-lightbox-image').append("<img src='"+s+"' />")) {
				if (typeof(console) !== 'undefined' && console != null) {
					console.log('failed appending image to html dom');
				}
			};
			
			jQuery('#catablog-lightbox-image img').css({width:w, height:h});
			
			jQuery('#catablog-lightbox-image').append(nav);
			jQuery('#catablog-lightbox-image a').height(h);
			
			// attach meta data below image
			jQuery(lightbox).append("<div id='catablog-lightbox-meta' />");
			jQuery('#catablog-lightbox-meta').append(title);
			jQuery('#catablog-lightbox-meta').append(description);
			
			jQuery('#catablog-whiteboard').append(close);
			
			
			lightbox.animate({width:w, height:h}, 400, function() {
				
				jQuery('#catablog-whiteboard').removeClass('loading');
				
				var full_height = h + jQuery('#catablog-lightbox-meta').outerHeight();
				
				jQuery(this).children('#catablog-lightbox-meta').show();
				jQuery(this).animate({height:full_height}, 400, function() {
					hold_click = false;
					bindNavigationControls();
				})
				
				jQuery('#catablog-lightbox-image').fadeIn(400, function() {
					
				});
			});
		}
		
		
		
		
		
		function change_lightbox(element) {
			
			jQuery('#catablog-whiteboard').addClass('loading');
			
			var row   = jQuery(element).closest('.catablog-row').get(0);
			var speed = 150;
			
			jQuery('#catablog-lightbox-meta').fadeOut(speed, function() {
				jQuery(this).remove();
			});
			jQuery('#catablog-lightbox-image').fadeOut(speed, function() {
				jQuery(this).remove();
				
				
				load_catablog_image(element);
				
			});			
		}
		
		

		
		function navigate_lightbox(direction) {
			if (hold_click) {
				return false;
			}
			
			hold_click = true;
			unbindNavigationControls();
			
			var selected = jQuery('.catablog-selected');
			var new_image  = null;
			
			if (direction == 'next') {
				new_image = lightbox_images[current_image + 1];
				if (new_image == undefined) {
					current_image = 0;
					new_image     = lightbox_images[current_image];
				}
				current_image += 1;
			}
			else if (direction == 'prev') {
				new_image = lightbox_images[current_image - 1];
				if (new_image == undefined) {
					current_image = lightbox_images.length - 1;
					new_image     = lightbox_images[current_image];
				}
				current_image -= 1;
			}
			
			
			// check if the new_image is an a tag or its wrapped in an a tag
			// if wrapped in an anchor tag, replace new_image with the anchor tag
			// if not wrapped in an achor tag, log an error to the console if it exists
			if (new_image.nodeName.toLowerCase() != 'a') {
				if (jQuery(new_image).closest('a').size() > 0) {
					new_image = jQuery(new_image).closest('a').get(0);
				} else {	
					if (new_image.nodeName.toLowerCase() == 'img') {
						
					}
					else {
						if (typeof(console) !== 'undefined' && console != null) {
							console.log("Could not find the adjacent image because the adjacent .catablog-image element is not an anchor or image tag.");
						}
						
						hold_click = false;
						return false;
					}
					
				}
			}
			
			var new_href = '';
			if (new_image.nodeName.toLowerCase() == 'a') {
				var new_href = new_image.href;
			}
			else {
				var new_href = new_image.src;
			}
			
			
			var new_extension = new_href.split('.').pop().toLowerCase();
			if (in_array(new_extension, image_extensions) == false) {
				if (current_image == 0) {
					hold_click = false;
					navigate_lightbox('next');
				}
				else if (current_image == (lightbox_images.length - 1)) {
					hold_click = false;
					navigate_lightbox('prev');
				}
				else {
					hold_click = false;
					navigate_lightbox(direction);
				}
				return false;
			}

			
			new_thumbnail = new_image;
			
			
			
			selected.removeClass('catablog-selected');
			jQuery(new_image).addClass('catablog-selected');
			
			change_lightbox(new_thumbnail);
		}
		
		
		function close_lightbox() {
			unbindNavigationControls();
			
			var fadeout_speed = 300;
			
			jQuery('#catablog-curtain').fadeOut(fadeout_speed, function() {
				jQuery(this).remove();
			});
			jQuery('#catablog-lightbox').fadeOut(fadeout_speed, function() {
				jQuery(this).remove();
			});
			jQuery('.catablog-selected').removeClass('catablog-selected');
		}
		
		
		
		function calculateMeta(row) {
			var row          = jQuery(row);
			
			if ((typeof js_i18n) == 'undefined') {
				var prev_tip     = 'You may also press "P" or the left arrow on your keyboard';
				var next_tip     = 'You may also press "N" or the right arrow on your keyboard';
				var close_tip    = "Close LightBox Now";
				// 
				var prev_label   = "PREV";
				var next_label   = "NEXT";
				var close_label  = "CLOSE";	
			}
			else {
				var prev_tip    = ((typeof js_i18n.prev_tip) == 'undefined')? 'You may also press "P" or the left arrow on your keyboard' : js_i18n.prev_tip;
				var next_tip    = ((typeof js_i18n.prev_tip) == 'undefined')? 'You may also press "N" or the right arrow on your keyboard' : js_i18n.next_tip;
				var close_tip   = ((typeof js_i18n.prev_tip) == 'undefined')? 'Close LightBox Now' : js_i18n.close_tip;
				// 
				var prev_label  = ((typeof js_i18n.prev_tip) == 'undefined')? "PREV" : js_i18n.prev_label;
				var next_label  = ((typeof js_i18n.prev_tip) == 'undefined')? "NEXT" : js_i18n.next_label;
				var close_label = ((typeof js_i18n.prev_tip) == 'undefined')? "CLOSE" : js_i18n.close_label;
			}
			
			var prev_button  = "<a href='#prev' id='catablog-lightbox-prev' class='catablog-nav' title='"+prev_tip+"'><span class='catablog-lightbox-nav-label'>"+prev_label+"</span></a>";
			var next_button  = "<a href='#next' id='catablog-lightbox-next' class='catablog-nav' title='"+next_tip+"'><span class='catablog-lightbox-nav-label'>"+next_label+"</span></a>";
			var close_button = "<a href='#close' id='catablog-lightbox-close' class='catablog-nav' title='"+close_tip+"'>"+close_label+"</a>";
			
			var meta = {};
			
			var title        = row.find('.catablog-title').html()
			var description  = row.find('.catablog-description').html();
			
			meta.title       = (title == null)? "" : title;
			meta.description = (description == null)? "" : description;
			meta.buynow      = "";
			meta.close       = close_button;
			
			meta.nav   = "";
			
			if (settings['navigation'] == 'combine') {
				if (current_image < (lightbox_images.length - 1)) {
					meta.nav += next_button;
				}

				if (current_image > 0) {
					meta.nav += prev_button;
				}
			}
			else {
				var next_row_item = row.next().hasClass('catablog-row');
				var prev_row_item = row.prev().hasClass('catablog-row');
				
				var row_images = row.find('.catablog-image');
				if (row_images.length > 1) {
					var clicked_image = jQuery(lightbox_images[current_image]);
					
					var subimage_offset = -1;
					for (var i = 0; i < row_images.length; i++) {
						if (clicked_image.attr('href') == row_images.eq(i).attr('href')) {
							subimage_offset = i;
						}
					}
					
					if (subimage_offset < 0) {
						if (typeof(console) !== 'undefined' && console != null) {
							console.log('SubImage Offset Error, subimage clicked in a catalog row that contains no subimages.');
						}
					}
					else if (subimage_offset == 0) {
						var next_row_item = true;
					}
					else if (subimage_offset == (row_images.length - 1)) {
						var prev_row_item = true;
					}
					else {
						var next_row_item = true;
						var prev_row_item = true;
					}
				}
				
				if (next_row_item) {
					meta.nav += next_button;
				}
				
				// if (current_image > 0) {
				if (prev_row_item) {
					meta.nav += prev_button;
				}
			}
			
			return meta;
		}
		
		
		
		
		
		/******************************
		**   SUPPORT METHODS
		******************************/
		function supportPositionFixed() {
			var isSupported = null;
			if (document.createElement) {
				var el = document.createElement('div');
				if (el && el.style) {
					el.style.position = 'fixed';
					el.style.top = '10px';
					var root = document.body;
					if (root && root.appendChild && root.removeChild) {
						root.appendChild(el);
						isSupported = (el.offsetTop === 10);
						root.removeChild(el);
					}
				}
			}
			return isSupported;
		}
		
		function bindNavigationControls() {
			
			// bind next and previous buttons
			jQuery('#catablog-lightbox-prev').bind('click', function(event) {
				navigate_lightbox('prev');
				return false;
			});

			jQuery('#catablog-lightbox-next').bind('click', function(event) {
				navigate_lightbox('next');
				return false;
			});
			
			
			// bind close button
			jQuery('#catablog-lightbox-close').bind('click', function(event) {
				close_lightbox();
				return false;
			});
			jQuery('#catablog-lightbox-close').bind('mouseenter', function(event) {
				jQuery(this).addClass('catablog-lightbox-close-hover');
				return false;
			});
			jQuery('#catablog-lightbox-close').bind('mouseleave', function(event) {
				jQuery(this).removeClass('catablog-lightbox-close-hover');
				return false;
			});
			jQuery(document).bind('mousemove', function(event) {
				var close_button = jQuery('#catablog-lightbox-close');
				
				if (close_button.is(':hidden')) {
					close_button.css('zIndex', 10800);
					close_button.fadeIn(50);
				}
				else {
					hideCloseButtonTimer(close_button);
				}
				
				
				
			});
			
			// bind keyboard shortcuts
			jQuery(document).bind('keyup', function(event) {
				var key_code = (event.keyCode ? event.keyCode : event.which);
				
				var forward_keycodes = [39, 78, 83];
				var back_keycodes    = [37, 80, 65];
				var escape_keycodes  = [27];
				
				if (in_array(key_code, forward_keycodes)) {
					jQuery('#catablog-lightbox-next').click();
				}
				if (in_array(key_code, back_keycodes)) {
					jQuery('#catablog-lightbox-prev').click();
				}
				if (in_array(key_code, escape_keycodes)) {
					close_lightbox();
				}
			});
		}
		
		function unbindNavigationControls() {
			jQuery('#catablog-lightbox-prev').unbind('click');
			jQuery('#catablog-lightbox-next').unbind('click');
			jQuery('#catablog-lightbox-close').unbind('click');
			jQuery(document).unbind('mousemove');
			jQuery(document).unbind('keyup');
			
			jQuery('#catablog-lightbox-close').fadeOut(200);
		}
		
		function hideCloseButtonTimer(obj) {
			clearTimeout(timeout);
			timeout = setTimeout(function() {
				if (obj.hasClass('catablog-lightbox-close-hover')) {
					hideCloseButtonTimer(obj);
				}
				else {
					obj.fadeOut(200);							
				}
			}, 1500);
		}
		
		function in_array (needle, haystack, argStrict) {
		    var key = '', strict = !!argStrict;

		    if (strict) {
		        for (key in haystack) {
		            if (haystack[key] === needle) {
		                return true;
		            }
		        }
		    } else {
		        for (key in haystack) {
		            if (haystack[key] == needle) {
		                return true;
		            }
		        }
		    }

		    return false;
		}
		
		
		
		
		return this;
	};
});



