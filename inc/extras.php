<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Patio
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function patio_body_classes( $classes ) {
	
	if( is_singular() ){
		$classes[] = 'singular';
	} else {
		$classes[] = 'not-singular';		
	}

	return $classes;
}
add_filter( 'body_class', 'patio_body_classes' );

/**
 * Post class modification
 */
function patio_post_classes( $classes ){
	global $wp_query, $post;

	if( isset( $wp_query->query_vars['paged'] ) ){
		$paged = $wp_query->query_vars['paged'];
	} else {
		$paged = 1;
	}

	// Skip sticky post
	if( is_home() && is_sticky( $post->ID ) && $paged < 2 ){
		return $classes;
	}

	if( ! isset( $wp_query->count_post ) ){
		$wp_query->count_post = 1;
	} else {
		$wp_query->count_post++;
	}

	$post_count = $wp_query->count_post % 5;

	if( in_array( $post_count, array( 0, 3, 4 ) ) ){
		$classes[] = 'three-column';
	}

	$classes[] = 'lock-' . $post_count;

	return $classes;
}
add_filter( 'post_class', 'patio_post_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function patio_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'patio' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'patio_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function patio_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'patio_render_title' );
endif;

/**
 * Footer widgets class
 */
function patio_footer_widget_class(){
	// Basic footer widget class
	$classes = array( 'footer-widgets-wrap' );

	$active_widget = 0;

	$widgets = range( 1, 4 );

	foreach ( $widgets as $widget ) {		

		// Determine widget id
		$widget_id = 'footer';

		if( $widget > 1 ){
			$widget_id .= '-' . $widget;
		}

		// If the widget is active, adds widget count
		if( is_active_sidebar( $widget_id ) ){
			$active_widget++;			
		}
	}	

	// Determine number fo active widget
	$classes[] = 'column-' . $active_widget;

	// Print the classes
	echo implode( ' ', apply_filters( 'patio_footer_widget_class', $classes ) );
}

/**
 * If the first 20 characters of title is equal to the first 20 characters of content, 
 * there is a chance that the title is generated from content. Auto hide this redundancy
 * 
 * @return bool
 */
if( ! function_exists( 'patio_is_auto_hide_title' ) ) :
function patio_is_auto_hide_title(){
	global $post;

	if( substr( $post->post_title, 0, 20 ) == substr( strip_tags( $post->post_content ), 0, 20 ) ){
		$do = true;
	} else {
		$do = false;
	}

	return apply_filters( 'patio_is_auto_hide_title', $do, $post );
}
endif;