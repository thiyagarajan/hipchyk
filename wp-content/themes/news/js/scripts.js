// JavaScript Document


jQuery(function() {
	if (!$('#coda-slider-1').length){
		return false;
	} else {
		$('#coda-slider-1').codaSlider({
			dynamicArrowLeftText: "",
			dynamicArrowRightText: "",
			sliderHolder: ".widget, .one-fourth, .one-third"
		});
	}
	
	if (!$('#coda-slider-2').length){
		return false;
	} else {
		$('#coda-slider-2').codaSlider({
			dynamicArrowLeftText: "",
			dynamicArrowRightText: "",
			sliderHolder: ".widget, .one-fourth, .one-third"
		});
	}
	if (!$('#coda-slider-3').length){
		return false;
	} else {
		$('#coda-slider-3').codaSlider({
			dynamicArrowLeftText: "",
			dynamicArrowRightText: "",
			sliderHolder: ".widget, .one-fourth, .one-third"
		});
	}

});

//Nivo
jQuery(function() {
	if (!$('.widget_slider').length) {
		return false;
	} else {
        $('.widget_slider').nivoSlider({
			slices: 4,
			boxCols: 4,
			directionNav: false,
			controlNav: false,
			prevText: '',
			nextText: '',
			customNav: true,
			customNavPrev: '.navig-small .SliderNamePrev',
			customNavNext: '.navig-small .SliderNameNext'
		});
	}
});

jQuery(function() {
	if (!$('.widget_slider2').length) {
		return false;
	} else {
        $('.widget_slider2').nivoSlider({
			slices: 4,
			boxCols: 4,
			directionNav: false,
			controlNav: false,
			prevText: '',
			nextText: '',
			customNav: true,
			customNavPrev: '.navig-small .SliderNamePrev2',
			customNavNext: '.navig-small .SliderNameNext2'
		});
	}
});

jQuery(function() {
	if (!$('#slider').length){
		return false;
	} else {
        $('#slider').nivoSlider({
			effect: 'boxRandom',
			animSpeed: 700,
			pauseTime: 7000,
			boxCols: 6,
			directionNav: false,
			controlNav: false,
			prevText: '',
			nextText: '',
			customNav: true,
			customNavPrev: '.big-slider .nivo-prevNav',
			customNavNext: '.big-slider .nivo-nextNav',
			beforeChange: function(){
				$('.grid').delay(100).fadeTo(500, 0.8).delay(200).fadeTo(700, 0);
			},
			afterChange: function(){
			}
		});
	}
});
jQuery(function() {
	if (!$('#slider-short').length){
		return false;
	} else {
        $('#slider-short').nivoSlider({
			effect: 'boxRandom',
			animSpeed: 700,
			pauseTime: 7000,
			boxCols: 6,
			directionNav: false,
			controlNav: false,
			prevText: '',
			nextText: '',
			customNav: true,
			customNavPrev: '.shortcode-slide .nivo-prevNav',
			customNavNext: '.shortcode-slide .nivo-nextNav',
			beforeChange: function(){
				$('.grid').delay(100).fadeTo(500, 0.8).delay(200).fadeTo(700, 0);
			},
			afterChange: function(){
			}
		});
	}
});

/*Menu down*/
jQuery(function(){
	$("#nav li").hover(function () {
												 
		  $(this).children('div').css({visibility: "visible",display: "none"}).slideDown('normal');
	  }, function () {
		  
		  $('div', this).css({visibility: "hidden"});
	  })
	 
});
jQuery(function() {
	$('#nav li div').each( function() {
		$(this).prev('a').css('cursor', 'auto');
	})
})

/*Carousel*/
  jQuery(function() {
	  if (!('#foo1').length){
		return false;
	} else
	  var foo1 = $('#foo1');
	  if (!foo1.length){
		  return false;
	  }
	  
	  foo1.carouFredSel({
		  prev: '#prev1',
		  next: '#next1',
		  pagination: false,
		  auto: false,		
		  scroll: 1
	  });

  });
    jQuery(function() {
		if (!('#foo2').length){
		return false;
	} else
	  var foo2 = $('#foo2');
	  if (!foo2.length){
		  return false;
	  }
	  foo2.carouFredSel({
		  prev: '#prev2',
		  next: '#next2',
		  pagination: false,
		  auto: false,
		  width: "variable",
		  height: "variable",		
		  scroll: 1
	  });

  });
	/*Carousel height*/
