<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Patio
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'patio' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="wrap">
			<div class="site-branding">
				<?php
					if( function_exists( 'jetpack_the_site_logo' ) ){
					    jetpack_the_site_logo();
					}
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			</div><!-- .site-branding -->
			
			<button class="search-toggle"><?php _e( 'Search', 'patio' ); ?></button>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php _e( 'Primary Menu', 'patio' ); ?></button>

				<div class="menu-wrap">
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>					
				</div><!-- .menu-wrap -->
			</nav><!-- #site-navigation -->
		</div><!-- .wrap -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">

		<?php if( ! is_singular() ) : ?>
		<div class="wrap">
		<?php endif; ?>