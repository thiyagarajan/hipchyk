<?php
/**
 * CataBlog Item Class
 *
 * This file contains the class for each CataBlog Item that is fetched from the database.
 * @author Zachary Segal <zac@illproductions.com>
 * @version 1.6.3
 * @package catablog
 */

/**
 * CataBlogItem, a container class for each catalog items with all the important 
 * properties, such as title, image, secondary images, etc. Variable names without
 * an underscore prefix are the post_parameters or values set by the user.
 *
 * @package catablog
 */
class CataBlogItem {
	
	// item properties that directly relate to 
	// values in the WordPress Database
	private $id           = null;
	private $title        = "";
	private $description  = "";
	private $date         = null;
	private $image        = "";
	private $sub_images   = array(); 
	private $order        = 0;
	private $link         = "";
	private $price        = 0;
	private $product_code = "";
	private $categories   = array();
	
	// object values not considered item properties
	// this will be skipped in getParameterArray() method
	private $_options            = array();
	private $_main_image_changed = false;
	private $_sub_images_changed = false;
	private $_old_images         = array();
	private $_wp_upload_dir      = "";
	private $_custom_post_name   = "";
	private $_custom_tax_name    = "";
	private $_post_meta_name     = "catablog-post-meta";
	private $_post_name          = "";
	
	// construction method
	public function __construct($post_parameters=null) {
		global $wp_plugin_catablog_class;
		$this->_custom_post_name = $wp_plugin_catablog_class->getCustomPostName();
		$this->_custom_tax_name  = $wp_plugin_catablog_class->getCustomTaxName();
		
		$this->_options = get_option('catablog-options');
		
		$wp_directories = wp_upload_dir();
		$this->_wp_upload_dir = $wp_directories['basedir'];
		
		if (isset($post_parameters)) {
			foreach ($this->getParameterArray() as $param_name) {
				if (isset($post_parameters[$param_name])) {
					$this->{$param_name} = $post_parameters[$param_name];
				}
			}
		}
	}




	/*****************************************************
	**       - FACTORY METHODS
	*****************************************************/




	/**
	 * Get a single catalog item by database id
	 *
	 * @param integer $id The id of the catalog item you wish to get.
	 * @return null|CataBlogItem
	 */
	public static function getItem($id) {
		$post = get_post($id);
		
		if ($post == false) {
			return null;
		}
		
		$item = new CataBlogItem();
		
		if ($post->post_type != $item->getCustomPostName()) {
			return null;
		}
		
		$category_ids = array();
		$terms = get_the_terms($post->ID, $item->_custom_tax_name);
		if (is_array($terms)) {
			foreach ($terms as $term) {
				$category_ids[$term->term_id] = $term->name;
			}			
		}
		
		$item->id           = $post->ID;
		$item->title        = $post->post_title;
		$item->description  = $post->post_content;
		$item->date         = $post->post_date;
		$item->categories   = $category_ids;
		$item->order        = $post->menu_order;
		$item->_post_name   = $post->post_name;
		
		$meta = get_post_meta($post->ID, $item->_post_meta_name, true);
		$item->processPostMeta($meta);
		
		return $item;
	}


	/**
	 * Convert a standard WordPress post object into a CataBlogItem
	 * object.
	 *
	 * @param object $post A standard WordPress post object
	 * @return null|CataBlogItem
	 */
	public static function postToItem($post) {
		if (empty($post)) {
			return null;
		}
		
		$item = new CataBlogItem;
		
		if ($post->post_type != $item->getCustomPostName()) {
			return null;
		}
		
		$category_ids = array();
		$terms = get_the_terms($post->ID, $item->_custom_tax_name);
		if (is_array($terms)) {
			foreach ($terms as $term) {
				$category_ids[$term->term_id] = $term->name;
			}			
		}				
		
		$item->id           = $post->ID;
		$item->title        = $post->post_title;
		$item->description  = $post->post_content;
		$item->date         = $post->post_date;
		$item->categories   = $category_ids;
		$item->order        = $post->menu_order;
		$item->_post_name   = $post->post_name;
		
		$meta = get_post_meta($post->ID, $item->_post_meta_name, true);
		$item->processPostMeta($meta);
		
		return $item;
	}