jQuery(function() {
	var wrap=$('.caroufredsel_wrapper ul').find(" > img");
	var h_wrap = wrap.attr("height");
	$(this).css('height', h_wrap);
});
//Fade effect

jQuery(function() {
	$('a.alignleft, a.alignright, a.alignnone, a.photo').append('<i></i>').each(function () {
	  var $span = $('i', this);
     if ($.browser.msie && $.browser.version < 9)
        $span.hide();
     else
        $span.css('opacity', 0);
	  $(this).hover(function () {
	    if ($.browser.msie && $.browser.version < 9)
	      $span.show();
	    else
   	    $span.stop().fadeTo(500, 1);
	  }, function () {
	    if ($.browser.msie && $.browser.version < 9)
	      $span.hide();
	    else
  	      $span.stop().fadeTo(500, 0);
	  });
	});
})

/*Hover height width*/
jQuery(function() {
	$("a.alignleft").each(function () {
		var i=$(this).find(" > img");
		var i_w = i.attr("width");
		var i_h = i.attr("height");
		$('i', this).css('width', i_w);
		$('i', this).css('height', i_h);
	});
});



/*nivo-caption width*/
jQuery(function() {
	$(".slider-shprtcode .slider-wrapper").each(function () {
		var im=$(this).find("img");
		var im_w = im.attr("width");
		$('.nivo-caption', this).css('width', im_w);
	});
	$(".widget .nivoSlider").each(function () {
		var im=$(this).find("img");
		var im_w = im.attr("width")-8;
		$('.nivo-caption', this).css('width', im_w);
	});
});

/*photo i height*/
jQuery(function() {
	$(".textwidget-photo a.photo").each(function () {
		var im=$(this).find("img");
		var im_h = im.attr("height");
		$('i', this).css('height', im_h);
	});
});
/*Slider textwidget*/
$(function () {
	if (!('.caroufredsel_wrapper .textwidget').length){
		return false;
	} else
	var block_counter = 0;
	
	$('.caroufredsel_wrapper .textwidget').each(function() {
		
		block_counter++;
		$(this).addClass('block_no_'+block_counter);
		$('.widget-info', this).appendTo('body').addClass('block_no_'+block_counter);
		//console.log('appended: block_no_'+block_counter);
	});
			show_me = '';
			$('.caroufredsel_wrapper .textwidget').hover(
				function() {
					$('body > .widget-info').hide();
					var w = $(this).find("img");
					var w_w = w.attr("width");
					show_me = $(this).attr('class').match(/block_no\_.+?\b/);
					
					var offset = $(this).offset();
					$('body > .'+show_me[0]).css({ top: offset.top - 5, left: offset.left -5, width: w_w}).fadeIn(300);

					/*console.log('top: '+offset.top+'px, left: '+offset.left+'px.');
					console.log($('body > .'+show_me[0]).css('top'));*/
				} , function() {
					$('body > .'+show_me[0]).hover( function() { 
						//$(this).hide();
					} , function() {
						
						$(this).hide();
					}
				);

				});				

});
/*Coda slider autor*/

//Widget
jQuery(function() {
	$(".textwidget").hover(
		function(){
			$('> .widget-info', this).stop().fadeTo(400, 1);
		}, function(){
			$('> .widget-info', this).stop().fadeTo(200, 0, function(){ $(this).hide() });
		}
	);
});


/*	*/

//PS fade info
jQuery(function() {
	if (!('.ps-album').length){
		return false;
	} else {
	$(".ps-album").hover(
		function() {
			if ($.browser.msie && $.browser.version < 9)
			{
				$(".ps-desc", this).stop().show();
			} else {
				$(".ps-desc", this).stop().fadeTo(400, 1);
			}
		} , function() {
			if ($.browser.msie && $.browser.version < 9)
			{
				$(".ps-desc", this).stop().hide();
			} else {
				$(".ps-desc", this).stop().fadeTo(200, 0, function(){$(this).hide()});
			}
		});
	}
});

