<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Patio
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function patio_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
		'posts_per_page' => 10
	) );
}
add_action( 'after_setup_theme', 'patio_jetpack_setup' );

/**
 * Adjust infinite scroll's footer_widgets parameter based on active state of widgets
 * See: http://jetpack.me/support/infinite-scroll/
 */
function patio_jetpack_infinite_scroll_has_footer_widgets(){

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

	if( $active_widget > 0 ){
		
		return true;

	} else {

		return false;

	}

}
add_filter( 'infinite_scroll_has_footer_widgets', 'patio_jetpack_infinite_scroll_has_footer_widgets' );