	/**
	 * Get just the id for every catalog item in the database. Returns
	 * an  array of integers, or possibly an empty array.
	 *
	 * @return array
	 */
	public static function getItemIds() {
		$items = array();
		
		$cata = new CataBlogItem();
		
		$params = array(
			'post_type'=> $cata->getCustomPostName(), 
			'orderby'=>'menu_order',
			'order'=>'ASC',
			'numberposts' => -1,
		);
		
		$posts = get_posts($params);
		$ids = array();
		foreach ($posts as $post) {
			$ids[] = $post->ID;
		}
		
		return $ids;
	}




	/**
	 * Get a collection of catalog items from the database. May
	 * possibly return an empty array.
	 *
	 * @param array $categories The array of category slugs to filter with. Will be ignored if false.
	 * @param string $operator  Operator used to test the categories filter. Possible values are 'IN', 'NOT IN' or 'AND'.
	 * @param string $sort The database field used to sort the collection. Possible values are 'title', 'order', 'date' or 'random'.
	 * @param string $order The order the collection should be returned in. Possible values are 'asc' or 'desc'.
	 * @param integer $offset The start ordinal of the collection, or number of catalog items to skip over.
	 * @param integer $limit The maximum amount of catalog items allowed in the collection.
	 * @param boolean $load_categories Wether to load the category ids for each item of the collection. Possible large database hit.
	 * @return array An array of CataBlogItem objects
	 */
	public static function getItems($categories=false, $operator='IN', $sort='menu_order', $order='asc', $offset=0, $limit=-1, $load_categories=true) {
		
		$items = array();
		
		$cata = new CataBlogItem();
		
		$params = array(
			'post_type'=> $cata->getCustomPostName(),
			'orderby'=> $sort,
			'order'=>$order,
			'offset'=>$offset,
			'numberposts' => $limit,
		);
		
		if (!empty($categories)) {
			
			$tax_query_array = array(
				array(
					'taxonomy' => $cata->getCustomTaxName(),
					'field'    => 'id',
					'terms'    => $categories,
					'operator' => $operator  // 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'
				)
			);
			$params['tax_query'] = $tax_query_array;
		}
		
		// $query = new WP_Query();
		// $query->query($params);
		// $posts = $query->posts;
		// echo $query->post_count;
		// echo $query->found_posts;

		$posts = get_posts($params);
		
		// return an array of CataBlogItems
		foreach ($posts as $post) {
			
			$item = new CataBlogItem();
			
			$item->id           = $post->ID;
			$item->title        = $post->post_title;
			$item->description  = $post->post_content;
			$item->date         = $post->post_date;
			$item->categories   = array();
			$item->order        = $post->menu_order;
			$item->_post_name   = $post->post_name;
			
			$item_cats = array();
			if ($load_categories) {
				$category_ids = array();
				$terms = get_the_terms($post->ID, $item->_custom_tax_name);
				if (is_array($terms)) {
					foreach ($terms as $term) {
						$category_ids[$term->term_id] = $term->name;
					}
				}
				$item->categories = $category_ids;
			}
			
			$meta = get_post_meta($post->ID, $item->_post_meta_name, true);
			$item->processPostMeta($meta);
			
			$items[] = $item;
		}
		
		return $items;
	}
	
	
	
	/**
	 * Get a collection of catalog items from the database using an array of ids.
	 * May possibly return an empty array.
	 *
	 * @param ids $ids The array of catalog item ids to be fetched from the database.
	 * @return array An array of CataBlogItem objects
	 */
	public static function getItemsByIds($ids) {
		
		$items = array();
		
		if (!is_array($ids) || empty($ids)) {
			$ids = array(-1);
		}
		$ids = array_unique($ids);
		
		$cata = new CataBlogItem();
		
		$params = array(
			'post_type'=> $cata->getCustomPostName(), 
			'post__in'=> $ids,
			'numberposts'=>-1,
		);

		$posts = get_posts($params);
		
		// return an array of CataBlogItems
		foreach ($posts as $post) {
			
			$item = new CataBlogItem();
			
			$item->id           = $post->ID;
			$item->title        = $post->post_title;
			$item->description  = $post->post_content;
			$item->date         = $post->post_date;
			$item->categories   = array();
			$item->order        = $post->menu_order;
			$item->_post_name   = $post->post_name;
			
			$item_cats = array();
			if (true) { // $load_categories
				$category_ids = array();
				$terms = get_the_terms($post->ID, $item->_custom_tax_name);
				if (is_array($terms)) {
					foreach ($terms as $term) {
						$category_ids[$term->term_id] = $term->name;
					}
				}
				$item->categories = $category_ids;
			}
			
			$meta = get_post_meta($post->ID, $item->_post_meta_name, true);
			$item->processPostMeta($meta);
			
			$items[$item->id] = $item;
		}
		
		return $items;
	}




