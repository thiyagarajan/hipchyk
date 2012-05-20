<?php

// Add actions
add_action('promoslider_content', 'promoslider_display_image');
add_action('promoslider_nav', 'promoslider_display_nav');
add_action('promoslider_thumbnail_nav', 'promoslider_thumb_nav');
add_action('promoslider_content', 'promoslider_display_title');
add_action('promoslider_content', 'promoslider_display_excerpt');

function promoslider_display_image($values){
	global $post; extract($values);
	// Check to see if ad code should be displayed
	if( get_post_meta($post->ID, '_promo_slider_show_ad_code', TRUE) ): 
		echo get_post_meta($post->ID, '_promo_slider_ad_code', TRUE);
	elseif( $cdn_img = get_post_meta($post->ID, '_promo_slider_cdn_image_url', TRUE) ): ?>
		<div class="promo_slider_background_image"><?php
			if( !$disable_links ) echo '<a href="'.$destination_url.'" target="'.$target.'">'; ?>
			<img src="<?php echo $cdn_img; ?>" alt="" /><?php
			if( !$disable_links ) echo '</a>'; ?>
		</div><?php
	// Otherwise, show featured image
	elseif( $image ): ?>
		<div class="promo_slider_background_image"><?php
			if( !$disable_links ) echo '<a href="'.$destination_url.'" target="'.$target.'">';
			echo $image;
			if( !$disable_links ) echo '</a>'; ?>
		</div><?php
	endif;
}

function promoslider_display_nav( $nav_type ){ // Display the default navigation
  $options = get_option('promotion_slider_options'); 
  switch( $nav_type ){
	case 'default': ?>
		<div class="promo_slider_nav">
			<span class="move_backward pointer" title="Move Backward">&lt;&lt;</span>
			<span class="slider_selections pointer"></span>
			<span class="move_forward pointer" title="Move Forward">&gt;&gt;</span>
		</div>
		<?php break;
	case 'fancy': ?>
		<div class="promo_slider_nav fancy_ps_nav">
			<span class="move_backward pointer" title="Move Backward">&lt;&lt;</span>
			<span class="slider_selections pointer"></span>
			<span class="move_forward pointer" title="Move Forward">&gt;&gt;</span>
		</div>
		<?php break;
	case 'links': ?>
		<div class="right_arrow move_forward pointer ps_hover" title="Move Forward"></div>
		<div class="left_arrow move_backward pointer ps_hover" title="Move Backward"></div>
		<?php break;
	default:
		return;
  }
}

function promoslider_thumb_nav($values){ // Display thumbnail navigation 
	extract($values); ?>
	<div class="promo_slider_thumb_nav"<?php if($width) echo ' style="'.$width.'"'; ?>>
		<div class="right_arrow move_forward pointer" title="Move Forward"></div>
		<div class="thumb_nav slider_selections pointer">
			<?php foreach($thumbs as $key => $thumb): ?>
				<span class="<?php echo $key + 1; ?>">
				<?php echo $thumb; ?>
				</span>
			<?php endforeach; ?>
		</div>
		<div class="clear"></div>
		<div class="left_arrow move_backward pointer" title="Move Backward"></div>
	</div><?php
}

function promoslider_display_title($values){
	global $post; extract($values);
	// If there is no title, don't do anything
	if( !$title ) return;
	// If ad code is being displayed, don't show the title
	if( get_post_meta($post->ID, '_promo_slider_show_ad_code', TRUE) ) return;
	// Set classess for the title
	$title_classes = 'promo_slider_title';
	// Change display based on user's choices
	switch( $display_title ){
		case 'fancy':
			// Add class for fancy display
			$title_classes .= ' fancy_ps_title';
		case 'default': ?>
			<div class="<?php echo $title_classes; ?>"><?php
				if( !$disable_links ) echo '<a href="'.$destination_url.'" target="'.$target.'">';
				echo $title;
				if( !$disable_links ) echo '</a>'; ?>
			</div>
			<?php break;
		default:
			return;
	}
}

function promoslider_display_excerpt($values){
	global $post; extract($values);
	// If there is no excerpt, don't do anything
	if( !$excerpt ) return;
	// If add code is being displayed, don't show the excerpt
	if( get_post_meta($post->ID, '_promo_slider_show_ad_code', TRUE) ) return;
	// Otherwise, if there is an excerpt, display it 
	switch( $display_excerpt ){
		case 'excerpt': ?>
			<div class="promo_slider_excerpt"><?php echo $excerpt; ?></div><?php
			break;
		default:
			return;
	}
}

?>