/* globals pronamic_fixed_price_payment_button */
( function ( blocks, components, editor, element ) {
	var el = element.createElement;
	var Fragment = element.Fragment;
	var InspectorControls = editor.InspectorControls;
	var TextControl = components.TextControl;
	var ServerSideRender = components.ServerSideRender;

	/**
	 * Register payment button block type.
	 *
	 * @param string name     Block name.
	 * @param object settings Block settings.
	 *
	 * @return WPBlock        Block if registered successfully, otherwise "undefined".
	 */
	blocks.registerBlockType( 'pronamic-pay/fixed-price-payment-button', {
		title: pronamic_fixed_price_payment_button.title,
		icon: 'money',
		category: 'common',

		// Attributes.
		attributes: {
			amount: {
				type: 'string'
			}
		},

		// Feature supports.
		supports: {
			// Remove support for an HTML mode.
			html: false
		},

		// Edit.
		edit: function ( props ) {
			var amount = props.attributes.amount;

			function onChangeAmount( updatedAmount ) {
			    props.setAttributes( { amount: updatedAmount } );
			}

			return el( Fragment, null,

				// Inspector controls.
				el( InspectorControls, null,
					el( Fragment, null,
						el( TextControl, {
							label: pronamic_fixed_price_payment_button.label_amount,
							value: amount,
							onChange: onChangeAmount
						} )
					)
				),

				// Server side render.
				el( ServerSideRender, {
					block: 'pronamic-pay/fixed-price-payment-button',
					attributes: props.attributes
				} )
			);
		},

		// Save.
		save: function () {
			return null;
		}
	} );
} )(
	window.wp.blocks,
	window.wp.components,
	window.wp.editor,
	window.wp.element
);
