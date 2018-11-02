( function () {
	var __ = wp.i18n.__; // The __() function for internationalization.
	var el = wp.element.createElement; // The wp.element.createElement() function to create elements.
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType; // The registerBlockType() function to register blocks.
	var InspectorControls = wp.editor.InspectorControls; // For adding block controls.
	var TextControl = wp.components.TextControl; // For creating editable elements.
	var SelectControl = wp.components.SelectControl; // For adding toggle controls to block settings panels.var el = wp.element.createElement,

	/**
	 * Register payment button block type.
	 *
	 * @param string name     Block name.
	 * @param object settings Block settings.
	 *
	 * @return WPBlock        Block if registered successfully, otherwise "undefined".
	 */
	registerBlockType( 'pronamic-pay/payment-button', {
		title: pronamic_payment_button.title,

		icon: 'money',

		category: 'common',

		attributes: {
			config: {
				type: 'string',
				default: ''
			},
			amount: {
				type: 'string',
				default: '0'
			}
		},

		supports: {
			// Remove the support for the generated className.
			className: false,

			// Remove support for an HTML mode.
			html: false
		},

		edit: function ( props ) {
			console.log( props.attributes );
			console.log( pronamic_payment_button );

			var config = props.attributes.config,
				amount = props.attributes.amount;

			function onChangeConfig( updatedConfig ) {
			    props.setAttributes( { config: updatedConfig } );
			}

			function onChangeAmount( updatedAmount ) {
			    props.setAttributes( { amount: updatedAmount } );
			}

			var config_name = '';

			for ( var i in pronamic_payment_button.configurations ) {
				var configuration = pronamic_payment_button.configurations[ i ];

				if ( configuration.value == config ) {
					config_name = configuration.label;
				}
			}

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						Fragment,
						null,
						el(
							SelectControl,
							{
								label: pronamic_payment_button.label_configuration,
								options: pronamic_payment_button.configurations,
								value: config,
								onChange: onChangeConfig
							}
						),
						el(
							TextControl,
							{
								label: pronamic_payment_button.label_amount,
								value: amount,
								onChange: onChangeAmount
							}
						)
					)
				),
				el(
					'p',
					null,
					'€ ' + amount + ' afrekenen via configuratie ' + config_name + '.',
					el( 'br' ),
					el( 'br' ),
					el(
						'button',
						{
							className: 'pronamic-pay-submit pronamic-pay-btn'
						},
						pronamic_payment_button.pay_now
					)
				)
			);

			console.log( config_name )
		},

		save: function () {
			return null;
		}
	} );
} )();
