.postTabs_divs{
	padding: 4px;	
}


.postTabs_titles{
	display:none;	
}

ul.postTabs
	{
	margin:0px 0px 1em !important;
	padding: 0.2em 1em 0.2em <?php if ($postTabs_options["align"]=="center") echo "0px"; else echo "20px"; ?> !important;
	border-bottom: 1px solid <?php echo $postTabs_options["line"]; ?> !important;
	font-size: 11px;
	list-style-type: none !important;
	line-height:normal;
	text-align: <?php echo $postTabs_options["align"]; ?>;
	display: block !important;
	background: none;
	}

ul.postTabs li
	{	
	display: inline !important;
	font-size: 11px;
	line-height:normal;
	background: none;
	padding: 0px;
	margin: 0px;
	}
  
ul.postTabs li:before{
content: none;	
}  
  	
ul.postTabs li a
	{
	text-decoration: none;
	background: <?php echo $postTabs_options["inactive_bg"]; ?>;
	border: 1px solid <?php echo $postTabs_options["line"]; ?>  !important;
	padding: 0.2em 0.4em !important;
	color: <?php echo $postTabs_options["inactive_font"]; ?> !important;
	outline:none;	
	cursor: pointer;
	
	}
	
ul.postTabs li.postTabs_curr a{
	border-bottom: 1px solid <?php echo $postTabs_options["active_bg"]; ?>  !important;
	background: <?php echo $postTabs_options["active_bg"]; ?>;
	color: <?php echo $postTabs_options["active_font"]; ?> !important;
	text-decoration: none;
	
	}

ul.postTabs li a:hover
	{
	color: <?php echo $postTabs_options["over_font"]; ?> !important;
	background: <?php echo $postTabs_options["over_bg"]; ?>;
	text-decoration: none;
	
	}

.postTabsNavigation{
	display: block !important;
	overflow:hidden;
}

.postTabs_nav_next{
	float:right;
}

.postTabs_nav_prev{
	float:left;
}
