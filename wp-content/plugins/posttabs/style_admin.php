	#postTabs_admin_preview{
		float:right;
		width: 400px;
		height:50px;
		border: 2px solid grey;
		background-color: <?php echo $postTabs_options["active_bg"]; ?>;
		margin-top:50px;
		padding:10px;
	}

	#postTabs_admin
		{
		margin:0px 0px 1em;
		padding: 0.2em 1em 0.2em 20px;
		border-bottom: 1px solid <?php echo $postTabs_options["line"]; ?>;
		font-size: 11px;
		list-style-type: none;
		text-align: <?php echo $postTabs_options["align"]; ?>;
		<?php if ($postTabs_options["align"]=="center") echo "padding-left:0px;"; ?>
		}

	#postTabs_admin li
		{	
		display: inline;
		font-size: 11px;
		line-height:normal;
		}
		
	#postTabs_admin_inactive 
		{
		text-decoration: none;
		background: <?php echo $postTabs_options["inactive_bg"]; ?>;
		border: 1px solid <?php echo $postTabs_options["line"]; ?>;
		padding: 0.2em 0.4em;
		color: <?php echo $postTabs_options["inactive_font"]; ?> ;
		outline:none;		
		}
		
	#postTabs_admin_active {
		
		background: <?php echo $postTabs_options["active_bg"]; ?>;
		color: <?php echo $postTabs_options["active_font"]; ?> ;
		border: 1px solid <?php echo $postTabs_options["line"]; ?>;
		border-bottom: 1px solid <?php echo $postTabs_options["active_bg"]; ?>;
		text-decoration: none;
		padding: 0.2em 0.4em;

		}

	#postTabs_admin_over 
		{
		color: <?php echo $postTabs_options["over_font"]; ?> ;
		background: <?php echo $postTabs_options["over_bg"]; ?>;
		border: 1px solid <?php echo $postTabs_options["line"]; ?>;
		text-decoration: none;
		padding: 0.2em 0.4em;

		}
