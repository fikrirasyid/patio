jQuery(document).ready(function($){
	/* Toggle search */
	$('#masthead').on( 'click', '.search-toggle', function(e){
		e.preventDefault();

		$('#search-wrap').toggleClass( 'active' );
		$('#search-wrap .search-field').focus();
	});

	$('#search-wrap').on( 'click', '.search-toggle-close', function(e){
		e.preventDefault();
		$('#search-wrap').addClass( 'fadeOut' );

		setTimeout(function(){
			$('#search-wrap').removeClass( 'active fadeOut' );
		}, 600 );
	});

	/* Toggle Menu */
	$('#masthead').on( 'click', '.menu-toggle', function(e){
		e.preventDefault();

		var button = $(this);

		if( $('.main-navigation').is( '.toggled' ) ){
			$('.main-navigation').addClass( 'fadeOut' );

			setTimeout(function(){
				$('.main-navigation').removeClass( 'toggled fadeOut' );
				button.attr({ 'aria-expanded' : false });
			}, 400 );
		} else {
			$('.main-navigation').addClass( 'toggled' );
				button.attr({ 'aria-expanded' : true });
		}
	});

	/**
	* Auto adjust post thumbnail
	*/
	function auto_adjust_thumbnail( thumb ){
		var thumb_height 	= thumb.outerHeight();
		var img 			= thumb.find( 'img' );
		var img_width 		= img.outerWidth();
		var img_height 		= img.outerHeight();

		if( thumb_height > img_height ){
			thumb.addClass( 'height-adjustment' );
		}
	}

	$('#main').waitForImages(function(){
		$('.entry-thumbnail').each(function(){
			auto_adjust_thumbnail( $(this) );
		});
	});

	$(window).resize(function(){
		$('.entry-thumbnail').each(function(){
			$(this).waitForImages(function(){
				$(this).removeClass('height-adjustment');
				auto_adjust_thumbnail( $(this) );				
			});
		});
	});

	// Auto adjust post thumbnail in infinite scroll
    $( document.body ).on( 'post-load', function () {
    	$('.infinite-wrap:last').waitForImages(function(){
			$(this).find('.entry-thumbnail').each(function(){
				auto_adjust_thumbnail( $(this) );
			}); 
    	});
    } );

    /* If there is #infinite-handle printed, make sure that the body has .infinite-scroll class */
    /* There is an bug where the the handle is still being printed even though user disable infinite scroll at dashboard > settings > reading */
    if( $('#infinite-handle').length > 0 ){
    	$('body').addClass( 'infinite-scroll' );
    }

    /*
	* Set minimum height for hentry-wrap
    */
    if( $('.singular .entry-footer').length > 0 ){
    	// var entry_footer_height = ;

    	$('.singular .hentry-wrap').css({ 'min-height' : $('.singular .entry-footer').height() });
    }

	/**
	* Civil Footnotes Support
	* Slide the window instead of jumping it
	*/
	$('#main').on( 'click', 'a[rel="footnote"], a.backlink', function(e){
		e.preventDefault();
		var target_id = $(this).attr('href');
		var respond_offset = $(target_id).offset();

		$('html, body').animate({
			scrollTop : respond_offset.top - 90
		});
	});		
});

/* Skip Link Focus Fix */
( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
})();
