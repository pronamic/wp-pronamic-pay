( function( $ ) {
	/**
	 * Pronamic iDEAL config prototype
	 */
	var PronamicPayGatewayConfigEditor = function( element ) {
		var obj = this;
		var $element = $( element );

		// Elements
		var elements = {};
		elements.variantId          = $element.find( '#pronamic_gateway_id' );
		elements.extraSettings      = $element.find( 'div.extra-settings' );
		elements.sectionHeaders     = $element.find( '.gateway-config-section-header' );
		elements.tabs               = $element.find( '.pronamic-pay-tabs' );
		elements.tabItems           = $element.find( 'ul.pronamic-pay-tabs-items' );
		elements.pkCertFieldsToggle = $( '#pk-cert-fields-toggle' );

		/**
		 * Update config fields
		 */
		this.updateFields = function() {
			// Reset variant specific elements
			if ( obj.selectedVariant ) {
				elements.tabs.removeClass( obj.selectedVariant.val() );
				elements.extraSettings.find( '.show-' + obj.selectedVariant.val() ).hide();
				elements.extraSettings.find( '.hide-' + obj.selectedVariant.val() ).show();
			}

			// Find selected variant
			obj.selectedVariant = elements.variantId.find( 'option:selected' );

			obj.settings = obj.selectedVariant.data( 'pronamic-pay-settings' );

			var providerName = obj.selectedVariant.text().split( ' - ' )[0].replace( / \(.*\)/, '' );

			// Hide all settings
			$element.find( '.extra-settings' ).hide();

			// Show settings for variant
			obj.settingElements = [];

			if ( $.isArray( obj.settings ) ) {
				var settingElement;

				$.each( obj.settings, function( index, value ) {
					settingElement = $element.find( '.setting-' + value);
					settingElement.show();

					obj.settingElements.push( settingElement );
				} );
			}

			// Hide or show variant specific elements
			elements.extraSettings.find( '.show-' + obj.selectedVariant.val() ).show();
			elements.extraSettings.find( '.hide-' + obj.selectedVariant.val() ).hide();

			// Make first tab active
			elements.tabItems.find(':visible').first().text( providerName ).click();

			obj.initPkCertFields();

			obj.updateRowBackgroundColor();

			$( '#pronamic-pay-gateway-description').html( obj.selectedVariant.attr( 'data-gateway-description' ) );
		};

		// Update row background color
		this.updateRowBackgroundColor = function() {
			// Set background color of visible even rows
			var rows = elements.extraSettings.find( '.form-table tr' );

			rows.removeClass( 'even' );
			rows.filter( ':visible:even' ).addClass( 'even' );
		};

		/**
		 * Tabs
		 */
		this.initTabs = function() {
			$.each(elements.sectionHeaders, function (i, elm) {
				var item = $(elm);
				var title = item.find('h4').text();
				var settingsClasses = item.parents('div')[0].className;

				elements.tabItems.append(
					$('<li>' + title + '</li>').addClass( settingsClasses )
				);
			});

			// Move tab items list after 'Mode' setting
			elements.tabItems.next().after(elements.tabItems);

			elements.tabItems.find('li').click( obj.showTab );
		};

		this.showTab = function( ) {
			var tabItem = $( this );

			elements.tabItems.find( 'li' ).removeClass( 'active' );

			tabItem.addClass( 'active' );

			// Show tab
			elements.extraSettings.hide().eq( tabItem.index() ).show();
		};

		/**
		 * iDEAL Advanced private key and certificate fields
		 */
		this.initPkCertFields = function() {
			var fieldPrivateKey  = $( '#_pronamic_gateway_ideal_private_key' );
			var fieldCertificate = $( '#_pronamic_gateway_ideal_private_certificate' );

			if ( '' !== fieldPrivateKey.val() && '' !== fieldCertificate.val() ) {
				elements.extraSettings.find( 'tr.pk-cert' ).hide();
				elements.extraSettings.find( '.pk-cert-error' ).hide();
			} else {
				elements.extraSettings.find( '.pk-cert-ok' ).hide();
			}
		};

		this.togglePkCertFields = function( e ) {
			if ( e.preventDefault ) {
				e.preventDefault();
			}

			if ( elements.pkCertFieldsToggle.hasClass( 'active' ) ) {
				elements.pkCertFieldsToggle.removeClass( 'active' );

				elements.extraSettings.find( 'tr.pk-cert' ).hide();
			} else {
				elements.pkCertFieldsToggle.addClass( 'active' );

				elements.extraSettings.find( 'tr.pk-cert' ).show();
			}

			obj.updateRowBackgroundColor();

			return false;
		};

		/**
		 * Function calls
		 */
		obj.initTabs();

		obj.updateFields();

		elements.variantId.change( obj.updateFields );

		elements.pkCertFieldsToggle.click( obj.togglePkCertFields );

		$( window ).resize( function() {
			if ( $.isArray( obj.settingElements ) ) {
				if ( $(window).width() >= 960 ) {
					$.each( obj.settingElements, function( index, element ) {
						element.hide();
					} );

					obj.updateFields();
				} else {
					$.each( obj.settingElements, function( index, element ) {
						element.show();
					} );
				}
			}
		} );
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

		// Tooltips
		var tiptip_args = {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200,
			'maxWidth': '400px'
		};

		$( '.pronamic-pay-tip' ).tipTip( tiptip_args );
	} );
} )( jQuery );