// flickr animations
jQuery(function () {
	
	$(".flickr i").animate({
			 opacity: 0
	
		  }, {
			 duration: 300,
			 queue: false
		  });      

   $(".flickr").parent().hover(
	   function () {},
	   function () {
		  $(".flickr i").animate({
			 opacity: 0
		  }, {
			 duration: 300,
			 queue: false
		  });
   });
 
   $(".flickr i").hover(
      function () {
		  $(this).animate({
			 opacity: 0
	
		  }, {
			 duration: 300,
			 queue: false
		  });      

		  $(".flickr i").not( $(this) ).animate({
			 opacity: 0.4
		  }, {
			 duration: 300,
			 queue: false
		  });
      }, function () {
      }
   );

});
/*Soc ico effect*/

$(function() {
	var $soc_top;
	$('.trigger').each(function () {
		$(this).hover(function () {
			$('body > .soc-info').remove();
			var $span = $('> span', this);
			var $old_html = $span.html();
			var $new_html = '<span class="soc-info"><span class="soc-cont">' + $span.html() + '<span class="soc-b"></span></span></span>';
			$('body').append($new_html);
			$soc_top = $('body > .soc-info');
			if ($.browser.msie && $.browser.version < 9)
			{
				$soc_top.css({ 'left' : $(this).offset().left, 'top' : $(this).offset().top-30, 'z-index' : '999', 'display' : 'block' })
			} else {
				$soc_top.css({ 'left' : $(this).offset().left, 'top' : $(this).offset().top-30, 'z-index' : '999' }).fadeIn(300)
			}
		}, function () {
			$soc_top.fadeOut(300, function () {$(this).remove()});
		});
	});
});



//Search
$(function(){
 var inputWdith = '212px';
  var inputWdithReturn = '83px';
  jQuery(".i-s").each(function () {
	  $('.i-search').click(function () {
		 $(this).parent().animate({
			  width: inputWdith
		  }, 200)
	  });
	  jQuery('.i-search').blur(function () {
	    
		    $('.i-search').parent().animate({
			  width: inputWdithReturn 
		    }, 200) 
		 
    });            
  });
});

  
  		// comments form
function move_form_to(ee)
{
      var e = $("#form-holder").html();
      var tt = $("#form-holder .header").text();
      
      var sb =$("#form-holder .go_button").attr("title");
      
      var to_slide_up = ($(".comment #form-holder").length ? $("#form-holder") : $(".share_com"));
      
      to_slide_up.slideUp(500, function () {
         $("#form-holder").remove();
         
         ee.append('<div id="form-holder">'+e+'</div>');
         $("#form-holder .header").html(tt);
         $("#form-holder [valed]").removeAttr('valed');
         $("#form-holder .do-clear").attr('remove', 1);
         
         $("#form-holder .go_button cufon").remove();
         $("#form-holder .go_button span span :not(i)").remove();
         $("#form-holder .go_button span i").after( sb );
         
         //alert(sb);
   			Cufon.refresh('#form-holder .header');
		 
         $(".formError").remove();
         
         $("#form-holder").hide();
         
         to_slide_up = ($(".comment #form-holder").length ? $("#form-holder") : $(".share_com"));
         if (to_slide_up.hasClass('share_com')) $("#form-holder").show();
         
         to_slide_up.slideDown(500);
         
         if (ee.attr("id") != "form_prev_holder")
         {
            var eid = ee.parent().attr("id");
            if (!eid)
               eid = "";
            $("#comment_parent").val( eid.replace('comment-', '') );
         }
         else
         {
            $("#comment_parent").val("0");
         }
         
         update_form_validation();
      });
}
jQuery(function () {
   $(".comments").click(function () {
      move_form_to( $(this).parent().parent() );
      return false;
   });
});
// end comments form


// PIE
/*jQuery(function () {
    $('.alignleft, #about, .textwidget-photo, .partner-bg, .alignleft, .alignright, .aligncenter, .shadow-light, .reviews-t, #aside .twit .reviews-t, .blockquote-bg, .navigation, .map, .navig-category, .slider-shprtcode, .toggle, .basic .accord, .shadow_dark, .shadow_dark i, .caption-head, .text-capt, .twit .reviews-t, #footer .flickr .alignleft, .contact-block, .shadow_light, .alignright, ul.tab-tab li').each(function() {
        if ($.browser.msie) PIE.attach(this);
    });
});*/
// end PIE

/*Highslide*/

