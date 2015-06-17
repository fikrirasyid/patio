<?php
/**
 * Patio Theme Customizer
 *
 * @package Patio
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function patio_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';

	// Remove jetpack's site-logo header text control. We're doing it automatically
	// logo exists means hidden text. vice versa.
	$wp_customize->remove_control( 'site_logo_header_text' );

	// Adding link color control
	$wp_customize->add_setting( 'link_color', array(
		'default'           => '#FF8F00',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'       => esc_html__( 'Link color', 'patio' ),
		'description' => esc_html__( 'Select color for your link', 'patio' ),
		'section'     => 'colors',
	) ) );
}
add_action( 'customize_register', 'patio_customize_register', 20 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function patio_customize_preview_js( $wp_customize ) {
	wp_enqueue_script( 'patio_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20150404', true );

	// Default params
	$patio_customizer_params = array(
		'generate_color_scheme_endpoint' 		=> esc_url( admin_url( 'admin-ajax.php?action=patio_generate_customizer_color_scheme' ) ),
		'generate_color_scheme_error_message' 	=> __( 'Error generating color scheme. Please try again.', 'patio' ),
		'clear_customizer_settings'				=> esc_url( admin_url( 'admin-ajax.php?action=patio_clear_customizer_settings' ) )
	);

	// Adding proper error message when customizer fails to generate color scheme in live preview mode (theme hasn’t been activated).
	// The color scheme is generated using wp_ajax and wp_ajax cannot be registered if the theme hasn’t been activated.
	if( ! $wp_customize->is_theme_active() ){
		$patio_customizer_params['generate_color_scheme_error_message'] = __( 'Color scheme cannot be generated. Please activate Patio theme first.', 'patio' );
	}

	// Attaching variables
	wp_localize_script( 'patio_customizer', 'patio_customizer_params', apply_filters( 'patio_customizer_params', $patio_customizer_params ) );

	// Display color scheme previewer
	$color_scheme = get_theme_mod( 'link_color_scheme_customizer', false );

	if( $color_scheme ){
		remove_action( 'wp_enqueue_scripts', 'patio_color_scheme' );

		/**
		 * Reload default style, wp_add_inline_style fail when the handle doesn't exist
		 */
		wp_enqueue_style( 'patio-style', get_stylesheet_uri() );

		$inline_style = wp_add_inline_style( 'patio-style', wp_strip_all_tags( $color_scheme ) );
	}
}
add_action( 'customize_preview_init', 'patio_customize_preview_js' );

/**
 * WordPress' native sanitize_hex_color seems to be hasn't been loaded
 * Provide theme's customizer with its own hex color sanitation
 */
if( ! function_exists( 'patio_sanitize_hex_color' ) ) :
function patio_sanitize_hex_color( $color ){
	if ( '' === $color ){
		return '';
	}

	// If given string has no hash, auto add hash
	if( '#' != substr( $color, 0, 1 ) ){
		$color = '#' . $color;
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;

	return null;
}
endif;

if ( ! function_exists( 'patio_sanitize_hex_color_no_hash' ) ) :
function patio_sanitize_hex_color_no_hash( $color ){
	$color = ltrim( $color, '#' );

	if ( '' === $color )
		return '';

	return patio_sanitize_hex_color( '#' . $color ) ? $color : null;
}
endif;


/**
 * Generate color scheme based on color given
 *
 * @uses Patio_Simple_Color_Adjuster
 */
if( ! function_exists( 'patio_generate_color_scheme_css' ) ) :
function patio_generate_color_scheme_css( $color, $mode = false ){

	// If given string has no hash, auto add hash
	if( '#' != substr( $color, 0, 1 ) ){
		$color = '#' . $color;
	}

	// Verify color
	if( ! patio_sanitize_hex_color( $color ) ){
		return false;
	}

	$simple_color_adjuster = new Patio_Simple_Color_Adjuster;

	switch ( $mode ) {

		case 'link_color':

			$css = "
.comment-body a:hover,
.entry-content a:hover {
  color: {$color};
}

body {
  border-top: 3px solid {$color};
  border-bottom: 3px solid {$color};
}

button,
input[type='button'],
input[type='reset'],
input[type='submit'] {
  background: {$color};
}

input[type='text']:focus,
input[type='email']:focus,
input[type='url']:focus,
input[type='password']:focus,
input[type='search']:focus,
textarea:focus {
  color: #111;
  border-bottom: 3px solid {$color};
}

.main-navigation .menu-wrap {
  background: {$color};
}

.main-navigation .menu-wrap:before {
  background: {$color};
}

.comment-navigation a:hover,
.posts-navigation a:hover {
  background: {$color};
}

@media screen and (min-width: 1000px) {

	.main-navigation li.current-menu-item > a, .main-navigation li:hover > a {
	color: {$color};
	}

	.main-navigation .page_item_has_children:hover > a,
	.main-navigation .menu-item-has-children:hover > a {
	background: {$color};
	}

	.main-navigation .children,
	.main-navigation .sub-menu {
		background: {$color};
	}
}

#masthead .site-title a:hover {
  color: {$color};
}

.not-singular .hentry .entry-title a:hover {
  color: {$color};
}

.not-singular .hentry .entry-excerpt .more-link:hover {
  color: {$color};
}

.singular .entry-footer a:hover {
  color: {$color};
}

.not-found a:hover {
  color: {$color};
}

.comment-body a.comment-reply-link:hover {
  background: {$color};
}

#cancel-comment-reply-link:hover {
  color: {$color};
  border-color: {$color};
}

#colophon .footer-widgets-wrap .footer-widgets a:hover {
  color: {$color};
}