	/*****************************************************
	**       - VALIDATE, SAVE & DELETE METHODS
	*****************************************************/




	/**
	 * This function will validate that the data being stored in the CataBlogItem
	 * object is safe to be saved to the database.
	 *
	 * @return boolean|string Wether or not all the objects parameters are valid.
	 */
	public function validate() {
		
		// catablog item must have an image associated with it
		if ($this->string_length($this->image) < 1) {
			return __('An item must have an image associated with it.', 'catablog');
		}
		
		// check that the originals directory exists and is writable
		$originals_directory = $this->_wp_upload_dir . "/catablog/originals";
		if (!is_writable($originals_directory)) {
			return __("Can't write uploaded image to server, please make sure the catablog directory in you WordPress uploads folder is writable.", 'catablog');
		}
		
		// check that the title is at least one character long
		if ($this->string_length($this->title) < 1) {
			return __('An item must have a title of at least one alphanumeric character.', 'catablog');
		}
		
		// check that the title is less then 200 characters long
		if ($this->string_length($this->title) > 200) {
			return __("An item's title can not be more then 200 characters long.", 'catablog');
		}
		
		// check that date value is a valid format
		if (!preg_match("/^\d{4}-\d{2}-\d{2} [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/", $this->date)) { 
			return __("An item's date must exactly match the MySQL date format, YYYY-MM-DD HH:MM:SS");
		}
		
		// check that the set date value is an actual day of the gregorian calendar
		$year  = substr($this->date,0,4);
		$month = substr($this->date,5,2);
		$day   = substr($this->date,8,2);
		if (!checkdate($month, $day, $year)) {
			return __("An item's date must be an actual day on the gregorian calendar.");
		}
		
		$hour  = (int) substr($this->date,11,2);
		if ($hour > 23) {
			return __("An item's date hour must be below twenty-four.");
		}
		
		// check that the item's order is a positive integer
		if (intval($this->order) < 1) {
			return __("An item's order value must be a positive integer.", 'catablog');
		}
		
		
		// check that the price is a positive number
		if ($this->string_length($this->price) > 0) {
			if (is_numeric($this->price) == false || $this->price < 0) {
				return __("An item's price must be a positive number.", 'catablog');
			}
		}
		
		return true;
	}




	/**
	 * This function is used to validate an uploaded image and see if it is
	 * acceptable for CataBlog usage.
	 *
	 * @param string $image A file path to an uploaded image.
	 * @return boolean|string Wether or not the image is an acceptable format.
	 */
	public function validateImage($image) {
		list($width, $height, $format) = @getimagesize($image);
		switch($format) {
			case IMAGETYPE_GIF: break;
			case IMAGETYPE_JPEG: break;
			case IMAGETYPE_PNG:	break;
			default: return __("The image could not be used because it is an unsupported format. JPEG, GIF and PNG formats only, please.", 'catablog');
		}
		
		// check if catablog is going over the storage space limit on multisite blogs
		if (function_exists('get_upload_space_available')) {
			if ($this->_main_image_changed) {
				$space_available = get_upload_space_available();
				$image_size      = filesize($this->image);
				if ($image_size > $space_available) {

					$space_available = round(($space_available / 1024 / 1024), 2);
					$image_size      = round(($image_size / 1024 / 1024), 2);

					$error  = __('Can not write uploaded image to server, your storage space is exhausted.', 'catablog') . '<br />';
					$error .= __('Please delete some media files to free up space and try again.', 'catablog') . '<br />';
					$error .= sprintf(__('You have %sMB of available space on your server and your image is %sMB.', 'catablog'), $space_available, $image_size);
					return $error;
				}				
			}
		}
		
		return true;
	}




