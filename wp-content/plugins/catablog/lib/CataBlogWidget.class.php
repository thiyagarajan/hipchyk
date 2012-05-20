<?php
/**
 * CataBlog Widget Class
 *
 * This file contains the widget classes for the CataBlog WordPress Plugin.
 * @author Zachary Segal <zac@illproductions.com>
 * @version 1.6.3
 * @package catablog
 */

class CataBlogWidget extends WP_Widget {

	function CataBlogWidget() {
		$widget_ops = array('classname' => 'CataBlogWidget', 'description' => __("Entries from your CataBlog Library", "catablog") );
		$this->WP_Widget('CataBlogWidget', __("CataBlog Catalog", "catablog"), $widget_ops);
	}


	function form($instance) {
		
		global $wp_plugin_catablog_class;
		
		$instance = wp_parse_args( (array) $instance, array(
			'title'             => '',
			'catablog-category' => '',
			'catablog-template' => 'gallery',
			'catablog-sort'     => 'date',
			'catablog-order'    => 'asc',
			'catablog-operator' => 'in',
			'catablog-limit'    => 5,
		) );
		
		$title    = $instance['title'];
		$category = $instance['catablog-category'];
		$template = $instance['catablog-template'];
		$sort     = $instance['catablog-sort'];
		$order    = $instance['catablog-order'];
		$operator = $instance['catablog-operator'];
		$limit    = $instance['catablog-limit'];
		
		// load the users templates files
		$template_file_array = array();
		$views = new CataBlogDirectory($wp_plugin_catablog_class->directories['user_views']);
		if ($views->isDirectory()) {
			$template_file_array = $views->getFileArray();
		}
		
		// $catablog_categories = $wp_plugin_catablog_class->get_terms();
		$catablog_sort_options = array(
			'date'       => __("Date", "catablog"),
			'menu_order' => __("Order", "catablog"),
			'title'      => __("Title", "catablog"),
			'rand'       => __("Random", "catablog"),
		);
		$catablog_order_options = array(
			'asc'  => __("Ascending", "catablog"),
			'desc' => __("Descending", "catablog"),
		);
		$catablog_operator_options = array(
			'in'     => __("In Categories", "catablog"),
			'not in' => __("Not In Categories", "catablog"),
			'and'    => __("In All Categories", "catablog"),
		);
		$form_template_path  = $wp_plugin_catablog_class->directories['template'] . '/widget-form.php';
		
		?>
		
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title: ", "catablog"); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		
		<?php /*
		<p>
			<label for="<?php echo $this->get_field_id('catablog-category') ?>"><?php _e("Categories"); ?></label>
			<select id="<?php echo $this->get_field_id('catablog-category') ?>" name="<?php echo $this->get_field_name('catablog-category') ?>" class="widefat" multiple="multiple">
				<?php foreach ($catablog_categories as $cat): ?>
					<option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name ?></option>
				<?php endforeach ?>
			</select>
		</p>
		*/ ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('catablog-category'); ?>"><?php _e("Category:", "catablog"); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('catablog-category');?>" name="<?php echo $this->get_field_name('catablog-category'); ?>" value="<?php echo esc_attr($category); ?>">
			</label>
			<small><?php _e("Separate category names with commas", "catablog"); ?></small>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('catablog-template'); ?>"><?php _e("Template:", "catablog"); ?>
			<select id="<?php echo $this->get_field_id('catablog-template'); ?>" name="<?php echo $this->get_field_name('catablog-template'); ?>" class="widefat">
				<?php foreach ($template_file_array as $file): ?>
					<?php $file = pathinfo($file, PATHINFO_FILENAME); ?>
					<?php $selected = ($file == $template)? ' selected="selected"' : ''; ?>
					<option value="<?php echo $file; ?>"<?php echo $selected; ?>><?php echo $file; ?></option>
				<?php endforeach ?>
			</select>
			</label>
		</p>
		
		<p>
			<label for"<?php echo $this->get_field_id('catablog-sort'); ?>"><?php _e("Sort:", "catablog"); ?>
			<select id="<?php echo $this->get_field_id('catablog-sort'); ?>" name="<?php echo $this->get_field_name('catablog-sort'); ?>" class="widefat">
				<?php foreach ($catablog_sort_options as $value => $sort_option): ?>
					<?php $selected = ($value == $sort)? ' selected="selected"' : ''; ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $sort_option; ?></option>
				<?php endforeach ?>
			</select>
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('catablog-order'); ?>"><?php _e("Order:", "catablog"); ?>
			<select id="<?php echo $this->get_field_id('catablog-order'); ?>" name="<?php echo $this->get_field_name('catablog-order'); ?>" class="widefat">
				<?php foreach ($catablog_order_options as $value => $order_option): ?>
					<?php $selected = ($value == $order)? ' selected="selected"' : '' ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $order_option; ?></option>
				<?php endforeach ?>
			</select>
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('catablog-operator'); ?>"><?php _e("Operator:", "catablog"); ?>
			<select id="<?php echo $this->get_field_id('catablog-operator'); ?>" name="<?php echo $this->get_field_name('catablog-operator'); ?>" class="widefat">
				<?php foreach ($catablog_operator_options as $value => $operator_option): ?>
					<?php $selected = ($value == $operator)? ' selected="selected"' : ''; ?>
					<option value="<?php echo $value; ?>"<?php echo $selected; ?>><?php echo $operator_option; ?></option>
				<?php endforeach ?>
			</select>
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('catablog-limit'); ?>"><?php _e("Limit:", "catablog"); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('catablog-limit'); ?>" name="<?php echo $this->get_field_name('catablog-limit'); ?>" value="<?php echo esc_attr($limit); ?>" />
			</label>
		</p>
		
		<?php
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		
		$instance['catablog-category'] = $new_instance['catablog-category'];
		$instance['catablog-template'] = $new_instance['catablog-template'];
		$instance['catablog-sort']     = $new_instance['catablog-sort'];
		$instance['catablog-order']    = $new_instance['catablog-order'];
		$instance['catablog-operator'] = $new_instance['catablog-operator'];
		$instance['catablog-limit']    = $new_instance['catablog-limit'];
		
	    return $instance;
	}
	
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		
		if (!empty($title))
		echo $before_title . $title . $after_title;;
		
