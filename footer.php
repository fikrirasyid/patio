<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Patio
 */
?>
		<?php if( ! is_singular() ) : ?>
		</div><!-- .wrap -->
		<?php endif; ?>

	</div><!-- #content -->
	
	<div id="search-wrap">
		<div class="wrap">
			<button class="search-toggle-close"><?php _e( 'Close Search', 'patio' ); ?></button>
			<?php get_search_form(); ?>
		</div><!-- .wrap -->
	</div><!-- #search-wrap -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="wrap">
			<div class="<?php patio_footer_widget_class(); ?>">

				<?php
					// Determine how many widgets are being registered
					$columns = range( 1, 4 );

					// Loop and print active widget
					foreach ( $columns as $column ) {		
						
						// Determine widget id
						if( $column == 1 ){
							$widget_id = 'footer';
						} else {
							$widget_id = 'footer-' . $column;
						}

						if( is_active_sidebar( $widget_id ) ){
							printf( '<div class="footer-widgets footer-%d">', $column );
							dynamic_sidebar( $widget_id );
							printf( '</div>' );							
						}
					}
				?>

			</div><!-- .widgets -->

			<div class="site-info">
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'patio' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'patio' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( __( 'Theme: %1$s by %2$s.', 'patio' ), 'Patio', '<a href="http://fikrirasy.id" rel="designer">Fikri Rasyid</a>' ); ?>
			</div><!-- .site-info -->			
		</div><!-- .wrap -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
