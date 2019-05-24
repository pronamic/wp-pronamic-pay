/* globals pronamic_payment_form */
( function ( blocks, components, editor, element ) {
	var el = element.createElement;
	var Fragment = element.Fragment;
	var InspectorControls = editor.InspectorControls;
	var Button = components.Button;
	var Placeholder = components.Placeholder;
	var TextControl = components.TextControl;
	var ServerSideRender = components.ServerSideRender;

	/**
	 * Register payment form block type.
	 *
	 * @param string name     Block name.
	 * @param object settings Block settings.
	 *
	 * @return WPBlock        Block if registered successfully, otherwise "undefined".
	 */
	blocks.registerBlockType( 'pronamic-pay/payment-form', {
		title: pronamic_payment_form.title,
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
			var hasSettingsSet = props.attributes && parseInt( amount ) > 0;

			function onChangeAmount( updatedAmount ) {
				props.setAttributes( { amount: updatedAmount } );
			}

			return el( Fragment, null,

				// Inspector controls.
				el( InspectorControls, null,
					el( Fragment, null,
						el( TextControl, {
							label: pronamic_payment_form.label_amount,
							value: amount,
							onChange: onChangeAmount
						} )
					)
				),

				// Setup required props.
				! hasSettingsSet &&
				el( Placeholder, {
						label: pronamic_payment_form.title,
						icon: 'money'
					},
					el( Fragment, null,
						el( TextControl, {
							label: pronamic_payment_form.label_amount,
							onChange: function ( value ) {
							},
							onBlur: function () {
								onChangeAmount( event.target.value );
							}
						} ),
						el( 'div',
							{
								style: { width: '100%' }
							},
							el( Button,
								{ isPrimary: true },
								el( Fragment, null, pronamic_payment_form.label_add_form )
							)
						)
					)
				),

				// Server side render.
				hasSettingsSet && el( ServerSideRender, {
					block: 'pronamic-pay/payment-form',
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
