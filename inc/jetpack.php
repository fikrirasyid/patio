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
	// Adding infinite scroll support
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
		'posts_per_page' => 10
	) );

	// Adding site logo support
	add_image_size( 'site-logo', 0, 39 );

	$site_logo_args = array(
	    'header-text' => array(
	        'site-title'
	    ),
	    'size' => 'site-logo',
	);

	add_theme_support( 'site-logo', $site_logo_args );	
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

/**
 * Auto set jetpack comments' color scheme to dark due to #comments background
 * 
 * @see Jetpack_Comments->set_default_color_theme_based_on_theme_settings() at /jetpack/modules/comments/comments.php
 */
function patio_jetpack_set_color_scheme_to_dark(){
	return 'dark';
}
add_filter( 'theme_mod_color_scheme', 'patio_jetpack_set_color_scheme_to_dark' );