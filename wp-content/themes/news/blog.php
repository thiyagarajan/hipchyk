<?php
/*
Template Name: Blog
*/
?>
<?php get_header(); ?>

		<div id="container">
			<div id="content">
			<h1><?php echo get_the_title(); ?></h1>
<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array( 'category__and' => array(4), 'post_type' => 'post', 'posts_per_page' => 2, 'paged' => $paged );
$wp_query = new WP_Query($args);
while ( have_posts() ) : the_post(); ?>
<div class="item-blog">
    <h2><?php the_title() ?></h2>
    <div class="entry-meta">
              				<a class="ico-link date" href=""> <?php the_date(); ?> </a>
              				<a class="ico-link author" href=""><?php the_author() ?></a>
              				<span class="ico-link categories">
                				<?php
                                $category = get_the_category();
                                echo $category[0]->cat_name;
                                ?>
              				</span>
              				<a href="#" class="button" title=""><span><i class="lik"></i>Like! (<?php echo showLikeCount(get_the_ID());?>)</span></a>
              				<a href="#" class="button" title=""><span><i class="commen"></i>Coments (29)</span></a>
            			</div>
    <div class="entry-content">
    							<?php the_content(); ?>
    							<?php wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', hybrid_get_textdomain() ), 'after' => '</p>' ) ); ?>
    						</div><!-- .entry-content -->
</div>
<?php endwhile; ?>

<?php get_template_part( 'loop-nav' ); ?>
			</div><!-- #content -->

<?php get_sidebar( 'primary' ); ?>
		</div><!-- #container -->

<?php get_footer(); ?>
