jQuery(document).ready(function($){
	/**
	* Auto adjust post thumbnail
	*/
	$('.entry-thumbnail').each(function(){
		var thumb 			= $(this);
		var thumb_height 	= thumb.outerHeight();
		var img 			= thumb.find( 'img' );
		var img_width 		= img.outerWidth();
		var img_height 		= img.outerHeight();

		if( thumb_height > img_height ){
			thumb.addClass( 'height-adjustment' );
		}
	});

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