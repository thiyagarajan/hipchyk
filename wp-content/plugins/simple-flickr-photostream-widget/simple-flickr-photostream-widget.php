<?php
/*
Plugin Name: Simple Flickr Photostream
Plugin URI: http://www.ai-development.com/wordpress-plugins/simple-flickr-photostream-widget
Description: Display a Flickr Photostream in any widgetized area
Author: Benoit Gilloz
Version: 1.3.7
Author URI:http://www.ai-development.com/
*/

class Sfps_init{
	
	function Sfps_init(){
	
		if(!get_option('sfps_cache')){
			add_option('sfps_cache', array());
		}

		if($sfps_cache = get_option('sfps_cache'))
			if($sfps_cache['expire'] <= time())
				$this->sfps_delete_cache();

		/* Add our function to the widgets_init hook. */
		add_action( 'widgets_init', array(&$this, 'sfps_widget_register'));
		
		/* Add javascript include in admin pages */
		add_action('admin_head', array(&$this, 'simple_flickr_admin_head'));
	}

	function simple_flickr_admin_head(){ ?>

		<script type="text/javascript">
			function sfps_toggleCache(a,b){jQuery("#"+a).is(":checked")?jQuery("#"+b).show():jQuery("#"+b).hide()};
			function sfps_toggleSource(a){if(jQuery("#"+a).val()=="user"){jQuery("#"+a).parent().nextAll("p.set_parent").hide();jQuery("#"+a).parent().nextAll("p.id_parent").show();jQuery("#"+a).parent().nextAll("p.tags_parent").show()}if(jQuery("#"+a).val()=="set"){jQuery("#"+a).parent().nextAll("p.set_parent").show();jQuery("#"+a).parent().nextAll("p.id_parent").show();jQuery("#"+a).parent().nextAll("p.tags_parent").hide()}if(jQuery("#"+a).val()=="favorite"){jQuery("#"+a).parent().nextAll("p.set_parent").hide(); jQuery("#"+a).parent().nextAll("p.id_parent").show();jQuery("#"+a).parent().nextAll("p.tags_parent").hide()}if(jQuery("#"+a).val()=="group"){jQuery("#"+a).parent().nextAll("p.set_parent").hide();jQuery("#"+a).parent().nextAll("p.id_parent").show();jQuery("#"+a).parent().nextAll("p.tags_parent").hide()}if(jQuery("#"+a).val()=="public"){jQuery("#"+a).parent().nextAll("p.set_parent").hide();jQuery("#"+a).parent().nextAll("p.id_parent").hide();jQuery("#"+a).parent().nextAll("p.tags_parent").show()}};
			function sfps_restoreDefault(id)
			{
				jQuery("#"+id).val(window.sfps_template);
			}
		</script>

	<?php
	}

	/* Function that registers our widget. */
	function sfps_widget_register() {
		register_widget( 'Simple_Flickr_Photostream' );
	}
	
	function sfps_delete_cache(){
		$cache_all = get_option('sfps_cache');

		foreach($cache_all as $widgetid => $cache){
			if($widgetid == 'expire') continue;
			
			foreach($cache as $pic){
				foreach($pic['cache_real_path'] as $picpath){

					chmod($picpath, 0777);
					#make sure we are talking about a file here, don't want to delete random stuff or worst
					if(is_file($picpath)){
						unlink($picpath);
					}
				}
			}
		}
		update_option('sfps_cache', array());
	}	
}

class Simple_Flickr_Photostream extends WP_Widget {