hs.showCredits = 0;
		hs.padToMinWidth = true;		
		//hs.align = 'center';
		if (hs.registerOverlay) {
			// The white controlbar overlay
			hs.registerOverlay({
				thumbnailId: 'thumb3',
				overlayId: 'controlbar',
				position: 'top right',
				hideOnMouseOut: true
			});
			// The simple semitransparent close button overlay
			hs.registerOverlay({
				thumbnailId: 'thumb2',
				html: '<div class="closebutton"	onclick="return hs.close(this)" title="Close"></div>',
				position: 'top right',
				fade: 2 // fading the semi-transparent overlay looks bad in IE
			});
		}
		
		// ONLY FOR THIS EXAMPLE PAGE!
		// Initialize wrapper for rounded-white. The default wrapper (drop-shadow)
		// is initialized internally.
		if (hs.addEventListener && hs.Outline) hs.addEventListener(window, 'load', function () {
			new hs.Outline('rounded-white');
			new hs.Outline('glossy-dark');
		});
		
		// The gallery example on the front page
		var galleryOptions = {
			slideshowGroup: 'gallery',
			wrapperClassName: 'dark',
			//outlineType: 'glossy-dark',
			dimmingOpacity: 0.8,
			align: 'center',
			transitions: ['expand', 'crossfade'],
			fadeInOut: true,
			wrapperClassName: 'borderless floating-caption',
			marginLeft: 100,
			marginBottom: 80
		};
		
		if (hs.addSlideshow) hs.addSlideshow({
			slideshowGroup: 'gallery',
			interval: 5000,
			repeat: false,
			useControls: true,
			overlayOptions: {
				className: 'text-controls',
				position: 'bottom center',
				relativeTo: 'viewport',
				offsetY: -60
			}/*,
			thumbstrip: {
				position: 'left top',
				mode: 'vertical',
				relativeTo: 'viewport'
			}
		*/
		});
		hs.Expander.prototype.onInit = function() {
			hs.marginBottom = (this.slideshowGroup == 'gallery') ? 150 : 15;
		}
		
		// focus the name field
		hs.Expander.prototype.onAfterExpand = function() {
		
			if (this.a.id == 'contactAnchor') {
				var iframe = window.frames[this.iframe.name],
					doc = iframe.document;
				if (doc.getElementById("theForm")) {
					doc.getElementById("theForm").elements["name"].focus();
				}
		
			}
		}
		
		
		// Not Highslide related
		function frmPaypalSubmit(frm) {
			if (frm.os0.value == '') {
				alert ('Please enter your domain name');
				return false;
			}
			return true;
		}


// Footer
//footer
$(function () {
	///
	$(window).resize(function () {
		h = $(window).height() - $("#top-bg").height() - $("#header").height()- $("#slide").height()- $("#container").height();
		$("#container").css('min-height', h+"px");
	});
	$(window).trigger("resize");
});


jQuery(function () {
	//console.log($('#header nav').width() - $('#nav').width());
	$('.soc-ico').css({
		'max-width' : $('#header nav').width() - $('#nav').width() - 20 +'px'
	})
});

/*Gallery switch two column*/