	/**
	 * This function will save the current CataBlogItem object to the 
	 * database. If the object has an id set, it will update, otherwise
	 * it will create a new catalog item.
	 *
	 * @return boolean|string Wether or not the database write was successful.
	 */
	public function save() {
		
		
		$params = array();
		$params['post_title']   = $this->title;
		$params['post_name']    = sanitize_title($this->title);
		$params['post_content'] = $this->description;
		$params['menu_order']   = $this->order;
		$params['post_date']    = $this->date;
		$params['post_status']  = 'publish';
		
		if ($this->id > 0) {
			$params['ID'] = $this->id;
			$update = wp_update_post($params);
			if ($update == 0) {
				return false;
			}
		}
		else {
			$params['post_status']    = 'publish';
			$params['comment_status'] = 'closed';
			$params['post_type']      = $this->_custom_post_name;
			
			$this->id = wp_insert_post($params);
			if ($this->id === false) {
				return false;
			}
		}
		
		// if the image has been set after loading process image
		if ($this->_main_image_changed) {
			
			$filename   = $_FILES['new_image']['name'];
			$image_name = $this->unique_filename($filename);
			$move_path  = $this->_wp_upload_dir . "/catablog/originals/$image_name";
			
			// move file to originals folder and set the filename in the object
			$moved = move_uploaded_file($this->image, $move_path);
			$this->image = $image_name;
			
			// save the new image's title to the post meta
			$this->updatePostMeta();
			
			// generate a thumbnail for the new image
			$this->makeThumbnail();
			if ($this->_options['lightbox-render']) {
				$this->makeFullsize();
			}

			delete_transient('dirsize_cache'); // WARNING!!! transient label hard coded.
		}
		
		// update post meta
		$this->updatePostMeta();
		
		// update post terms
		// NOTE: this is the one instance where $this->categories should be an array on term ids, otherwise
		//       it is an array of term names keyed by term id.
		$terms_set = wp_set_object_terms($this->id, $this->categories, $this->_custom_tax_name);
		if ($terms_set instanceof WP_Error) {
			return __("Could not set categories, please try again.", 'catablog');
		}
		
		return true;
	}




	/**
	 * This function removes the current CataBlogItem object from the
	 * database. The object must have an id greater then zero to actually
	 * delete the catalog item from the database.
	 *
	 * @param boolean $remove_images Wether or not the catalog item's associated images should be deleted from disc.
	 * @return void
	 */
	public function delete($remove_images=true) {
		if ($this->id > 0) {
			
			// $this->deletePostMeta();
			wp_delete_post($this->id, true);
			
			// remove any associated images
			if ($remove_images) {
				$to_delete = array();
				$to_delete['original']  = $this->_wp_upload_dir . "/catablog/originals/" . $this->image;
				$to_delete['thumbnail'] = $this->_wp_upload_dir . "/catablog/thumbnails/" . $this->image;
				$to_delete['fullsize']  = $this->_wp_upload_dir . "/catablog/fullsize/" . $this->image;
				
				if (is_array($this->sub_images)) {
					foreach ($this->sub_images as $key => $image) {
						$to_delete["sub$key-original"]  = $this->_wp_upload_dir . "/catablog/originals/" . $image;
						$to_delete["sub$key-thumbnail"] = $this->_wp_upload_dir . "/catablog/thumbnails/" . $image;
						$to_delete["sub$key-fullsize"]  = $this->_wp_upload_dir . "/catablog/fullsize/" . $image;
					}					
				}
				
				foreach ($to_delete as $file) {
					if (is_file($file)) {
						unlink($file);
					}
				}
				
				delete_transient('dirsize_cache'); // WARNING!!! transient label hard coded.
			}
		}
		
	}




