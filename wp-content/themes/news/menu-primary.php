<?php
/**
 * Primary Menu Template
 *
 * Displays the Primary Menu if it has active menu items.
 *
 * @package News
 * @subpackage Template
 */

if ( has_nav_menu( 'primary' ) ) : ?>

	<div id="menu-primary" class="menu-container">

		<div class="wrap">

			<?php do_atomic( 'before_menu_primary' ); // Before primary menu hook ?>

			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-primary-items', 'fallback_cb' => '' ) ); ?>

			<?php do_atomic( 'after_menu_primary' ); // After primary menu hook ?>
			<ul class="soc-ico">
                         			<li><a class="rss trigger" href="#"><span>Our RSS</span></a></li>
                          			<li><a class="dribble trigger" href="#"><span>Follow Us on Dribbble</span></a></li>
                          			<li><a class="facebook trigger" href="#"><span>Follow Us on Facebook</span></a></li>
                          			<li><a class="twitter trigger" href="#"><span>Follow Us on Twitter</span></a></li>
                          			<li><a class="google trigger" href="#"><span>Follow Us on Google+</span></a></li>
                                    <li><a class="delicious trigger" href="#"><span>Follow Us on Delicious</span></a></li>
                          			<li><a class="flickr trigger" href="#"><span>Follow Us on Flickr</span></a></li>
                          			<li><a class="forrst trigger" href="#"><span>Follow Us on Forrst</span></a></li>
                                    <li><a class="lastFM trigger" href="#"><span>Follow Us on LastFM</span></a></li>
                          			<li><a class="linkedin trigger" href="#"><span>Follow Us on Linkedin</span></a></li>
                          			<li><a class="mySpace trigger" href="#"><span>Follow Us on MySpace</span></a></li>
                                    <li><a class="vimeo trigger" href="#"><span>Follow Us on Vimeo</span></a></li>
                          			<li><a class="youTube trigger" href="#"><span>Follow Us on YouTube</span></a></li>
             </ul>

		</div>

	</div><!-- #menu-primary .menu-container -->

<?php endif; ?>