jQuery(function(){ 

	function portfolio_add_cufon() {
		$('.textwidget .info > .head').each(function(){
			$(this).clone().prependTo($(this).parent()).removeClass("head").addClass("hide-me");
		})		
		cufon_in_gall();
	}
	function portfolio_add_zoom() {
		$('.widget-info').each(function(){
			$('a.photo', this).append('<i class="widget-inf"></i>').each(function () {
			  var $span = $('>i.widget-inf', this);
			 if ($.browser.msie && $.browser.version < 9)
				$span.hide();
			 else
				$span.css('opacity', 0);
			  $(this).hover(function () {
				if ($.browser.msie && $.browser.version < 9)
				  $span.show();
				else
				$span.stop().fadeTo(500, 1);
			  }, function () {
				if ($.browser.msie && $.browser.version < 9)
				  $span.hide();
				else
				  $span.stop().fadeTo(500, 0);
			  });
			});
		});
	}
	
	function list_to_grid() {	
			$(".gallery-inner").fadeOut("fast", function() {
				$(this).fadeIn("fast").addClass("w-i");
				$('.textwidget:first', this).removeClass('first')
				$('.textwidget.text', this).each(function(){
					$(this).removeClass('text');
					$('.info', this).each(function () {
						$(this).wrap("<div class='widget-info'></div>");
					});						
					$('.textwidget-photo', this).each(function() {
						$(this).clone(true).prependTo($(this).parent(".textwidget").find(".widget-info"))
					});
					$('.widget-info .info a.button', this).removeClass("button").addClass('details');
				});
				portfolio_add_zoom()
				
			});			
		return false;
	}
	
	function grid_to_list() {
		  $(".gallery-inner").fadeOut("fast", function() {
			$(this).fadeIn("fast").addClass("t-l");
			$('.textwidget:first', this).addClass('first')
			$('.textwidget', this).each(function(){			
				$(this).addClass('text')
				$(this).append( $('.widget-info > .info', this))
				$('.widget-info', this).remove();
				$('.info a.details', this).removeClass("details").addClass('button')				
			  });
			  Cufon.refresh('.textwidget > .info > .head');
		  })		
		return false;
	}
	
	portfolio_add_cufon();
	
	$('.navig-category').delegate('a.button.categ.td:not(.act)' , 'click' , function(){
		$("a.button.categ.td").addClass("act"); 
		$("a.button.categ.list").removeClass("act");
		
		list_to_grid();	
	});
	
	$('.navig-category').delegate('a.button.categ.list:not(.act)' , 'click' , function(){
		$("a.button.categ.list").addClass("act");
		$("a.button.categ.td").removeClass("act"); 
		grid_to_list();
	});
	
	if( $("a.button.categ.list").length || $("a.button.categ.td").length ) {
		if( "#grid" == window.location.hash ) {
			list_to_grid();	
			$("a.button.categ.td").addClass("act"); 
			$("a.button.categ.list").removeClass("act");
		}else if( "#list" == window.location.hash ) {
			grid_to_list();
			$("a.button.categ.list").addClass("act");
			$("a.button.categ.td").removeClass("act");
		}
	}
});

/*Gallery switch three column*/

jQuery(function(){
	function portfolio_add_zoom() {
		$('.widget-info').each(function(){
			$('a.photo', this).append('<i class="widget-inf"></i>').each(function () {
			  var $span = $('>i.widget-inf', this);
			 if ($.browser.msie && $.browser.version < 9)
				$span.hide();
			 else
				$span.css('opacity', 0);
			  $(this).hover(function () {
				if ($.browser.msie && $.browser.version < 9)
				  $span.show();
				else
				$span.stop().fadeTo(500, 1);
			  }, function () {
				if ($.browser.msie && $.browser.version < 9)
				  $span.hide();
				else
				  $span.stop().fadeTo(500, 0);
			  });
			});
		});
	}
	function list_to_hovergrid() {	 
		$(".gallery-inner").fadeOut("fast", function() {
			$(this).fadeIn("fast").addClass("t-l");
			$('.textwidget:first', this).addClass('first')
			$('.textwidget', this).each(function(){			
				$(this).addClass("one-fourth")
				$(this).append( $('.widget-info > .info', this))
				$('.widget-info', this).remove();
				$('.info a.details', this).removeClass("details").addClass('button')	
			});
			portfolio_add_zoom()			  
		});
		return false;
	};
	
	function hovergrid_to_list() {
		$(".gallery-inner").fadeOut("fast", function() {
			$(this).fadeIn("fast").addClass("w-i");
			$('.textwidget:first', this).removeClass('first')
			$('.textwidget.one-fourth', this).each(function(){
				$(this).removeClass("one-fourth")							
				$('.info', this).each(function () {
					$(this).wrap("<div class='widget-info'></div>");				
				});			
				$('.textwidget-photo', this).each(function() {
					$(this).clone(true).prependTo($(this).parent(".textwidget").find(".widget-info"))
				});					
				$('.widget-info .info a.button', this).removeClass("button").addClass('details')			
			});		
			portfolio_add_zoom()				  
		});
		return false;
	}
	$('.navig-category').delegate('a.button.categ.td-three:not(.act)' , 'click' , function(){
		$("a.button.categ.td-three").addClass("act"); 
		$("a.button.categ.list-three").removeClass("act"); 
		hovergrid_to_list();
	});
	
	$('.navig-category').delegate('a.button.categ.list-three:not(.act)' , 'click' , function(){
		$("a.button.categ.list-three").addClass("act");
		$("a.button.categ.td-three").removeClass("act"); 
		list_to_hovergrid();	
	});
	if( $("a.button.categ.list-three").length || $("a.button.categ.td-three").length ) {
		if( "#grid" == window.location.hash ) {
			$("a.button.categ.td-three").addClass("act"); 
			$("a.button.categ.list-three").removeClass("act"); 
			hovergrid_to_list();
		}else if( "#list" == window.location.hash ) {
			$("a.button.categ.list-three").addClass("act");
			$("a.button.categ.td-three").removeClass("act"); 
			list_to_hovergrid();	
		}
	}
});


