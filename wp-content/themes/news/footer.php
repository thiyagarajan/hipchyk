<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
 *
 * @package News
 * @subpackage Template
 */
?>

			<?php get_sidebar( 'secondary' ); ?>

			<?php do_atomic( 'close_container' ); // Close container hook ?>

			</div><!-- .wrap -->

		</div><!-- #container -->

		<?php do_atomic( 'after_container' ); // After container hook ?>

		<?php do_atomic( 'before_footer' ); // Before footer hook ?>

         <div class="line-footer"></div>
		<div id="footer">
              <?php
                  if(function_exists('special_recent_posts')) {
                      special_recent_posts($args);
                  }
              ?>
			<?php do_atomic( 'open_footer' ); // Open footer hook ?>

			<?php get_template_part( 'menu', 'subsidiary' ); // Loads the menu-subsidiary.php file ?>

    	<div class="foot-cont">

      		<div class="one-fourth">
        		<div class="widget twit">
          			<div class="header">CLIENTS SAY</div>
          			<div class="reviews-t">
            			<div class="coda-slider-wrapper">
             				<div class="coda-slider preload" id="coda-slider-2">


              				</div>
            			</div>

          			<div class="reviews-b"></div>
                    </div>
                    <p class="autor">Anonimus</p>
        		</div>
      		</div>
      		<div class="one-fourth">
        		<div class="widget">
          			<div class="header">RECENT POSTS</div>
                    <?php query_posts('category_name=blog&showposts=3'); ?>
                    <?php while (have_posts()) : the_post(); ?>
                    <div class="post">
                    	<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    	<div class="goto-post">
                    	    <span class="ico-link date"><?php the_date(); ?></span>
                            <span class="ico-link likes">144 likes</span>
                    	</div>
                    </div>
                     <?php endwhile; ?>
        		</div>
      		</div>
      		<div class="one-fourth">
                    		<div class="widget">
                      			<div class="header">NOW WORKING</div>
                                <?php if (function_exists('get_thethe_image_slider')) { print get_thethe_image_slider('footer'); } ?>
                    		</div>
            </div>

    		<div class="one-fourth">
      			<div class="widget">
        			<div class="header">GET IN TOUCH!</div>
        			<?php echo do_shortcode( '[contact-form-7 id="9" title="Contact form 1"]' ); ?>
    			</div>
  			</div>
  		</div>

		<!--	<div class="wrap">
			<?php do_atomic( 'footer' ); // Footer hook ?>   -->
		<!--	</div><!-- .wrap -->
              <div id="bottom">
               <?php echo apply_atomic_shortcode( 'footer_content', hybrid_get_setting( 'footer_insert' ) ); ?>
               </div>
			<?php do_atomic( 'close_footer' ); // Close footer hook ?>

		</div><!-- #footer -->
		   <?php if(is_home()){ ?>
           </div>
           <?php } ?>
		<?php do_atomic( 'after_footer' ); // After footer hook ?>

	</div><!-- #body-container -->

	<?php do_atomic( 'after_html' ); // After HTML hook ?>
	<?php wp_footer(); // WordPress footer hook ?>

</body>
</html>