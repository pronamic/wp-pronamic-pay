/* global pronamicPayAdminTour */
jQuery( document ).ready( function( $ ) {
	$.each( pronamicPayAdminTour.pointers, function() {
		var pointer = this;

		var options =  $.extend( pointer.options, {
			buttons: function() {
				return false;
			}
		} );

		$( pointer.selector ).first().pointer( options ).pointer( 'open' );
	} );
} );