/*Gallery switch full three column*/
jQuery(function(){
	function portfolio_add_zoom() {
		$('.widget-info').each(function(){
			$('a.photo', this).append('<i class="widget-inf"></i>').each(function () {
			  var $span = $('>i.widget-inf', this);
			 if ($.browser.msie && $.browser.version < 9)
				$span.hide();
			 else
				$span.css('opacity', 0);
			  $(this).hover(function () {
				if ($.browser.msie && $.browser.version < 9)
				  $span.show();
				else
				$span.stop().fadeTo(500, 1);
			  }, function () {
				if ($.browser.msie && $.browser.version < 9)
				  $span.hide();
				else
				  $span.stop().fadeTo(500, 0);
			  });
			});
		});
	}
	function full_hovergrid_to_list() {
		$(".gallery-inner").fadeOut("fast", function() {
			$(this).fadeIn("fast").addClass("t-l");
			$('.textwidget:first', this).addClass('first');
			$('.textwidget', this).each(function(){			
				$(this).addClass("one-third")
				$(this).append( $('.widget-info > .info', this))
				$('.widget-info', this).remove();
				$('.info a.details', this).removeClass("details").addClass('button')	
			});					  
		});
		return false;
	};
	function full_list_to_hovergrid() {
		$(".gallery-inner").fadeOut("fast", function() {
			$(this).fadeIn("fast").addClass("w-i");
			$('.textwidget:first', this).removeClass('first');
			$('.textwidget.one-third', this).each(function(){
				$(this).removeClass("one-third")						
				$('.info', this).each(function () {
					$(this).wrap("<div class='widget-info'></div>");
				});						
				$('.textwidget-photo', this).each(function() {
					$(this).clone(true).prependTo($(this).parent(".textwidget").find(".widget-info"))
				});					
				$('.widget-info .info a.button', this).removeClass("button").addClass('details');			
			});
			portfolio_add_zoom()				  
		});
		return false;
	}
	$('.navig-category').delegate('a.button.categ.td-full-third:not(.act)' , 'click' , function(){
		$("a.button.categ.td-full-third").addClass("act"); 
		$("a.button.categ.list-full-third").removeClass("act"); 
		full_list_to_hovergrid();
	});
	
	$('.navig-category').delegate('a.button.categ.list-full-third:not(.act)' , 'click' , function(){
		$("a.button.categ.list-full-third").addClass("act");
		$("a.button.categ.td-full-third").removeClass("act"); 
		full_hovergrid_to_list();
	});
	if( $("a.button.categ.list-full-third").length || $("a.button.categ.td-full-third").length ) {
		if( "#grid" == window.location.hash ) {
			$("a.button.categ.td-full-third").addClass("act"); 
			$("a.button.categ.list-full-third").removeClass("act"); 
			full_list_to_hovergrid();
		}else if( "#list" == window.location.hash ) {
			$("a.button.categ.list-full-third").addClass("act");
			$("a.button.categ.td-full-third").removeClass("act"); 
			full_hovergrid_to_list();	
		}
	}
})


function initiate_parallax() {
	$(window).resize(function () {
		var parallax_holder = $('#parallax');
		var parallax_holder_w = parallax_holder.width();
		var parallax_holder_h = parallax_holder.height();
		$('> li:nth-child(1)', parallax_holder).css({'width' : parallax_holder_w+30+'px' , 'height' : parallax_holder_h+20+'px' , 'left' : '-15px'});
		$('> li:nth-child(2)', parallax_holder).css({'width' : parallax_holder_w+50+'px' , 'height' : parallax_holder_h+40+'px' , 'left' : '-25px'});
		$('> li:nth-child(3)', parallax_holder).css({'width' : parallax_holder_w+90+'px' , 'height' : parallax_holder_h+60+'px' , 'left' : '-45px'});
		$('> li:nth-child(4)', parallax_holder).css({'width' : parallax_holder_w+130+'px' , 'height' : parallax_holder_h+80+'px' , 'left' : '-65px'});
	  	$('> li', parallax_holder).parallax({frameDuration:  15});
	});
	$(window).trigger("resize");
}