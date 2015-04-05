<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'patio' ) ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Type keyword and hit enter to search...', 'placeholder', 'patio' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'patio' ) ?>" />
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'patio' ) ?>" />
</form>