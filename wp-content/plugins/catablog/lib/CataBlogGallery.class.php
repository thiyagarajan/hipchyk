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
 * CataBlogGallery, a container class for each catalog gallery with all the important 
 * properties, such as title, description and the ids of catalog items in the gallery.
 * Variable names without an underscore prefix are the post_parameters or values set by the user.
 *
 * @package catablog
 */
class CataBlogGallery {
	
	// item properties that directly relate to 
	// values in the WordPress Database
	private $id           = null;
	private $item_ids     = array();
	private $title        = "";
	private $description  = "";
	private $date         = null;
	
	// object values not considered item properties
	// this will be skipped in getParameterArray() method
	private $_post_name = "";
	private $_options   = array();	
	private $_custom_post_gallery_name = "";
	
	
	// construction method
	public function __construct($post_parameters=null) {
		
		global $wp_plugin_catablog_class;
		$this->_custom_post_gallery_name = $wp_plugin_catablog_class->getCustomPostGalleryName();
		
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
	 * Get a single catalog gallery by database id
	 *
	 * @param integer $id The id of the catalog gallery you wish to get.
	 * @return null|CataBlogGallery
	 */
	public static function getGallery($id) {
		$post = get_post($id);
		
		if ($post == false) {
			return null;
		}
		
		$gallery = new CataBlogGallery();
		
		if ($post->post_type != $gallery->getCustomPostGalleryName()) {
			return null;
		}
		
		$gallery->id           = $post->ID;
		$gallery->item_ids     = unserialize($post->post_content);
		$gallery->title        = $post->post_title;
		$gallery->description  = $post->post_excerpt;
		$gallery->date         = $post->post_date;
		$gallery->_post_name   = $post->post_name;
		
		
		return $gallery;
	}




	/**
	 * Get just the id for every catalog gallery in the database. Returns
	 * an  array of integers, or possibly an empty array.
	 *
	 * @return array
	 */
	public static function getGalleryIds() {
		$cata = new CataBlogGallery();
		
		$params = array(
			'post_type'=> $cata->getCustomPostGalleryName(), 
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
	 * Get a collection of catalog galleries from the database. May
	 * possibly return an empty array.
	 *
	 * @param string $sort The database field used to sort the collection. Possible values are 'title', 'order', 'date' or 'random'.
	 * @param string $order The order the collection should be returned in. Possible values are 'asc' or 'desc'.
	 * @param integer $offset The start ordinal of the collection, or number of catalog items to skip over.
	 * @param integer $limit The maximum amount of catalog items allowed in the collection.
	 * @return array An array of CataBlogGallery objects
	 */
	public static function getGalleries($sort='menu_order', $order='asc', $offset=0, $limit=-1) {
		
		$galleries = array();
		
		$cata = new CataBlogGallery();
		
		$params = array(
			'post_type'=> $cata->getCustomPostGalleryName(), 
			'orderby'=> $sort,
			'order'=>$order,
			'offset'=>$offset,
			'numberposts' => $limit,
		);
		
		$posts = get_posts($params);
		
		// return an array of CataBlogGallery objects
		foreach ($posts as $post) {
			
			$gallery = new CataBlogGallery();
			
			$gallery->id           = $post->ID;
			$gallery->item_ids     = unserialize($post->post_content);
			$gallery->title        = $post->post_title;
			$gallery->description  = $post->post_excerpt;
			$gallery->date         = $post->post_date;
			$gallery->_post_name   = $post->post_name;
			
			$galleries[] = $gallery;
		}
		
		return $galleries;
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
		
		// check that the title is at least one character long
		if ($this->string_length($this->title) < 1) {
			return __('A gallery must have a title of at least one alphanumeric character.', 'catablog');
		}
		
		// check that the title is less then 200 characters long
		if ($this->string_length($this->title) > 200) {
			return __("A gallery title can not be more then 200 characters long.", 'catablog');
		}
		
		// // check that date value is a valid format
		// if (!preg_match("/^\d{4}-\d{2}-\d{2} [0-2][0-9]:[0-5][0-9]:[0-5][0-9]$/", $this->date)) { 
		// 	return __("An item's date must exactly match the MySQL date format, YYYY-MM-DD HH:MM:SS");
		// }
		// 
		// // check that the set date value is an actual day of the gregorian calendar
		// $year  = substr($this->date,0,4);
		// $month = substr($this->date,5,2);
		// $day   = substr($this->date,8,2);
		// if (!checkdate($month, $day, $year)) {
		// 	return __("An item's date must be an actual day on the gregorian calendar.");
		// }
		// 
		// $hour  = (int) substr($this->date,11,2);
		// if ($hour > 23) {
		// 	return __("An item's date hour must be below twenty-four.");
		// }
		
		return true;
	}



	/**
	 * This function will save the current CataBlogGallery object to the 
	 * database. If the object has an id set, it will update, otherwise
	 * it will create a new catalog gallery.
	 *
	 * @return boolean|string Wether or not the database write was successful.
	 */
	public function save() {
		
		
		$params = array();
		$params['post_title']   = $this->title;
		$params['post_name']    = sanitize_title($this->title);
		$params['post_content'] = serialize($this->item_ids);
		$params['post_excerpt'] = $this->description;
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
			$params['comment_status'] = 'closed';
			$params['post_type']      = $this->_custom_post_gallery_name;
			
			$this->id = wp_insert_post($params);
			if ($this->id === false) {
				return false;
			}
		}
		
		return true;
	}




	/**
	 * This function removes the current CataBlogGallery object from the
	 * database. The object must have an id greater then zero to actually
	 * delete the catalog gallery from the database.
	 *
	 * @return void
	 */
	public function delete() {
		if ($this->id > 0) {
			wp_delete_post($this->id, true);
		}
	}





	
	
	
	
	
	/*****************************************************
	**       - GETTER METHODS
	*****************************************************/
	public function getId() {
		return $this->id;
	}
	public function getItemIds() {
		return $this->item_ids;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getDate() {
		return $this->date;
	}
	public function getCustomPostGalleryName() {
		return $this->_custom_post_gallery_name;
	}
	public function getCSVArray() {
		$id           = $this->getId();
		$item_ids     = $this->getItemIds();
		$title        = $this->getTitle();
		$description  = $this->getDescription();
		$date         = $this->getDate();
		return array($id, $item_ids, $title, $description, $date);
	}
	public function getCataBlogItems() {
		return CataBlogItem::getItemsByIds($this->item_ids);
	}
	
	
	/*****************************************************
	**       - SETTER METHODS
	*****************************************************/
	public function setId($id) {
		$this->id = $id;
	}
	public function setItemId($item_id) {
		$this->item_ids[] = $item_id;
	}
	public function setItemIds($item_ids) {
		$this->item_ids = $item_ids;
	}
	public function addItem($item_id) {
		$this->setItemId($item_id);
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
	
	
	
	
	/*****************************************************
	**       - HELPER METHODS
	*****************************************************/
	private function getParameterArray() {
		$param_names = array();
		foreach ($this as $name => $value) {
			if (substr($name,0,1) != '_') {
				$param_names[] = $name;				
			}
		}
		return $param_names;
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
