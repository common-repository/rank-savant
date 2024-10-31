(function( $ ) {
	'use strict';

	/**
	 * All of the code for admin-facing JavaScript source
	 * reside in this file.
	*/
	 
	// When the window is loaded:
	$( window ).load(function() {
        $('.ranksavant-select-containers').on('change', function() {
            let id = this.value;
            if('empty' != id){
                $('.ranksavant-generated-shortcode').html('[ranksavant-integration container='+id+']');
            }else{
                $('.ranksavant-generated-shortcode').html('');
            }
        });
          
	});

})( jQuery );