	function Simple_Flickr_Photostream() {
		
		$this->defaultTemplate  = '<li class="picture-item %classes%">';
		$this->defaultTemplate .=	'<a href="%flickr_page%" title="%title% by %author_name%">';
		$this->defaultTemplate .=		'<img src="%image_square%" alt="%title% by %author_name%"/>';
		$this->defaultTemplate .=	'</a>';
		$this->defaultTemplate .= '</li>';
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'simple-flickr-photostream', 'description' => 'Display a Flickr Photostream' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 500);
		/* Create the widget. */
		$this->WP_Widget( 'Flickr_Photostream-widget', 'Simple Flickr Photostream', $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$type = $instance['type'];
		$tags = $instance['tags'];
		$set = $instance['set'];
		$id = $instance['id'];
		$do_cache = $instance['do_cache'];
		$cache_sizes = $instance['cache_sizes'];
		$cache_path = $instance['cache_path'];
		$cache_uri = $instance['cache_uri'];
		$num_items = $instance['num_items'];
		$before_list = $instance['before_list'];
		$html = $instance['html'];
		$after_list = $instance['after_list'];

		$count = 0;

		$sfps_cache = get_option('sfps_cache');

		if($do_cache && !empty($sfps_cache[$this->id])){
			$pix = $sfps_cache[$this->id];
		}else{

			if (!($rss = $this->getRSS($instance)))
			{
				echo "no content";
				return;
			}

			$pix = array();

			if($num_items != "random")
			{
				$items = array_slice($rss['items'], 0, $num_items);
			}
			else
			{
				$rand_keys = array_rand($rss['items'], 1);
				$items = array($rss['items'][$rand_keys]);
			}
			
			# builds html from array
			foreach ( $items as $item ) {
				
				$count++;
				
				$baseurl = str_replace("_m.jpg", "", $item["m_url"]);
				$thumbnails = array(
					'small' => $baseurl . "_m.jpg",
					'square' => $baseurl . "_s.jpg",
					'thumbnail' => $baseurl . "_t.jpg",
					'medium' => $baseurl . ".jpg",
					'large' => $baseurl . "_b.jpg"
				);
				
				#check if there is an image title (for html validation purposes)
				if($item['title'] !== "")
					$pic_title = htmlspecialchars(stripslashes($item['title']));
				else
					$pic_title = $default_title;

				$pic_url = $item['url'];
				$author_name = $item['author_name'];
				$author_profile = $item['author_url'];

				$cachePath = trailingslashit($cache_uri);
				$fullPath = trailingslashit($cache_path);

				$pic_real_path = '';

				#build array with pix path and if applicable, cache them
				foreach ($thumbnails as $size => $thumbnail) {

					if (
							is_array($cache_sizes) &&
							in_array($size, $cache_sizes) &&
							$do_cache &&
							$cachePath &&
							$fullPath
					) {
						
						preg_match('<http://farm[0-9]{0,3}\.staticflickr\.com/\d+?\/([^.]*)\.jpg>', $thumbnail, $flickrSlugMatches);
						$flickrSlug = $flickrSlugMatches[1];

						if (!file_exists("$fullPath$flickrSlug.jpg")) {
							
							$localimage = fopen("$fullPath$flickrSlug.jpg", 'wb');
							$remoteimage = wp_remote_fopen($thumbnail);
							$iscached = fwrite($localimage,$remoteimage);
							fclose($localimage);
							
						} else {
							$iscached = true;
						}
						
						if($iscached){
							$thumbnail = "$cachePath$flickrSlug.jpg";
							$pic_real_path[$size] = "$fullPath$flickrSlug.jpg";
						}
					}
					$cache_pic[$size] = $thumbnail;
				}

				$pix[] = array(
					'title' => $pic_title,
					'author_name' => $author_name,
					'author_url' => $author_profile,
					'url' => $pic_url,
					'cache' => $cache_pic,
					'cache_real_path' => $pic_real_path
				);

			}

			#if do_cache set then save that nice array in a transcient
			if($do_cache){
				$cache_all = get_option('sfps_cache');
				$cache_all[$this->id] = $pix;
				$cache_all['expire'] = time()+(60*60);
				update_option('sfps_cache', $cache_all);
			}
		}

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;

		echo stripslashes($before_list);

		//echo '<pre>'.print_r($pix, true).'</pre>';

		$count = 0;
		#array of pictures
		foreach($pix as $pic){

			$count++;
			$toprint = stripslashes($html);

			if(strpos($toprint, "%classes%")){
				$classes = 'item-'.$count;
				if($count == 1)
					$classes .= ' first';
				//If last element, add class 'last'
				if($count == $num_items)
					$classes .= ' last';
				$toprint = str_replace("%classes%", $classes, $toprint);
			}

			$toprint = str_replace("%flickr_page%", $pic['url'], $toprint);
			$toprint = str_replace("%title%", $pic['title'], $toprint);
			$toprint = str_replace("%author_name%", $pic['author_name'], $toprint);
			$toprint = str_replace("%author_url%", $pic['author_url'], $toprint);

			foreach($pic['cache'] as $size => $path){
				$toprint = str_replace("%image_".$size."%", $path, $toprint);
			}

			echo $toprint;
		}

		echo stripslashes($after_list);

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		if(empty($new_instance['cache_sizes']))
			$new_instance['cache_sizes'] = array('square');

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['type'] = strip_tags( $new_instance['type']);
		$instance['tags'] = strip_tags( $new_instance['tags']);
		$instance['set'] = strip_tags( $new_instance['set']);
		$instance['id'] = strip_tags( $new_instance['id']);
		$instance['do_cache'] = strip_tags( $new_instance['do_cache']);
		$instance['cache_sizes'] = $new_instance['cache_sizes'];
		$instance['cache_path'] = strip_tags( $new_instance['cache_path']);
		$instance['cache_uri'] = strip_tags( $new_instance['cache_uri']);
		$instance['num_items'] = strip_tags( $new_instance['num_items']);
		$instance['before_list'] = $new_instance['before_list'];
		$instance['html'] = $new_instance['html'];
		$instance['after_list'] = $new_instance['after_list'];

		#when the transient is deleted, also delete the pictures
		//add_action('delete_transient_sfps_cache','sfps_delete_cache');
		global $sfps_instance;
		$sfps_instance->sfps_delete_cache();

		return $instance;
	}

