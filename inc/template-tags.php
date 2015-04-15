<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Patio
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'patio' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'patio' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'patio' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'patio' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'patio_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function patio_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$time = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
	printf( '%s <span class="separator">&bull;</span> ', $time );

	// Display author data if author data exists. In some rare cases, imported / generated post might have not have post author data 
	$author_id = get_the_author_meta( 'ID' );
	if( $author_id ){
		$author = '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
		printf( '%s <span class="separator">&bull;</span> ', $author );
	}

	echo '<span class="comments-link">';
	comments_popup_link( __( 'Add your comment', 'patio' ), __( '1 Comment', 'patio' ), __( '% Comments', 'patio' ) );
	echo '</span>';

}
endif;

if ( ! function_exists( 'patio_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function patio_entry_footer() {
	// Display author link
	$author = get_the_author();
	if( $author ){
		printf( '<div class="author-link item"><a href="%s" title="%s">%s</a></div>', get_author_posts_url( get_the_author_meta( 'ID' ) ), sprintf( __( 'Posts written by %s', 'patio' ), $author ), $author );
	}

	// Display published date
	printf( '<div class="date-link item">%s</div>', get_the_date() );

	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'patio' ) );
		if ( $categories_list && patio_categorized_blog() ) {
			printf( '<span class="cat-link item">%s</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'patio' ) );
		if ( $tags_list ) {
			printf( '<span class="tag-link item">%s</span>', $tags_list );
		}
	}

	echo '<span class="comment-link item">';
	comments_popup_link( __( 'Leave a comment', 'patio' ), __( '1 Comment', 'patio' ), __( '% Comments', 'patio' ) );
	echo '</span>';


	edit_post_link( __( 'Edit', 'patio' ), '<span class="edit-link item">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'patio' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'patio' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'patio' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'patio' ), get_the_date( _x( 'Y', 'yearly archives date format', 'patio' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'patio' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'patio' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'patio' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'patio' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'patio' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'patio' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'patio' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'patio' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'patio' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function patio_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'patio_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'patio_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so patio_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so patio_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in patio_categorized_blog.
 */
function patio_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'patio_categories' );
}
add_action( 'edit_category', 'patio_category_transient_flusher' );
add_action( 'save_post',     'patio_category_transient_flusher' );

/**
 * Manually display sticky posts at the front page
 * @see patio_pre_get_posts()
 */
function patio_front_page_sticky_posts(){
	if( is_home() && ! is_paged() ){

		$sticky_posts = get_option( 'sticky_posts' );

		if( is_array( $sticky_posts ) && ! empty( $sticky_posts ) ){
			
			// Query sticky posts
			$front_page_query = new WP_Query( array(
				'post__in' => $sticky_posts
			) );

			// Loop
			if( $front_page_query->have_posts() ){

				while ( $front_page_query->have_posts() ) {
					$front_page_query->the_post();

					// Display posts
					get_template_part( 'content', get_post_format() );
				}

			}

			wp_reset_postdata();

		}

	}	
}