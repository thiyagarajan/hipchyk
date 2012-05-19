<?php

/*
Plugin Name: WP Coda Slider
Plugin URI: http://c3mdigital.com/wp-coda-slider/
Description: Add a jQuery Coda slider to any WordPress post or page
Author: c3mdigital
Author URI: http://c3mdigital.com/
Version: 0.3.3.2
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/


	add_filter( 'cmb_meta_boxes', 'coda_slider_meta_boxes' );
	add_action( 'init', 'c3m_initialize_meta_boxes', 9999 );
	add_action( 'wp_enqueue_scripts', 'c3m_coda_scripts' );
	add_filter( 'the_content', 'c3m_slider_show' );

	/**
	 * @return array
	 * @description Sets up the meta boxes added to post edit screen
	 */
	function coda_slider_meta_boxes() {
		$prefix = '_c3m_';

		$meta_boxes[] = array (
			'id' => 'slider_meta',
			'title' => 'Create a coda slider for this post',
			'pages' => array ( 'page', 'post' ), // Post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array (
				array (
					'name' => 'Unique Title',
					'desc' => 'Give the slider a unique title ( used as div id)',
					'id' => $prefix . 'title',
					'type' => 'text_small',
				),
				array (
					'name' => 'Display slider:',
					'id' => $prefix . 'display',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'Display Slider', 'value' => 'before', ),
						array ( 'name' => 'Don\'t Display on this page', 'value' => 'never', ),
					),
				),
				array (
					'name' => 'Show Post:',
					'id' => $prefix . 'content',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'Content', 'value' => 'content', ),
						array ( 'name' => 'Excerpt', 'value' => 'excerpt', ),
					),
				),
				array (
					'name' => 'Show Title:',
					'id' => $prefix . 'title',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'Yes', 'value' => 'yes', ),
						array ( 'name' => 'No', 'value' => 'no', ),
					),
				),
				array (
					'name' => 'Category to get posts from',
					'id' => $prefix . 'cat',
					'type' => 'taxonomy_select',
					'taxonomy' => 'category', // Taxonomy Slug
				),
				array (
					'name' => 'Number of posts to query',
					'desc' => 'enter -1 for all posts in the category',
					'id' => $prefix . 'show',
					'type' => 'text_small',
				),

				array (
					'name' => 'CSS Options',
					'id' => $prefix . 'test_title',
					'type' => 'title',
				),
				array (
					'name' => 'CSS Width',
					'desc' => 'How wide in px, em, or %',
					'id' => $prefix . 'width',
					'type' => 'text_small',
				),
				array (
					'name' => 'Tab background color',
					'id' => $prefix . 'tab_bg',
					'type' => 'colorpicker',
					'std' => '#000000'
				),
				array (
					'name' => 'Tab Active background color',
					'id' => $prefix . 'tab_active_bg',
					'type' => 'colorpicker',
					'std' => '#000000'
				),
				array (
					'name' => 'Tab text color',
					'id' => $prefix . 'tab_color',
					'type' => 'colorpicker',
					'std' => '#ffffff'
				),
				array (
					'name' => 'Tab Active text color',
					'id' => $prefix . 'tab_active_color',
					'type' => 'colorpicker',
					'std' => '#ffffff'
				),
				array (
					'name' => 'Tab title font size',
					'desc' => 'Enter value in px, em or %',
					'id' => $prefix . 'tab_font',
					'type' => 'text_small',
				),
				array (
					'name' => 'Custom CSS',
					'desc' => 'Include any custom css',
					'id' => $prefix . 'slider_css',
					'type' => 'textarea_small',
				),
				array (
					'name' => 'Coda slider Options',
					'id' => $prefix . 'slider_args',
					'type' => 'title',
				),
				array (
					'name' => 'autoHeight',
					'id' => $prefix . 'autoheight',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'True', 'value' => 'true' ),
						array ( 'name' => 'False', 'value' => 'false', ),
					),
				),
				array (
					'name' => 'autoSlide',
					'id' => $prefix . 'autoslide',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'True', 'value' => 'true', ),
						array ( 'name' => 'False', 'value' => 'false', ),
					),
				),
				array (
					'name' => 'autoSlideInterval',
					'id' => $prefix . 'slide_interval',
					'type' => 'text_small',
				),
				array (
					'name' => 'autoSlideStopWhenClicked',
					'id' => $prefix . 'stop_click',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'True', 'value' => true, ),
						array ( 'name' => 'False', 'value' => false, ),
					),
				),
				array (
					'name' => 'dynamicTabs',
					'id' => $prefix . 'dyntabs',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'True', 'value' => 'true', ),
						array ( 'name' => 'False', 'value' => 'false', ),
					),
				),
				array (
					'name' => 'dynamicTabsAlign',
					'id' => $prefix . 'tab_align',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'Center', 'value' => 'center', ),
						array ( 'name' => 'Left', 'value' => 'left', ),
						array ( 'name' => 'Right', 'value' => 'right', ),
					),
				),
				array (
					'name' => 'dynamicArrows',
					'id' => $prefix . 'dynamicarrows',
					'type' => 'radio_inline',
					'options' => array (
						array ( 'name' => 'True', 'value' => 'true', ),
						array ( 'name' => 'False', 'value' => 'false', ),
					),
				),
				array (
					'name' => 'Dynamic Arrows Left Text',
					'id' => $prefix . 'left_text',
					'type' => 'text_small',
				),
				array (
					'name' => 'Dynamic Arrows Right Text',
					'id' => $prefix . 'right_text',
					'type' => 'text_small',
				),

				array (
					'name' => 'EaseDuration',
					'id' => $prefix . 'easeduration',
					'type' => 'text_small',
				),
				array (
					'name' => 'SlideEaseFunction',
					'id' => $prefix . 'slidefunc',
					'type' => 'select',
					'options' => array (
						array ( 'name' => 'jswing', 'value' => 'jswing', ),
						array ( 'name' => 'easeInQuad', 'value' => 'easeInQuad', ),
						array ( 'name' => 'easeOutQuad', 'value' => 'easeOutQuad', ),
						array ( 'name' => 'easeInOutQuad', 'value' => 'easeInOutQuad', ),
						array ( 'name' => 'easeInCubic', 'value' => 'easeInCubic', ),
						array ( 'name' => 'easeOutCubic', 'value' => 'easeOutCubic', ),
						array ( 'name' => 'easeInOutCubic', 'value' => 'easeInOutCubic', ),
						array ( 'name' => 'easeInQuart', 'value' => 'easeInQuart', ),
						array ( 'name' => 'easeOutQuart', 'value' => 'easeOutQuart', ),
						array ( 'name' => 'easeInOutQuart', 'value' => 'easeInOutQuart', ),
						array ( 'name' => 'easeInQuint', 'value' => 'easeInQuint', ),
						array ( 'name' => 'easeOutQuint', 'value' => 'easeOutQuint', ),
						array ( 'name' => 'easeInOutQuint', 'value' => 'easeInOutQuint', ),
						array ( 'name' => 'easeInSine', 'value' => 'easeInSine', ),
						array ( 'name' => 'easeOutSine', 'value' => 'easeOutSine', ),
						array ( 'name' => 'easeInOutSine', 'value' => 'easeInOutSine', ),
						array ( 'name' => 'easeInExpo', 'value' => 'easeInExpo', ),
						array ( 'name' => 'easeOutExpo', 'value' => 'easeOutExpo', ),
						array ( 'name' => 'easeInOutExpo', 'value' => 'easeInOutExpo', ),
						array ( 'name' => 'easeInCirc', 'value' => 'easeInCirc', ),
						array ( 'name' => 'easeOutCirc', 'value' => 'easeOutCirc', ),
						array ( 'name' => 'easeInOutCirc', 'value' => 'easeInOutCirc', ),
						array ( 'name' => 'easeInElastic', 'value' => 'easeInElastic', ),
						array ( 'name' => 'easeOutElastic', 'value' => 'easeOutElastic', ),
						array ( 'name' => 'easeInOutElastic', 'value' => 'easeInOutElastic', ),
						array ( 'name' => 'easeInBack', 'value' => 'easeInBack', ),
						array ( 'name' => 'easeOutBack', 'value' => 'easeOutBack', ),
						array ( 'name' => 'easeInOutBack', 'value' => 'easeInOutBack', ),
						array ( 'name' => 'easeInBounce', 'value' => 'easeInBounce', ),
						array ( 'name' => 'easeOutBounce', 'value' => 'easeOutBounce', ),
						array ( 'name' => 'easeInOutBounce', 'value' => 'easeInOutBounce', ),
					),
				),
			)

		);

		return $meta_boxes;
	}

	/**
	 * @description Initializes the meta box class
	 * @see
	 */

	function c3m_initialize_meta_boxes() {
		if ( ! class_exists ( 'cmb_Meta_Box' ) )
			require dirname ( __FILE__ ) . '/lib/init.php';

	}

	/**
	 * @param $meta
	 * @param string $return
	 * @param string $post_id
	 * @return mixed
	 */

	function c3m_meta( $meta, $return = '', $post_id = '' ) {
		if ( $return == '' ) $return = true;
		if ( ! $post_id ) $post_id = get_the_ID ();
		$val = get_post_meta ( $post_id, $meta, true );
		if ( empty( $val ) ) {
			$meta = NULL;
		} else $meta = $val;
		if ( true == $return ) return $meta;
		else echo $meta;

	}

	/**
	 * @param $content
	 * @return string
	 */
	function c3m_slider_show( $content ) {
		if ( c3m_meta( '_c3m_display' ) == '' || c3m_meta ( '_c3m_display' ) == 'never' || ! is_singular () )
			return $content;

		$cat            = c3m_meta( '_c3m_cat' );
		$show           = c3m_meta( '_c3m_show' );
		$autoheight     = c3m_meta( '_c3m_autoheight' );
		$easeduration   = c3m_meta( '_c3m_easeduration' );
		$easefunc       = c3m_meta( '_c3m_easefunc' );
		$stop_click     = c3m_meta( '_c3m_stop_click' );
		$tab_align      = c3m_meta( '_c3m_tab_align' );
		$right_text     = c3m_meta( '_c3m_right_text' );
		$left_text      = c3m_meta( '_c3m_left_text' );
		$tabs           = c3m_meta( '_c3m_dyntabs' );
		$arrows         = c3m_meta( '_c3m_dynamicarrows' );
		$slide_int      = c3m_meta( '_c3m_slide_interval' );
		$auto_slide     = c3m_meta( '_c3m_autoslide' );
		$id             = c3m_meta( '_c3m_title' );
		$tab_color      = c3m_meta( '_c3m_tab_color' );
		$tab_bg         = c3m_meta( '_c3m_tab_bg' );
		$tab_active_bg  = c3m_meta( '_c3m_tab_active_bg' );
		$tab_active_col = c3m_meta( '_c3m_tab_active_color' );
		$width          = c3m_meta( '_c3m_width' );
		$tab_font       = c3m_meta( '_c3m_tab_font' );
		$slider_css     = c3m_meta( '_c3m_slider_css' );

		$args = array (
			'autoHeight' => $autoheight,
			'autoSlide' => $auto_slide,
			'autoSlideInterval' => $slide_int,
			'autoSlideStopWhenClicked' => $stop_click,
			'dynamicArrows' => $arrows,
			'dynamicArrowLeftText' => $left_text,
			'dynamicArrowRightText' => $right_text,
			'dynamicTabs' => $tabs,
			'dynamicTabsAlign' => $tab_align,
			'slideEaseDuration' => $easeduration,
			'slideEaseFunction' => $easefunc

		);

		$defaults = array (
			'autoHeight' => 'true',
			'autoSlide' => 'false',
			'autoSlideInterval' => '7000',
			'autoSlideStopWhenClicked' => 'true',
			'dynamicArrows' => 'true',
			'dynamicArrowLeftText' => '&#171; left',
			'dynamicArrowRightText' => 'right &#187;',
			'dynamicTabs' => 'true',
			'dynamicTabsAlign' => 'center',
			'slideEaseDuration' => '1000',
			'slideEaseFunction' => 'easeInOutExpo'
		);

		$autoHeight = empty( $args['autoHeight'] ) ? $defaults['autoHeight'] : $args['autoHeight'];
		$autoSlide = empty( $args['autoSlide'] ) ? $defaults['autoSlide'] : $args['autoSlide'];
		$autoSlideInterval = empty( $args['autoSlideInterval'] ) ? $defaults['autoSlideInterval'] : $args['autoSlideInterval'];
		$autoSlideStopWhenClicked = empty( $args['autoSlideStopWhenClicked'] ) ? $defaults['autoSlideStopWhenClicked'] : $args['autoSlideStopWhenClicked'];
		$dynamicArrows = empty( $args['dynamicArrows'] ) ? $defaults['dynamicArrows'] : $args['dynamicArrows'];
		$dynamicArrowLeftText = empty( $args['dynamicArrowLeftText'] ) ? $defaults['dynamicArrowLeftText'] : $args['dynamicArrowLeftText'];
		$dynamicArrowRightText = empty( $args['dynamicArrowRightText'] ) ? $defaults['dynamicArrowRightText'] : $args['dynamicArrowRightText'];
		$dynamicTabs = empty( $args['dynamicTabs'] ) ? $defaults['dynamicTabs'] : $args['dynamicTabs'];
		$dynamicTabsAlign = empty( $args['dynamicTabsAlign'] ) ? $defaults['dynamicTabsAlign'] : $args['dynamicTabsAlign'];
		$slideEaseDuration = empty( $args['slideEaseDuration'] ) ? $defaults['slideEaseDuration'] : $args['slideEaseDuration'];
		$slideEaseFunction = empty( $args['slideEaseFunction'] ) ? $defaults['slideEaseFunction'] : $args['slideEaseFunction'];

		$content .= '<div class="coda-slider-wrapper">';
		$content .= '<div class="coda-slider preload" id="' . $id . '">';
		$args = array (
			'post_type' => 'post',
			'posts_per_page' => $show,
			'cat__in' => $cat,
			'post_not_in' => array ( get_the_ID () )
		);

		$loop = new WP_Query( $args );
		while ( $loop->have_posts () ) : $loop->the_post ();
			$content .= '<div id="post-' . get_the_ID () . '" class="panel">';
			$content .= '<div class="panel-wrapper">';
			if ( c3m_meta ( '_c3m_title' ) == 'yes' ) {
				$content .= '<h2 class="title">' . get_the_title () . '</h2>';
			} else {
				$content .= '<h2 class="title" style="display:none">' . get_the_title () . '</h2>';
			}
			if ( c3m_meta ( '_c3m_content' ) == 'excerpt' ) {
				$content .= get_the_excerpt ();
			} else {
				$content .= apply_filters ( 'the_content', get_the_content () );
			}
			$content .= '</div><!-- /panel-wrapper --> </div><!-- /panel -->';

		endwhile;
		wp_reset_postdata ();

		$content .= '</div><!-- /.coda-slider .preload -->';
		$content .= '</div><!-- /coda-slider-wrapper -->';
		$content .= '<script type="text/javascript" >
				jQuery(document).ready(function($) {
					$( "#' . $id . '" ) .codaSlider ({
						autoHeight:' . $autoHeight . ',
						autoSlide:' . $autoSlide . ',
						autoSlideInterval:' . $autoSlideInterval . ',
						autoSlideStopWhenClicked:' . $autoSlideStopWhenClicked . ',
						dynamicArrows:' . $dynamicArrows . ',
						dynamicArrowLeftText:"' . $dynamicArrowLeftText . '",
						dynamicArrowRightText:"' . $dynamicArrowRightText . '",
						dynamicTabs:' . $dynamicTabs . ',
						dynamicTabsAlign:"' . $dynamicTabsAlign . '",
						slideEaseDuration:' . $slideEaseDuration . ',
						slideEaseFunction:"' . $slideEaseFunction . '"
					});

					$(".coda-slider-wrapper").hover(function() {
						$(".coda-nav-left, .coda-nav-right").fadeIn(600);
					}, function() {
						$(".coda-nav-left, .coda-nav-right").fadeOut(600);
					});
				});

			</script>';
		$content .= '<style type="text/css">
					.coda-slider-wrapper { padding: 20px 0; direction:ltr; position: relative; }
					.coda-slider { /*background:#a9a9a9;*/ /* put your background color here */ }
					.coda-slider-no-js .coda-slider { height: 200px; overflow: auto !important; padding-right: 20px }
					.coda-slider, .coda-slider .panel {width: ' . $width . ';}
					.coda-slider-wrapper.arrows .coda-slider, .coda-slider-wrapper.arrows .coda-slider .panel {width: ' . $width . ';}
					.coda-slider-wrapper.arrows .coda-slider { margin: 0 10px }
					.coda-nav-left a, .coda-nav-right a { background: ' . $tab_bg . '; color: ' . $tab_color . '; padding: 3px; }
					.coda-nav ul li a.current { background: ' . $tab_active_bg . '; color: ' . $tab_active_col . '; }
					.coda-slider .panel-wrapper { padding: 20px }
					.coda-slider p.loading { padding: 20px; text-align: center }
					.coda-nav ul { clear: both; display: block; margin: auto; overflow: hidden }
					.coda-nav ul li { display: inline }
					.coda-nav ul li a { background: ' . $tab_bg . '; color: ' . $tab_color . '; display: block; float: left; margin-right: 1px; padding: 3px 6px; font-size: ' . $tab_font . '; text-decoration: none }
					.coda-slider-wrapper { clear: both; overflow: hidden; }
					.coda-slider { float: left; overflow: hidden; position: relative }
					.coda-slider .panel { display: block; float: left }
					.coda-slider .panel-container { position: relative }
					.coda-nav-right { display:none; float: right; position: absolute; right: 0; top: 50%; }
					.coda-nav-left { display:none; float: left; position: absolute; left: 0px; top: 50%; }
					.coda-nav-left a, .coda-nav-right a { background: transparent; color: #000; text-align: center; text-decoration: none; width: auto; }
					' . $slider_css . '
					</style>';

		return $content;
	}

	/**
	 * @description outputs the javascript and css on the front end
	 */
	function c3m_coda_scripts() {
		if ( is_singular () ) {
			wp_enqueue_script( 'jquery.easing', WP_PLUGIN_URL . '/wp-coda-slider/js/jquery.easing.1.3.js', array ( 'jquery' ) );
			wp_enqueue_script( 'coda_slider', WP_PLUGIN_URL . '/wp-coda-slider/js/coda.slider.js', array ( 'jquery.easing' ) );
			wp_enqueue_style( 'coda_slider_css', WP_PLUGIN_URL . '/wp-coda-slider/css/coda-slider-2.0.1.css' );
			wp_localize_script( 'coda_slider', 'Plugin_Url', array ( 'plugin_url' => WP_PLUGIN_URL . '/wp-coda-slider/images/' ) );
		}
	}

	include_once dirname( __FILE__ ) . '/lib/deprecated.php';
