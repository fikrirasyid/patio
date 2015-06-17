<?php
/**
 * Patio functions and definitions
 *
 * @package Patio
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 780; /* pixels */
}

if ( ! function_exists( 'patio_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function patio_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Patio, use a find and replace
	 * to change 'patio' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'patio', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'patio' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image'
	) );

	// Adding editor style
	add_editor_style( array(
		'//fonts.googleapis.com/css?family=Chivo:400,400italic,900,900italic',
		"editor.css"
	) );
}
endif; // patio_setup
add_action( 'after_setup_theme', 'patio_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function patio_widgets_init() {
	register_sidebars( 4, array(
		'name'          => __( 'Footer %d', 'patio' ),
		'id'            => 'footer',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'patio_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function patio_scripts() {

    wp_enqueue_style( 'patio-google-fonts', '//fonts.googleapis.com/css?family=Chivo:400,400italic,900,900italic' );
	wp_enqueue_style( 'patio-style', get_stylesheet_uri() );

	wp_register_script( 'jquery-waitforimages', get_template_directory_uri() . '/js/jquery.waitforimages.js', '1.0', array( 'jquery' ) );
	wp_enqueue_script( 'patio-script', get_template_directory_uri() . '/js/patio.js', array( 'jquery', 'jquery-waitforimages' ), '20150215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'patio_scripts' );

/**
 * Display custom color scheme
 */
if( ! function_exists( 'patio_color_scheme' ) ) :
function patio_color_scheme(){
	$name = 'link_color_scheme';

	$color_scheme = get_theme_mod( $name, false );

	if( $color_scheme ){
		wp_add_inline_style( 'patio-style', wp_strip_all_tags( $color_scheme ) );
	}
}
endif; // patio_color_scheme
add_action( 'wp_enqueue_scripts', 'patio_color_scheme' );

/**
 * Modifying excerpt suffix
 */
function patio_excerpt_more(){
	global $post;

	$more_url 		= esc_url( get_permalink( $post->ID ) );
	$more_title 	= sprintf( __( 'Permanent link to %s', 'patio' ), $post->post_title );
	$more_copy 		= __( 'Continue Reading', 'patio' );

	return "&hellip; </p><p><a href='{$more_url}' title='{$more_title}' class='more-link'>{$more_copy} &rarr;</a>";
}
add_filter( 'excerpt_more', 'patio_excerpt_more' );

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Twenty Fifteen 1.0
 *
 * @see wp_add_inline_style()
 */
function patio_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous a{ background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next a{ background-image: url(' . esc_url( $nextthumb[0] ) . '); }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	wp_add_inline_style( 'patio-style', wp_strip_all_tags( $css ) );
}
add_action( 'wp_enqueue_scripts', 'patio_post_nav_background' );

/**
 * Removing sticky posts from front page's main query
 * Sticky post which its natural position is on the first page causes posts_per_page miscalculation.
 */
function patio_pre_get_posts( $query ){
	if( ! is_admin() && $query->is_main_query() && is_home() ) {
		$query->set( 'post__not_in', get_option( 'sticky_posts' ) );
	}
}
add_action( 'pre_get_posts', 'patio_pre_get_posts' );

/**
 * Load simple color adjuster library
 */
if( ! class_exists( 'Patio_Simple_Color_Adjuster' ) ){
	require get_template_directory() . '/inc/simple-color-adjuster.php';
}
/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