		// WIDGET CODE GOES HERE
				
		if (function_exists('catablog_show_items')) {
			
			$category = '';
			$template = 'gallery';
			$sort = 'rand';
			$order = 'asc';
			$operator = 'in';
			$limit = 6;
			$navigation = false;
			
			
			if (!empty($instance['catablog-category'])) {
				$category = $instance['catablog-category'];
			}
			if (!empty($instance['catablog-template'])) {
				$template = $instance['catablog-template'];
			}
			if (!empty($instance['catablog-sort'])) {
				$sort = $instance['catablog-sort'];
			}
			if (!empty($instance['catablog-order'])) {
				$order = $instance['catablog-order'];
			}
			if (!empty($instance['catablog-operator'])) {
				$operator = $instance['catablog-operator'];
			}
			if (!empty($instance['catablog-limit'])) {
				$limit = $instance['catablog-limit'];
			}
			
			
			catablog_show_items($category, $template, $sort, $order, $operator, $limit, $navigation);
		}
		else {
			_e("CataBlog display functions could not be found, please completely reinstall CataBlog.", "catablog");
		}
		
		// WIDGET CODE ENDS HERE
		
		echo $after_widget;
	}


 
}


















class CataBlogCategoryWidget extends WP_Widget {

	function CataBlogCategoryWidget() {
		$widget_ops = array('classname' => 'CataBlogCategoryWidget', 'description' => __("Categories from your CataBlog Library", "catablog") );
		$this->WP_Widget('CataBlogCategoryWidget', __("CataBlog Categories", "catablog"), $widget_ops);
	}


	function form($instance) {
		
		global $wp_plugin_catablog_class;
		
		$catablog_options = $wp_plugin_catablog_class->get_options();
		if (false === $catablog_options['public_posts']) {
			printf(__("This widget requires you to enable the %sCataBlog Public Option%s.", "catablog"), '<a href="'.get_admin_url(null, 'admin.php?page=catablog-options#public').'">', '</a>');
			return false;
		}
		
		
		$instance = wp_parse_args( (array) $instance, array(
			'title'     => '',
			'dropdown'  => NULL,
			'count'     => NULL,
			'hierarchy' => NULL,
		) );
		
		$title     = $instance['title'];
		$dropdown  = $instance['dropdown'];
		$count     = $instance['count'];
		$hierarchy = $instance['hierarchy'];
		
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title: ", "catablog"); ?>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php echo ($dropdown !== NULL)? ' checked="checked"' : ''; ?>/>
			<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e("Display as dropdown", "catablog"); ?></label><br />
			
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php echo ($count !== NULL)? ' checked="checked"' : ''; ?>/>
			<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e("Show post count", "catablog"); ?></label><br />
			
			<?php /*
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hierarchy'); ?>" name="<?php echo $this->get_field_name('hierarchy'); ?>"<?php echo ($hierarchy !== NULL)? ' checked="checked"' : ''; ?>/>
			<label for="<?php echo $this->get_field_id('hierarchy'); ?>"><?php _e("Show hierarchy", "catablog"); ?></label><br />
			*/ ?>
		</p>
		
		<?php
		
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title']    = $new_instance['title'];
		$instance['dropdown'] = $new_instance['dropdown'];
		$instance['count']    = $new_instance['count'];
		$instance['hierarchy']    = $new_instance['hierarchy'];
		return $instance;
	}
	
	function widget($args, $instance) {
		
		global $wp_plugin_catablog_class;
		
		$catablog_options = $wp_plugin_catablog_class->get_options();
		if (false === $catablog_options['public_posts']) {
			return false;
		}
		
		
		extract($args, EXTR_SKIP);
		
		echo $before_widget;
		$title    = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$d = !empty($instance['dropdown']);
		$c = !empty($instance['count']);
		$h = false; //!empty($instance['hierarchy']);
		
		if (!empty($title))
		echo $before_title . $title . $after_title;;
		
		// WIDGET CODE GOES HERE
		
		echo $wp_plugin_catablog_class->frontend_render_categories($d, $c);
		
		// WIDGET CODE ENDS HERE
		
		echo $after_widget;
	}


 
}
