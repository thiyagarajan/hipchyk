<?php 
global $post;
wp_nonce_field( 'ps_update_promotion', 'promo_slider_noncename' ); 

// Setup form data
$target = get_post_meta($post->ID, '_promo_slider_target', TRUE);
$url = get_post_meta($post->ID, '_promo_slider_url', TRUE);
$disable_links = get_post_meta($post->ID, '_promo_slider_disable_links', TRUE);
$show_ad_code = get_post_meta($post->ID, '_promo_slider_show_ad_code', TRUE);
$ad_code = get_post_meta($post->ID, '_promo_slider_ad_code', TRUE);
$cdn_image_url = get_post_meta($post->ID, '_promo_slider_cdn_image_url', TRUE);

// Shortcut variables
$selected = ' selected="selected"';
$checked = ' checked="checked"';
?>

<br /><h3><?php _e('Change Linking Behaviour', 'promotion-slider'); ?></h3>

<!-- Link Target Attribute -->
  <p>
    <label for="_promo_slider_target"><?php _e('Select the behaviour for this link:', 'promotion-slider');?> </label>
    <select name="_promo_slider_target">
      <option value=""<?php if( empty($target) ) echo $selected; ?>><?php _e('-- Choose One --', 'promotion-slider'); ?></option>
      <option value="_self"<?php if( $target == '_self' ) echo $selected; ?>><?php _e('Open link in same page', 'promotion-slider'); ?></option>
      <option value="_blank"<?php if( $target == '_blank' ) echo $selected; ?>><?php _e('Open link in a new page', 'promotion-slider'); ?></option>
    </select>
  </p>

<!-- Destination URL --><br />
  <p><?php _e('By default, all links will point to this promotion page.  If you want to have your links point elsewhere, set the destination URL below.', 'promotion-slider'); ?></p>
  <p>
    <label for="_promo_slider_url"><?php _e('Destination URL: ', 'promotion-slider') ?></label> 
    <input type="text" id= "_promo_slider_url" name="_promo_slider_url" value="<?php if(!empty($url)) echo $url; ?>" size="75" />
  </p>

<!-- Disable Promotion Page --><br />
  <p><?php _e("If you just want to display the featured image and don't want to use this promotion page, you can disable all links here.", 'promotion-slider'); ?></p>
  <p>
    <input type="checkbox" id= "_promo_slider_disable_links" name="_promo_slider_disable_links" value="true"<?php if($disable_links) echo $checked; ?> size="75" />
    <label for="_promo_slider_disable_links"><?php _e('Disable all links for this promotion', 'promotion-slider') ?></label>
  </p>

<br /><h3 class="hndle"><?php _e('Insert Ad Code', 'promotion-slider'); ?></h3>

<!-- Show Ad Code -->
  <p><?php _e('Use the options here to display third party ads, such as Google AdSense ads, on your slider.', 'promotion-slider'); ?></p>
  <p>
    <input type="checkbox" id= "_promo_slider_show_ad_code" name="_promo_slider_show_ad_code" value="true"<?php if($show_ad_code) echo $checked; ?> size="75" />
    <label for="_promo_slider_show_ad_code"><?php _e('Display the ad code below rather than the featured image', 'promotion-slider') ?></label>
  </p>

<!-- Ad Code --><br />
  <p><?php _e('Insert ad code here:', 'promotion-slider'); ?></p>
  <p><textarea id= "_promo_slider_ad_code" name="_promo_slider_ad_code" rows="10" style="width:100%;"><?php echo $ad_code; ?></textarea></p>
  
<br /><h3 class="hndle"><?php _e('Use Content Delivery Network', 'promotion-slider'); ?></h3>

<!-- Show Ad Code -->
  <p><?php _e('If you use a content delivery network, such as Amazon CloudFront, you can define the url of the image here.  Please note that any URL in the box below will override the Featured Image in the slider.', 'promotion-slider'); ?></p>
  <p>
    <label for="_promo_slider_cdn_image_url"><?php _e('CDN Image URL: ', 'promotion-slider') ?></label> 
    <input type="text" id= "_promo_slider_cdn_image_url" name="_promo_slider_cdn_image_url" value="<?php if(!empty($cdn_image_url)) echo $cdn_image_url; ?>" size="75" />
  </p>