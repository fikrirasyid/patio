<?php
/**
 * @package Patio
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" title="<?php printf( __( 'Permanent link to %s', 'patio' ), strip_tags( get_the_title() ) ); ?>" class="entry-thumbnail">
			<?php the_post_thumbnail(); ?>
		</a>
	<?php endif; ?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php patio_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if( ! has_post_thumbnail() ): ?>
	<div class="entry-excerpt">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->		
	<?php endif; ?>
</article><!-- #post-## -->