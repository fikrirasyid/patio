<?php
/**
 * The template for displaying all single posts.
 *
 * @package Patio
 */

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>
	
		<div class="wrap">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">	
					<?php get_template_part( 'content', 'page' ); ?>
				</main><!-- #main -->
			</div><!-- #primary -->			
		</div><!-- .wrap -->

		<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		?>

	<?php endwhile; // end of the loop. ?>


<?php get_footer(); ?>
