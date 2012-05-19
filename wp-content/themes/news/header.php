<?php
/**
 * Header Template
 *
 * The header template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the top of the file. It is used mostly as an opening
 * wrapper, which is closed with the footer.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
 *
 * @package News
 * @subpackage Template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<title><?php hybrid_document_title(); ?></title>

<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="all" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/scripts.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/cufon-yui.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/Charis_SIL_700.font.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/cufon-colors-ancient.js"></script>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); // WP head hook ?>
</head>

<body class="<?php hybrid_body_class(); ?>">
<?php if(is_home()){ ?>
   <div id="home-bg">
   <?php } ?>
	<?php do_atomic( 'before_html' ); // Before HTML hook ?>

	<div id="body-container">

		<?php get_template_part( 'menu', 'secondary' ); // Loads the menu-secondary.php file ?>

		<?php do_atomic( 'before_header' ); // Before header hook ?>

		<div id="header">

			<?php do_atomic( 'open_header' ); // Open header hook ?>
			
			<div class="wrap">

				<?php hybrid_site_title(); // Displays the site title ?>

				<?php get_sidebar( 'header' ); // Loads the sidebar-header.php file ?>

				<?php do_atomic( 'header' ); // Header hook ?>

			</div><!-- .wrap -->
			
			<?php do_atomic( 'close_header' ); // Close header hook ?>
             <?php get_template_part( 'menu', 'primary' ); // Loads the menu-primary.php file ?>
             <?php if(is_home()){ ?>
             <div id="navoslide">
             <?php if (function_exists('nivoslider4wp_show')) { nivoslider4wp_show(); } ?>
             </div>
             <div id="container" class="full-width">
                   		<section class="half">
                     		<h2>Welcome to HipChyk</h2>
                     		HipChyk offers many services from Full Makeovers  to putting on some false eyelashes for a night out!.
             The reason that HipChyk works this way is for various reasons but ideally  because everyones needs are as unique as the other.<br>
             &nbsp;<br>
             HipChyk started because some ladies that I knew often made comments like, `I`d  wear makeup but I don`t know how`.`Or, I wish I had time in the mornings but it  just takes too long!<br>
             I`m not in your shoes so I would never suggest that you don`t have time or  argue that you can be taught; but what I intend to do is give you an option.  That`s it - an option. Outside of going to the overwhelming desk at the  drugstore to ask for advice. Or, worse, struggle through the cosmetics aisle  where no service is provided.<br>
             &nbsp;<br>
             I`m here to help. Help you feel better about yourself. Help you get a Do Over!  Help you realize that you are beautiful and&nbsp;sexy - we`re just going to  smooth out the edges and add a bit of colour! Bring out the best you!<br>
             &nbsp;<br>
             HipChyk can provide you a multitude of services - one at a time - or all at  once. It just all depends on you.&nbsp;<br></section>
                     	<section class="one-fourth">
                		    <h2>OUR SERVICES</h2>
                     		<div>
                       			<div>
                         			<div>

                           				<div>
                            			    <img src="<?php echo get_bloginfo('template_directory'); ?>/images/scroll.png" width="241" height="256"></div>

                         			</div>
                     			</div>
                    				<div class="reviews-b"></div>
                    			</div>

             			</section>

                   		<section class="one-fourth">
                     		<h2>RECENT PHOTOS</h2>
                       			<div class="flickr">
                         			<a href="#" class="alignleft-f"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/58x58.jpg" width="58" height="58" alt="" /><i></i></a>
                         			<a href="#" class="alignleft-f"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/58x58-1.jpg" width="58" height="58" alt="" /><i></i></a>
                         			<a href="#" class="alignleft-f"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/58x58-2.jpg" width="58" height="58" alt="" /><i></i></a>
                         			<a href="#" class="alignleft-f"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/58x58-3.jpg" width="58" height="58" alt="" /><i></i></a>
                         			<a href="#" class="alignleft-f"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/58x58-4.jpg" width="58" height="58" alt="" /><i></i></a>
                         			<a href="#" class="alignleft-f"><img src="<?php echo get_bloginfo('template_directory'); ?>/images/58x58-5.jpg" width="58" height="58" alt="" /><i></i></a>
                       			</div>
             			</section>


                 	</div><!-- #container-->

             <?php } ?>
		</div><!-- #header -->

		<?php do_atomic( 'after_header' ); // After header hook ?>



		<?php do_atomic( 'before_container' ); // Before container hook ?>

		<div id="container">

			<div class="wrap">

			<?php do_atomic( 'open_container' ); // Open container hook ?>

			<?php if ( current_theme_supports( 'breadcrumb-trail' ) ) breadcrumb_trail( array( 'before' => __( 'Browsing:', hybrid_get_textdomain() ) . ' <span class="sep">/</span>' ) ); ?>