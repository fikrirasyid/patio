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
					<?php get_template_part( 'content', 'single' ); ?>
			
					<?php
						the_post_navigation( array(
							'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'See Next', 'patio' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Next post:', 'patio' ) . '</span> ' .
								'<span class="post-title">%title</span>',
							'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previously', 'patio' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Previous post:', 'patio' ) . '</span> ' .
								'<span class="post-title">%title</span>',
						) );
					?>
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