	function form( $instance ) {

		$uploaddir = wp_upload_dir();

		/* Set up some default widget settings. */
		$defaults = array(
				'title' => 'Flickr Photostream',
				// The type of Flickr images that you want to show. Possible values: 'user', 'favorite', 'set', 'group', 'public'
				'type' => 'public',
				// Optional: To be used when type = 'user' or 'public', comma separated
				'tags' => '',
				// Optional: To be used when type = 'set'
				'set' => '',
				// Optional: Your Group or User ID. To be used when type = 'user' or 'group'
				'id' => '',
				// Do you want caching?
				'do_cache' => false,
				// The image sizes to cache locally. Possible values: 'square', 'thumbnail', 'small', 'medium' or 'large', provided within an array
				'cache_sizes' => array('square'),
				// Where images are saved (Server path)
				'cache_path' => $uploaddir['basedir'].'',
				// The URI associated to the cache path (web address)
				'cache_uri' => $uploaddir['baseurl'].'',

				 // The number of thumbnails you want
				'num_items' => 4,
				 // the HTML to print before the list of images
				'before_list' => '<ul>',
				// the code to print out for each image. Meta tags available:
				// - %flickr_page%
				// - %title%
				// - %image_small%, %image_square%, %image_thumbnail%, %image_medium%, %image_large%
				'html' => $this->defaultTemplate,
				// the default title
				'default_title' => "Untitled Flickr photo",
				// the HTML to print after the list of images
				'after_list' => '</ul>');

		$instance = wp_parse_args( (array) $instance, $defaults );

?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'num_items' ); ?>">Display</label>
			<select name="<?php echo $this->get_field_name( 'num_items' ); ?>" id="<?php echo $this->get_field_id( 'num_items' ); ?>">
				<option <?php if ($instance['num_items'] == "random") { echo 'selected'; } ?> value="random">Random (1)</option>
				<?php for ($i=1; $i<=20; $i++) { ?>
					<option <?php if ($instance['num_items'] == $i) { echo 'selected'; } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<select onchange="javascript: sfps_toggleSource('<?php echo $this->get_field_id( 'type' ); ?>');" name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>">
				<option <?php if($instance['type'] == 'user') { echo 'selected'; } ?> value="user">user</option>
				<option <?php if($instance['type'] == 'set') { echo 'selected'; } ?> value="set">set</option>
				<option <?php if($instance['type'] == 'favorite') { echo 'selected'; } ?> value="favorite">favorite</option>
				<option <?php if($instance['type'] == 'group') { echo 'selected'; } ?> value="group">group</option>
				<option <?php if($instance['type'] == 'public') { echo 'selected'; } ?> value="public">community</option>
			</select>
			photos.
		</p>
		<p class="id_parent">
			<label for="<?php echo $this->get_field_id( 'id' ); ?>">User or Group ID</label>
			<input name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" id="<?php echo $this->get_field_id( 'id' ); ?>" value="<?php echo $instance['id']; ?>" size="20" />
		</p>
		<p class="set_parent">
			<label for="<?php echo $this->get_field_id( 'set' ); ?>">Set ID</label>
			<input name="<?php echo $this->get_field_name( 'set' ); ?>" type="text" id="<?php echo $this->get_field_id( 'set' ); ?>" value="<?php echo $instance['set']; ?>" size="40" />
			<small>Use number from the set url</small>
		</p>
		<p class="tags_parent">
			<label for="<?php echo $this->get_field_id( 'tags' ); ?>">Tags (optional)</label>
			<input class="widefat" name="<?php echo $this->get_field_name( 'tags' ); ?>" type="text" id="<?php echo $this->get_field_id( 'tags' ); ?>" value="<?php echo $instance['tags']; ?>" size="40" />
			<small>Comma separated, no spaces</small>
		</p>
		<script type="text/javascript">
			sfps_toggleSource('<?php echo $this->get_field_id( 'type' ); ?>');
		</script>
		<div>
			<p><label for="<?php echo $this->get_field_id( 'before_list' ); ?>">Before List:</label><br/><input class="widefat" name="<?php echo $this->get_field_name( 'before_list' ); ?>" type="text" id="<?php echo $this->get_field_id( 'before_list' ); ?>" value="<?php echo htmlspecialchars(stripslashes($instance['before_list'])); ?>" /></p>

			<p><label for="<?php echo $this->get_field_id( 'html' ); ?>">Item HTML:</label></p>
			<p>Allowed tags: <code>%flickr_page%</code>
				<code>%title%</code>
				<code>%author_name%</code>
				<code>%author_url%</code>
				<code>%image_square%</code>
				<code>%image_small%</code>
				<code>%image_thumbnail%</code>
				<code>%image_medium%</code>
				<code>%image_large%</code>
				<code title="(item number and 'first'/'last')">%classes%</code>
			</p>
			<p>
				<textarea name="<?php echo $this->get_field_name( 'html' ); ?>" type="text" id="<?php echo $this->get_field_id( 'html' ); ?>" style="width:400px;" rows="10"><?php echo htmlspecialchars(stripslashes($instance['html'])); ?></textarea>
				<script type="text/javascript">var sfps_template = '<?php echo $this->defaultTemplate ?>';</script>
				<br /><small><a href="javascript: sfps_restoreDefault('<?php echo $this->get_field_id( 'html' ); ?>');">restore default template</a></small>
			</p>


			<p><label for="<?php echo $this->get_field_id( 'after_list' ); ?>">After List:</label><br/> <input class="widefat" name="<?php echo $this->get_field_name( 'after_list' ); ?>" type="text" id="<?php echo $this->get_field_id( 'after_list' ); ?>" value="<?php echo htmlspecialchars(stripslashes($instance['after_list'])); ?>" /></p>
		</div>

		<div id="<?php echo $this->get_field_id( 'flickr-widget-misc' ); ?>">

			<p>Caching:</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'do_cache' ); ?>">Turn on caching:</label>
				<input name="<?php echo $this->get_field_name( 'do_cache' ); ?>" type="checkbox" id="<?php echo $this->get_field_id( 'do_cache' ); ?>" <?php echo $instance['do_cache']==1?'checked="checked"':''; ?> value="1"
					   onclick="javascript: sfps_toggleCache('<?php echo $this->get_field_id( 'do_cache' ); ?>', '<?php echo $this->get_field_id( 'flickr-widget-do_cache' ); ?>');"
					   />
			</p>