	/**
	 * Attach a new secondary image to the current CataBlogItem object. You
	 * should validate the image you wish to attach using validateImage before
	 * running this function with an uploaded file.
	 *
	 * @param string $tmp_path A file path to an uploaded image.
	 * @return boolean|string Wether or not the image was successfully attached to the catalog item.
	 */
	public function addSubImage($tmp_path) {
		if (function_exists('get_upload_space_available')) {
			$space_available = get_upload_space_available();
			$image_size = filesize($tmp_path);
		
			if ($image_size > $space_available) {
				$space_available = round(($space_available / 1024 / 1024), 2);
				$image_size      = round(($image_size / 1024 / 1024), 2);

				$error  = __('Can not write uploaded image to server, your storage space is exhausted.', 'catablog') . '<br />';
				$error .= __('Please delete some media files to free up space and try again.', 'catablog') . '<br />';
				$error .= sprintf(__('You have %sMB of available space on your server and your image is %sMB.', 'catablog'), $space_available, $image_size);
				return $error;
			}
		}
		
		// check if any image is of a bad format
		list($width, $height, $format) = getimagesize($tmp_path);
		switch($format) {
			case IMAGETYPE_GIF: break;
			case IMAGETYPE_JPEG: break;
			case IMAGETYPE_PNG:	break;
			default: return __("The image could not be used because it is an unsupported format. JPEG, GIF and PNG formats only, please.", 'catablog');
		}
		
		$filename   = $_FILES['new_sub_image']['name'];
		$image_name = $this->unique_filename($filename);
		$move_path  = $this->_wp_upload_dir . "/catablog/originals/$image_name";
		
		// move file to originals folder and set the filename into sub images array in the object
		$moved = move_uploaded_file($tmp_path, $move_path);
		$this->sub_images[] = $image_name;
		
		$this->updatePostMeta();
		
		// generate a thumbnail for the new image
		$this->makeThumbnail($image_name);
		if ($this->_options['lightbox-render']) {
			$this->makeFullsize($image_name);
		}
		
		delete_transient('dirsize_cache'); // WARNING!!! transient label hard coded.
		
		return true;
	}




	/*****************************************************
	**       - IMAGE GENERATION METHODS
	*****************************************************/




