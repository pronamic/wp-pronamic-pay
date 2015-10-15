/* global pronamicPayAdminPointers */
jQuery( document ).ready( function( $ ) {
	$.each( pronamicPayAdminPointers.pointers, function() {
		var pointer = this;

		var options =  $.extend( pointer.options, {
			buttons: function() {
				return false;
			}
		} );

		$( pointer.selector ).first().pointer( options ).pointer( 'open' );
	} );
} );