#colophon .footer-widgets-wrap .widget_rss .widget-title a:hover {
  color: {$color};
}

#colophon .footer-widgets-wrap .widget_rss .rsswidget {
  color: {$color};
}

#colophon .footer-widgets-wrap .widget_facebook_likebox .widget-title a:hover {
  color: {$color};
}

#colophon .site-info a:hover {
  color: {$color};
}

#infinite-handle span button:hover {
  background: {$color};
}

/* Lighten */
button:active,
input[type='button']:active,
input[type='reset']:active,
input[type='submit':active],
button:focus,
input[type='button']:focus,
input[type='reset']:focus,
input[type='submit':focus]{
	background: ". $simple_color_adjuster->lighten( $color, 10 ) .";
}

.main-navigation .page_item_has_children:hover .children li a:hover,
.main-navigation .page_item_has_children:hover .sub-menu li a:hover,
.main-navigation .menu-item-has-children:hover .children li a:hover,
.main-navigation .menu-item-has-children:hover .sub-menu li a:hover {
	background: ". $simple_color_adjuster->lighten( $color, 10 ) .";
}

.comment-body h3,
.entry-content h3 {
  border-bottom: 1px dotted ". $simple_color_adjuster->lighten( $color, 70 ) .";
}

/* Darken */

button:hover,
input[type='button']:hover,
input[type='reset']:hover,
input[type='submit']:hover {
	background: ". $simple_color_adjuster->darken( $color, 10 ) .";
}

.comment-body a.comment-reply-link:active {
	background: ". $simple_color_adjuster->darken( $color, 10 ) .";
}
			";

			break;

		default:

			$css = false;

			break;
	}

	return $css;
}
endif;

/**
 * AJAX endpoint for generating color scheme in near real time for customizer
 */
if( ! function_exists( 'patio_generate_customizer_color_scheme' ) ) :
function patio_generate_customizer_color_scheme(){

	if( current_user_can( 'customize' ) ){

		// Determine color key
		if( isset( $_GET['link_color'] ) && patio_sanitize_hex_color_no_hash( $_GET['link_color'] ) ){
			$prefix = 'link';
		} else {
			$prefix = false;
		}

		// Saving color
		if( $prefix ){

			$key = $prefix . '_color';

			// Get color
			$color = patio_sanitize_hex_color_no_hash( $_GET[ $key ] );

			if( $color ){

				$color = '#' . $color;

				// Generate color scheme css
				$css = patio_generate_color_scheme_css( $color, $key );

				// Set Color Scheme
				set_theme_mod( $key . '_scheme_customizer', wp_strip_all_tags( $css ) );

				$generate = array( 'status' => true, 'colorscheme' => $css );

			} else {

				$generate = array( 'status' => false, 'colorscheme' => false );

			}

		} else {

			$generate = array( 'status' => false, 'colorscheme' => false );

		}

	} else {

		$generate = array( 'status' => false, 'colorscheme' => false );

	}

	// Transmit message
	echo json_encode( $generate );

	die();
}
endif;
add_action( 'wp_ajax_patio_generate_customizer_color_scheme', 'patio_generate_customizer_color_scheme' );

/**
 * Generate color scheme based on color choosen by user
 */
if ( ! function_exists( 'patio_save_color_scheme' ) ) :
function patio_save_color_scheme(){

	$key = 'link_color';

	$color = get_theme_mod( $key, false );

	if( $color ){

		$css = patio_generate_color_scheme_css( $color, $key );

		if( $css ){

			// Set color scheme
			set_theme_mod( $key . '_scheme', wp_strip_all_tags( $css ) );

			// Remove customizer scheme
			remove_theme_mod( $key . '_scheme_customizer' );

		}
	}
}
endif;
add_action( 'customize_save_after', 'patio_save_color_scheme' );

/**
 * Endpoint for clearing all customizer temporary settings
 * This is made to be triggered via JS call (upon tab is closed)
 *
 * @return void
 */
if( ! function_exists( 'patio_clear_customizer_settings' ) ) :
function patio_clear_customizer_settings(){
	if( current_user_can( 'customize' ) ){
		remove_theme_mod( 'link_color_scheme_customizer' );
	}

	die();
}
endif;
add_action( 'wp_ajax_patio_clear_customizer_settings', 'patio_clear_customizer_settings' );