			<div id="<?php echo $this->get_field_id( 'flickr-widget-do_cache' ); ?>">

				<div>
					<p>Cache size(s):</p>
					<p><small>should be the same as the one(s) you are using in the html output</small></p>
					<ul>
						<?php
						$allowed_sizes = array('square', 'thumbnail', 'small', 'medium', 'large');

						foreach($allowed_sizes as $size): ?>
						<li>
							<label>
								<input type="checkbox"
								name="<?php echo $this->get_field_name( 'cache_sizes' ); ?>[]"
								value="<?php echo $size ?>"
								<?php if(in_array($size, $instance['cache_sizes'] )) echo 'checked="checked"' ?> />
								<?php echo $size ?>
							</label>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<p>
					<label for="<?php echo $this->get_field_id( 'cache_path' ); ?>">Cache path:</label>
					<input class="widefat" name="<?php echo $this->get_field_name( 'cache_path' ); ?>" type="text" id="<?php echo $this->get_field_id( 'cache_path' ); ?>" value="<?php echo $instance['cache_path']; ?>" />
				<p>
					<label for="<?php echo $this->get_field_id( 'cache_uri' ); ?>">Cache Uri:</label>
					<input class="widefat" name="<?php echo $this->get_field_name( 'cache_uri' ); ?>" type="text" id="<?php echo $this->get_field_id( 'cache_uri' ); ?>" value="<?php echo $instance['cache_uri']; ?>" />
				</p>
			</div>
			<script type="text/javascript">
				sfps_toggleCache('<?php echo $this->get_field_id( 'do_cache' ); ?>', '<?php echo $this->get_field_id( 'flickr-widget-do_cache' ); ?>');
			</script>
		</div>
		<?php
	}

	function getRSS($settings) {
		
		$format = "php_serial";
		
		if ($settings['type'] == "user") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $settings['id'] . '&tags=' . $settings['tags'] . '&format='.$format; }
		elseif ($settings['type'] == "favorite") { $rss_url = 'http://api.flickr.com/services/feeds/photos_faves.gne?id=' . $settings['id'] . '&format='.$format; }
		elseif ($settings['type'] == "set") { $rss_url = 'http://api.flickr.com/services/feeds/photoset.gne?set=' . $settings['set'] . '&nsid=' . $settings['id'] . '&format='.$format; }
		elseif ($settings['type'] == "group") { $rss_url = 'http://api.flickr.com/services/feeds/groups_pool.gne?id=' . $settings['id'] . '&format='.$format; }
		elseif ($settings['type'] == "public" || $settings['type'] == "community") { $rss_url = 'http://api.flickr.com/services/feeds/photos_public.gne?tags=' . $settings['tags'] . '&format='.$format; }
		else {
			print '<strong>No "type" parameter has been setup. Check your settings, or provide the parameter as an argument.</strong>';
			die();
		}			
		
		if(function_exists("curl_init"))	
		{
			$ch = curl_init(); // open curl session

			// set curl options
			curl_setopt($ch, CURLOPT_URL, $rss_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			$object = curl_exec($ch); // execute curl session
			curl_close($ch); // close curl session
		}
		else
		{
			$object = file_get_contents($rss_url, false);
		}

		$object = unserialize($object);

		return $object;
	}

}

/* Initialise outselves */
add_action('plugins_loaded', create_function('','global $sfps_instance; $sfps_instance = new Sfps_init();'));

?>