	public function makeFullsize($filepath=NULL) {
		if ($filepath === NULL) {
			$filepath = $this->getImage();
		}
		
		$original = $this->_wp_upload_dir . "/catablog/originals/" . $filepath;
		$fullsize = $this->_wp_upload_dir . "/catablog/fullsize/" . $filepath;
		$quality  = 80;
		
		if (is_file($original) === false) {
			return "<strong>$filepath</strong>: " . sprintf(__("Original image file missing, could not be located at %s", 'catablog'), $original);
		}
		
		list($width, $height, $format) = getimagesize($original);
		$canvas_size = $this->_options['image-size'];
		
		if ($width < 1 || $height < 1) {
			return "<strong>$filepath</strong>: " . __("Original image dimensions are less then 1px. Most likely PHP does not have permission to read the original file.", 'catablog');
		}
		
		if ($width < $canvas_size && $height < $canvas_size) {
			//original is smaller, do nothing....
		}
		
		
		$ratio = ($height > $width)? ($canvas_size / $height) : ($canvas_size / $width);			
		$new_height = $height * $ratio;
		$new_width  = $width * $ratio;
		
		
		// create a blank canvas of user specified size and fill it with background color
		$bg_color = $this->html2rgb($this->_options['background-color']);
		$canvas   = imagecreatetruecolor($new_width, $new_height);
		$bg_color = imagecolorallocate($canvas, $bg_color[0], $bg_color[1], $bg_color[2]);
		imagefilledrectangle($canvas, 0, 0, $new_width, $new_height, $bg_color);
		
		switch($format) {
			case IMAGETYPE_GIF:
				$upload = imagecreatefromgif($original);
				break;
			case IMAGETYPE_JPEG:
				$upload = imagecreatefromjpeg($original);
				break;
			case IMAGETYPE_PNG:
				$upload = imagecreatefrompng($original);
				break;
			default:
				return "<strong>$filepath</strong>: " . __("Original image could not be loaded because it is an unsupported format.", 'catablog');
		}
		
		// copy the image upload onto the canvas
		imagecopyresampled($canvas, $upload, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		// remove the upload image from memory
		if(is_resource($upload)) @imagedestroy($upload);
		
		// rotate the final canvas to match the original files orientation
		$canvas = $this->rotateImage($canvas, $original);
		
		// save the canvas
		imagejpeg($canvas, $fullsize, $quality);
		
		// remove the canvas from memory
		if(is_resource($canvas)) @imagedestroy($canvas);
		
		
		return true;
	}
	
	public function makeThumbnail($filepath=NULL) {
		if ($filepath === NULL) {
			$filepath = $this->getImage();
		}
		
		$original = $this->_wp_upload_dir . "/catablog/originals/" . $filepath;
		$thumb    = $this->_wp_upload_dir . "/catablog/thumbnails/" . $filepath;
		$quality  = 90;
		
		if (is_file($original) === false) {
			return "<strong>$filepath</strong>: " . sprintf(__("Original image file missing, could not be located at %s", 'catablog'), $original);
		}
		
		list($width, $height, $format) = @getimagesize($original);
		$canvas_width = $this->_options['thumbnail-width'];
		$canvas_height = $this->_options['thumbnail-height'];
		
		if ($width < 1 || $height < 1) {
			return "<strong>$filepath</strong>: " . __("Original image dimensions are less then 1px. Most likely PHP does not have permission to read the original file.", 'catablog');
		}
		
		switch($format) {
			case IMAGETYPE_GIF:
				$upload = imagecreatefromgif($original);
				break;
			case IMAGETYPE_JPEG:
				$upload = imagecreatefromjpeg($original);
				break;
			case IMAGETYPE_PNG:
				$upload = imagecreatefrompng($original);
				break;
			default:
				return "<strong>$filepath</strong>: " . __("Original image could not be loaded because it is an unsupported format.", 'catablog');
		}
		
		
		// rotate loaded image and get its dimensions
		$upload = $this->rotateImage($upload, $original);
		$width = imagesx($upload);
		$height = imagesy($upload);
		
		
		// create a blank canvas of user specified size and color
		$bg_color = $this->html2rgb($this->_options['background-color']);
		$canvas   = imagecreatetruecolor($canvas_width, $canvas_height);
		$bg_color = imagecolorallocate($canvas, $bg_color[0], $bg_color[1], $bg_color[2]);
		imagefilledrectangle($canvas, 0, 0, $canvas_width, $canvas_height, $bg_color);
		
		
		// determine settings to place the original image into the thumbnail canvas
		if (!$this->_options['keep-aspect-ratio']) {
			if ($width > $height) {
				$params = $this->crop_width($width, $height, $canvas_width, $canvas_height);
			}
			else {
				$params = $this->crop_height($width, $height, $canvas_width, $canvas_height);
			}
		}
		else {
			if ($width > $height) {
				$params = $this->shrink_width($width, $height, $canvas_width, $canvas_height);
			}
			else {
				$params = $this->shrink_height($width, $height, $canvas_width, $canvas_height);
			}
		}
		
		// copy the upload image onto the canvas
		imagecopyresampled($canvas, $upload, $params['left'], $params['top'], 0, 0, $params['width'], $params['height'], $width, $height);
		
		// remove the upload from memory
		if(is_resource($upload)) @imagedestroy($upload);
		
		// save the canvas
		imagejpeg($canvas, $thumb, $quality);
		
		// remove the canvas from memory
		if(is_resource($canvas)) @imagedestroy($canvas);
		
		return true;
	}
	
	
	private function shrink_width($original_width, $original_height, $thumbnail_width, $thumbnail_height) {
		$ratio      = $thumbnail_height / $original_height;
		$new_width  = $original_width * $ratio;
		$new_height = $thumbnail_height;
		$new_top    = 0;
		$new_left   = (($thumbnail_width - $new_width) / 2);

		if ($new_width > $thumbnail_width) {
			return $this->shrink_height($original_width, $original_height, $thumbnail_width, $thumbnail_height);
		}

		return array('top'=>$new_top, 'left'=>$new_left, 'width'=>$new_width, 'height'=>$new_height);
	}

	private function shrink_height($original_width, $original_height, $thumbnail_width, $thumbnail_height) {
		$ratio      = $thumbnail_width / $original_width;
		$new_width  = $thumbnail_width;
		$new_height = $original_height * $ratio;
		$new_top    = (($thumbnail_height - $new_height) / 2);
		$new_left   = 0;

		if ($new_height > $thumbnail_height) {
			return $this->shrink_width($original_width, $original_height, $thumbnail_width, $thumbnail_height);
		}

		return array('top'=>$new_top, 'left'=>$new_left, 'width'=>$new_width, 'height'=>$new_height);
	}

	private function crop_width($original_width, $original_height, $thumbnail_width, $thumbnail_height) {
		$ratio      = $thumbnail_width / $original_width;
		$new_width  = $thumbnail_width;
		$new_height = $original_height * $ratio;
		$new_top    = (($thumbnail_height - $new_height) / 2);
		$new_left   = 0;

		if ($new_height < $thumbnail_height) {
			return $this->crop_height($original_width, $original_height, $thumbnail_width, $thumbnail_height);
		}

		return array('top'=>$new_top, 'left'=>$new_left, 'width'=>$new_width, 'height'=>$new_height);
	}

	private function crop_height($original_width, $original_height, $thumbnail_width, $thumbnail_height) {
		$ratio      = $thumbnail_height / $original_height;
		$new_width  = $original_width * $ratio;
		$new_height = $thumbnail_height;
		$new_top    = 0;
		$new_left   = (($thumbnail_width - $new_width) / 2);

		if ($new_width < $thumbnail_width) {
			return $this->crop_width($original_width, $original_height, $thumbnail_width, $thumbnail_height);
		}

		return array('top'=>$new_top, 'left'=>$new_left, 'width'=>$new_width, 'height'=>$new_height);
	}
	
	
	
	
	
	
	
	
	
	/*****************************************************
	**       - GETTER METHODS
	*****************************************************/
	public function getId() {
		return $this->id;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getDescriptionSummary() {
		$no_line_breaks       = str_replace(array("\r", "\n", "\r\n"), ' ', ($this->getDescription()));
		$description_summary  = substr($no_line_breaks, 0, 120);
		$description_summary .= ($this->string_length($no_line_breaks) > 120)? '...' : '';
		return $description_summary;
	}
	public function getDate() {
		return $this->date;
	}
	public function getImage() {
		return $this->image;
	}
	public function getSubImages() {
		return (is_array($this->sub_images))? $this->sub_images : array();
	}
	public function getOrder() {
		return $this->order;
	}
	public function getLink() {
		return $this->link;
	}
	public function getPrice() {
		return $this->price;
	}
	public function getProductCode() {
		return $this->product_code;
	}
	public function getCategories() {
		return $this->categories;
	}
	public function getCategorySlugs() {
		global $wp_plugin_catablog_class;
		
		$slugs = array();
		$terms = $wp_plugin_catablog_class->get_terms();
		
		foreach ($terms as $term) {
			if ($this->inCategory($term->name)) {
				$slugs[] = $term->slug;
			}
		}
		
		return $slugs;
	}
	public function getCustomPostName() {
		return $this->_custom_post_name;
	}
	public function getCustomTaxName() {
		return $this->_custom_tax_name;
	}
	public function getCSVArray() {
		$id           = $this->getId();
		$image        = $this->getImage();
		$subimages    = implode('|', $this->getSubImages());
		$title        = $this->getTitle();
		$description  = $this->getDescription();
		$date         = $this->getDate();
		$order        = $this->getOrder();
		$link         = $this->getLink();
		$price        = $this->getPrice();
		$product_code = $this->getProductCode();
		$categories   = implode('|', $this->getCategories());
		return array($id, $image, $subimages, $title, $description, $date, $order, $link, $price, $product_code, $categories);
	}
	public function getPostMetaKey() {
		return $this->_post_meta_name;
	}
	public function getPermalink() {
		return get_permalink($this->id);
	}
	
	
	
	/*****************************************************
	**       - SETTER METHODS
	*****************************************************/
	public function setId($id) {
		$this->id = $id;
	}
	public function setTitle($title) {
		$this->title = $title;
	}
	public function setDescription($description) {
		$this->description = $description;
	}
	public function setDate($date) {
		$this->date = $date;
	}
	public function setImage($image, $update=true) {
		if ($update) {
			$this->_old_images[] = $this->image;
			$this->_main_image_changed = true;			
		}
		
		$this->image = $image;
	}
	public function setSubImage($image) {
		$this->sub_images[] = $image;
		$this->_sub_images_changed = true;
	}
	public function setSubImages($images) {
		$this->sub_images = $images;
		$this->_sub_images_changed = true;
	}
	public function setOrder($order) {
		$this->order = $order;
	}
	public function setLink($link) {
		$this->link = $link;
	}
	public function setPrice($price) {
		$this->price = $price;
	}
	public function setProductCode($product_code) {
		$this->product_code = $product_code;
	}
	public function setCategory($category) {
		$this->categories[] = $category;
	}
	public function setCategories($categories) {
		$this->categories = $categories;
	}
	
	
	
	/*****************************************************
	**       - CONDITIONAL METHODS
	*****************************************************/
	public function inCategory($test_category) {
		$bool = false;
		foreach ($this->getCategories() as $category) {
			if (strtolower($category) == strtolower($test_category)) {
				$bool = true;
			}
		}
		
		return $bool;
	}
	
	
	/*****************************************************
	**       - HELPER METHODS
	*****************************************************/
	private function processPostMeta($meta) {
		
		// deserialize meta if necessary
		if (is_serialized($meta)) {
			$meta = unserialize($meta);
		}
		
		// loop through meta array and set properties		
		if (is_array($meta)) {
			foreach ($meta as $key => $value) {
				$this->{str_replace('-', '_', $key)} = $value;
			}
		}
	}
	
	private function updatePostMeta() {
		$meta = array();
		$meta['image']        = $this->image;
		$meta['sub-images']   = $this->sub_images;
		$meta['link']         = $this->link;
		$meta['price']        = $this->price;
		$meta['product-code'] = $this->product_code;
		
		update_post_meta($this->id, $this->_post_meta_name, $meta);
	}
	
	private function deletePostMeta() {
		// remove the current post meta values from database
		delete_post_meta($this->id, $this->_post_meta_name);
	}
	
	private function deleteLegacyPostMeta() {
		delete_post_meta($this->id, 'catablog-image');
		delete_post_meta($this->id, 'catablog-link');
		delete_post_meta($this->id, 'catablog-price');
		delete_post_meta($this->id, 'catablog-product-code');		
	}
	
	private function getParameterArray() {
		$param_names = array();
		foreach ($this as $name => $value) {
			if (substr($name,0,1) != '_') {
				$param_names[] = $name;				
			}
		}
		return $param_names;
	}
	
	private function unique_filename($filename) {
		$original = $filename;
		$test_path  = $this->_wp_upload_dir . "/catablog/originals/$filename";
		$count = 2;
		
		while (is_file($test_path) && $count < 1000) {
			$filename_array = explode('.', $original);
			$extension      = array_pop($filename_array);
			$filename_two   = (implode('.', $filename_array)) . "-" . $count . "." . $extension;
			
			$test_path = $this->_wp_upload_dir . "/catablog/originals/$filename_two";
			$filename  = $filename_two;
			
			$count++;
		}
		
		return $filename;
	}
	// private function getSanitizedTitle() {
	// 	$special_chars_removed = preg_replace("/[^a-zA-Z0-9s]/", "", $this->title);
	// 	return sanitize_title($special_chars_removed) . ".jpg";
	// }
	
	private function html2rgb($color) {
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}
		
		if (strlen($color) == 6) {
			list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
		}
		elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		}
		else {
			return false;
		}
		
		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);
		
		return array($r, $g, $b);
	}
	
	private function rotateImage($canvas, $original) {
		if (function_exists('exif_read_data') && function_exists('imagerotate')) {
			$orientation = 1;
			$exif = @exif_read_data($original, 'EXIF', 0);
			if ($exif) {
				if (isset($exif['Orientation'])) {
					$orientation = $exif['Orientation'];
				}
			}
			
			switch ($orientation) {
				case 1:
					$orientation = 0;
					break;
				case 3:
					$orientation = 180;
					break;
				case 6:
					$orientation = -90;
					break;
				case 8:
					$orientation = 90;
					break;
			}
			
			if ($orientation != 0) {
				$canvas = imagerotate($canvas, $orientation, 0);
			}
			
		}
		// die;
		return $canvas;
	}
	
	public function string_length($string) {
		if (function_exists('mb_strlen')) {
			return mb_strlen($string);
		}
		else {
			return strlen($string);
		}
	}
	
	
}
