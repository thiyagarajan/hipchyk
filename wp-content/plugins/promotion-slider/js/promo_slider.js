// Function returns a random number between 1 and n
function rand(n){return(Math.floor(Math.random()*n+1));}

jQuery(document).ready(function(){
 
  // Disable HTML title attribute for slider images
  jQuery('.promo_slider img').removeAttr('title');
 
  // Get all instances of promo_slider on the page
  var sliders = jQuery('.promo_slider_wrapper');
  
  // Cycle through each slider
  jQuery.each(sliders, function(){
	
	// Define current slider
	var currentSlider = jQuery(this);
	var thisSlider = jQuery('.promo_slider', currentSlider);
	
	// Get all panels
    var panels = jQuery('.panel', thisSlider);
	
	// Get total count of panels
    var panelCount = panels.length;
	
	// Set number for first panel
	if( promo_slider_options.startOn == 'first' ) var initialPanel = 1;
	else var initialPanel = rand(panelCount);
	if( currentSlider.hasClass('random') ) initialPanel = rand(panelCount);
	if( currentSlider.hasClass('first') ) initialPanel = 1;
	
	// Should we pause the slider on mouseover?
	var pauseOnMouseover;
	if( currentSlider.hasClass('pause') ) pauseOnMouseover = true;
	else pauseOnMouseover = false;
	
	// Assign variable for setInterval
	var sliderInterval;
	
	// Set time delay
	var timeDelay = promo_slider_options.timeDelay;
	if( jQuery('.promo_slider_time_delay', thisSlider).html() ){
		timeDelay = jQuery('.promo_slider_time_delay', thisSlider).html();
	}
	
	// Set auto advance variable
	var autoAdvance = promo_slider_options.autoAdvance;
	if( thisSlider.hasClass('auto_advance') ) autoAdvance = true;
	if( thisSlider.hasClass('no_auto_advance') ) autoAdvance = false;
	if( panelCount < 2 ) autoAdvance = false;
	
	// Set navigation option
	var navOption = promo_slider_options.nav_option;
	if( currentSlider.hasClass('default_nav') ) navOption = 'default';
	else if( currentSlider.hasClass('fancy_nav') ) navOption = 'fancy';
	else if( currentSlider.hasClass('links_nav') ) navOption = 'links';
	else if( currentSlider.hasClass('thumb_nav') ) navOption = 'thumb';
	else navOption = false;
	
	// Hide all panels
	panels.hide();
	
	// Show initial panel and add class 'current' to active panel
	jQuery('.panel-' + initialPanel, currentSlider).show().addClass('current');
	
	if(panelCount > 1 && (navOption == 'default' || navOption == 'fancy' || navOption == 'thumb') ){

	  jQuery('.promo_slider_nav').show();
	  jQuery('.promo_slider_thumb_nav').show();
	
	  if(navOption == 'fancy' || navOption == 'default'){
		  // Generate HTML for navigation
		  var navHTML = '';
		  jQuery.each(panels, function(index, object){
			// Set panel title
			panelTitle = jQuery('.panel-'+(index+1)+' span.panel-title', currentSlider).html();
			  newSpan = '<span class="'+(index+1)+'" title="'+panelTitle+'">'+(index+1)+'</span>';
			  if( (index + 1) != panelCount){newSpan = newSpan + '<b class="promo_slider_sep"> | </b>';}
			navHTML = navHTML + newSpan;
		  });
		  
		  // Insert HTML into nav
		  jQuery('.slider_selections', currentSlider).html(navHTML);
	  }
	  
	  // Set click functions for each span in the slider nav
	  var slideNav = jQuery('.slider_selections span', currentSlider);
	  jQuery.each(slideNav, function(index, object){
		jQuery(object).click(function(){ 
		  clearInterval(sliderInterval);
		  if( !jQuery(object).hasClass('current') ) progress(jQuery(object).attr('class'), currentSlider, panelCount);
		  if(autoAdvance) sliderInterval = setInterval(function(){progress('forward', currentSlider, panelCount);}, (timeDelay * 1000));
		});
	  });
	  
	  // Set active span class to 'current'
	  jQuery('.slider_selections span[class=' + initialPanel + ']', currentSlider).addClass('current');
	  
	}
	
	// Create click functions for navigational elements
	jQuery('.move_forward', currentSlider).click(function(){ 
	  clearInterval(sliderInterval);
	  progress('forward', currentSlider, panelCount);
	  if(autoAdvance) sliderInterval = setInterval(function(){progress('forward', currentSlider, panelCount);}, (timeDelay * 1000));
	});
	jQuery('.move_backward', currentSlider).click(function(){
	  clearInterval(sliderInterval);
	  progress('backward', currentSlider, panelCount);
	  if(autoAdvance) sliderInterval = setInterval(function(){progress('forward', currentSlider, panelCount);}, (timeDelay * 1000));
	});
	
	
	if( autoAdvance ){
	
		// Begin auto advancement of slides
		sliderInterval = setInterval(function(){progress('forward', currentSlider, panelCount);}, (timeDelay * 1000));
	
		if( pauseOnMouseover ){
		
			// Pause slide advancement on mouseover
			jQuery(thisSlider).mouseover(function(){
				clearInterval(sliderInterval);
			});
		
			// Continue slide advancement on mouseout
			jQuery(thisSlider).mouseout(function(){
				sliderInterval = setInterval(function(){progress('forward', currentSlider, panelCount);}, (timeDelay * 1000));
			});
		
		}
		
	}
	
  });
  
  // Progress to selected panel
  function progress(value, currentSlider, panelCount){
	  
	  // Find number of current panel
	  var currentValue = jQuery('div.promo_slider > .panel', currentSlider).index(jQuery('div.panel.current', currentSlider)) + 1;

	  // Set value of new panel
  	  if(value == 'forward'){
		var newValue = currentValue + 1;
		if(newValue > panelCount){newValue = 1;}
	  }
	  else if(value == 'backward'){
		var newValue = currentValue - 1;
		if(newValue == 0){newValue = panelCount;}
	  }
	  else{
		var newValue = value;
	  }
	  
	  // Assign variables for ease of use
	  var currentItem = jQuery('.panel-' + currentValue, currentSlider);
	  var newItem = jQuery('.panel-' + newValue, currentSlider);
	  var currentSpan = jQuery('.slider_selections span.current', currentSlider);
	  var newSpan = jQuery('.slider_selections span.' + newValue, currentSlider);
	  
	  // Add / Remove classes
	  currentItem.removeClass('current');
	  newItem.addClass('current');
	  currentSpan.removeClass('current');
	  newSpan.addClass('current');
	  
	  // Fade in / out
	  currentItem.fadeOut('fast', function(){
		newItem.fadeIn('fast');
	  });

  }

});