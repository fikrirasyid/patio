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
});