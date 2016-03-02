( function( $ ) {
	/**
	 * Pronamic iDEAL config prototype
	 */
	var PronamicPayGatewayConfigEditor = function( element ) {
		var obj = this;
		var $element = $( element );

		// Elements
		var elements = {};
		elements.variantId = $element.find( '#pronamic_gateway_id' );

		/**
		 * Update config
		 */
		this.updateConfigFields = function() {
			var settings = elements.variantId.find( 'option:selected' ).data( 'pronamic-pay-settings' );

			$element.find( '.extra-settings' ).hide();

			if ( $.isArray( settings ) ) {
				$.each( settings, function( index, value ) {
					$element.find( '.setting-' + value ).show();
				} );
			}
		};

		/**
		 * Update fields
		 */
		this.updateFields = function() {
			obj.updateConfigFields();
		};

		// Function calls
		obj.updateFields();

		elements.variantId.change( obj.updateConfigFields );
	};

	/**
	 * jQuery plugin - Pronamic iDEAL config editor
	 */
	$.fn.pronamicPayGatewayConfigEditor = function() {
		return this.each( function() {
			var $this = $( this );

			if ( $this.data( 'pronamic-pay-gateway-config-editor' ) ) {
				return;
			}

			var editor = new PronamicPayGatewayConfigEditor( this );

			$this.data( 'pronamic-pay-gateway-config-editor', editor );
		} );
	};

	//////////////////////////////////////////////////

	/**
	 * Pronamic iDEAL pay form options
	 */
	var PronamicPayFormOptions = function( element ) {
		var obj = this;
		var $element = $( element );

		// Elements
		var elements = {};
		elements.amountMethod = $element.find( 'select[name="_pronamic_payment_form_amount_method"]' );

		/**
		 * Update amounts visibility
		 */
		this.updateAmountsVisibility = function() {
			var method = elements.amountMethod.val();

			if ( method === 'choices_only' || method === 'choices_and_input' ) {
				$element.find('input[name="_pronamic_payment_form_amount_choices\[\]"]').closest('div').show();
			} else {
				$element.find('input[name="_pronamic_payment_form_amount_choices\[\]"]').closest('div').hide();
			}
		};

		/**
		 * Maybe add an empty amount field
		 */
		this.maybeAddAmountChoice = function() {
			elements.amountChoices = $element.find( 'input[name="_pronamic_payment_form_amount_choices\[\]"]' );
			var emptyChoices       = elements.amountChoices.filter( function() { return this.value === ''; } );

			if ( emptyChoices.length === 0 ) {
				var lastChoice = elements.amountChoices.last().closest( 'div' );
				var newChoice  = lastChoice.clone();
				var choiceId   = '_pronamic_payment_form_amount_choice_' + elements.amountChoices.length;

				newChoice.find( 'input' ).attr( 'id', choiceId ).val( '' );
				newChoice.find( 'label' ).attr( 'for', choiceId );

				lastChoice.after( newChoice );
			}
		};

		// Function calls
		obj.updateAmountsVisibility();

		elements.amountMethod.change( obj.updateAmountsVisibility );

		$element.on( 'keyup', 'input[name="_pronamic_payment_form_amount_choices\[\]"]', function() {
			obj.maybeAddAmountChoice();
		});
	};

	/**
	 * jQuery plugin - Pronamic iDEAL form options
	 */
	$.fn.pronamicPayFormOptions = function() {
		return this.each( function() {
			var $this = $( this );

			if ( $this.data( 'pronamic-pay-forms-options' ) ) {
				return;
			}

			var formOptions = new PronamicPayFormOptions( this );

			$this.data( 'pronamic-pay-form-options', formOptions );
		} );
	};

	/**
	 * Ready
	 */
	$( document ).ready( function() {
		$( '#pronamic-pay-gateway-config-editor' ).pronamicPayGatewayConfigEditor();
		$( '#pronamic_payment_form_options').pronamicPayFormOptions();
	} );
} )( jQuery );
