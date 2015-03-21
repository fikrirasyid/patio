<?php
/**
 * @package Patio
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ): ?>
	<div class="entry-featured-image">
		<?php the_post_thumbnail( 'full' ); ?>
	</div><!-- .entry-featured-image -->
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'patio' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php patio_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
