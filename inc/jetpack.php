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
