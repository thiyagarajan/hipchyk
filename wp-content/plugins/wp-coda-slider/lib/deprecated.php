<?php
/**
 * Deprecated functions from past WP Coda Slider versions. You shouldn't use these
 * functions and look for the alternatives instead. The functions will be
 * removed in a later version.
 *
 * @package WP Coda Slider
 * @subpackage Deprecated
 */

/**
 * Deprecated functions come here to die.
 */


	/**
	 * @deprecated 0.2.5
	 * @deprecated Use c3m_slider_show() //Coming Soon next version
	 * @param $id string
	 * @param $cat integer
	 * @param $show integer
	 * @param $args string
	 */

	function c3m_wpcodaslider ( $id, $cat, $show, $args ) {
		_deprecated_function ( __FUNCTION__, '0.2.5' );
		$template_tag_args = array (
			'post_type' => 'post',
			'cat' => $cat,
			'posts_per_page' => $show
		);

		$coda_query = new WP_Query( $template_tag_args ); ?>
	<div class="coda-slider-wrapper"> <!-- yes -->
        <div class="coda-slider preload <?php echo 'id="' . $id; ?>">

		<?php if ( $coda_query->have_posts () ) : while ( $coda_query->have_posts () ) : $coda_query->the_post (); ?>

	        <div id="post-<?php the_ID (); ?>" <?php post_class ( 'panel' ); ?>>

                <div class="panel-wrapper">
                    <h2 class="title"><?php the_title (); ?></h2>
	                <?php echo apply_filters ( 'the_content', $post->post_content ); ?>
                </div> <!-- .panel-wrapper -->

            </div> <!-- .panel -->

	        <?php endwhile;
        endif;
	        wp_reset_postdata (); ?>

		</div><!-- .coda-slider .preload -->
		</div><!-- coda-slider-wrapper -->

	<?php echo'<script type="text/javascript">
        				jQuery(document).ready(function($){
                            $().ready(function() {
                                $(\'#' . $id . '\').codaSlider({' . $args . '});
                            });
        				});
                        </script>';

	}

	/**
	 * @deprecated 0.2.5
	 * @deprecated see readme for new uses
	 *
	 */

	$my_wpcodaslider = new wpcodaslider();
	class wpcodaslider {
		var $shortcode_name = 'wpcodaslider';
		var $pattern = '<!-- wpcodaslider -->';
		var $posts_content = '';

		/**
		 * @deprecated 0.2.5
		 * @deprecated use wp_codaslider()
		 */

		function wpcodaslider () {
			_deprecated_function ( __FUNCTION__, '0.2.5' );
			add_shortcode ( $this->shortcode_name, array ( &$this, 'shortcode' ) );

		}

		/**
		 * @param array $atts Extracted args from short code
		 * @param null $content
		 * @return string
		 */

		function shortcode ( $atts, $content = null ) {
			_deprecated_function ( __FUNCTION__, '0.2.5' );
			extract ( shortcode_atts ( array (
						'cat' => null,
						'id' => null,
						'show' => null,
						'args' => null
					), $atts
				)
			);


			if ( ! $cat || ! $id )
				return 'Could not load slider. Mallformed shortcode.';
			$o = '
                <div class="coda-slider-wrapper">
                        <div class="coda-slider preload" id="' . $id . '">';

			$shortcode_args = array (
				'post_type' => 'post',
				'cat' => $cat,
				'posts_per_page' => $show
			);

			$posts = get_posts ( $shortcode_args );

			foreach ( $posts as $post ) {

				$o .=
						'<div class="panel" id="post-' . $post->ID . '">
                                <div class="panel-wrapper">
                                        <h2 class="title">' . $post->post_title . '</h2>
                                        ' . apply_filters ( 'the_content', $post->post_content ) . '
                                </div> <!-- .panel-wrapper -->
                        </div><!-- .panel #post-' . $id . ' -->';
			}


			$o .= '
                                </div><!-- .coda-slider .preload -->
                        </div><!-- coda-slider-wrapper -->
                        <script type="text/javascript">
        jQuery(document).ready(function($){
                                $().ready(function() {
                                        $(\'#' . $id . '\').codaSlider({' . $args . '});
                                });
        });
                        </script>';


			return $o;
		}
	}