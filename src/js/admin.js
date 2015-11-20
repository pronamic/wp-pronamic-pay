/* global ajaxurl */
/* global fieldSettings */
( function( $ ) {
	/**
	 * Gravity Forms iDEAL feed editor
	 */
	var GravityFormsIDealFeedEditor = function( element ) {
		var obj = this;
		var $element = $( element );

		// Elements
		var elements = {};
		elements.feed = $element.find( '#gf_ideal_feed' );
		elements.gravityForm = $element.find( '#gf_ideal_gravity_form' );
		elements.formId = $element.find( '#_pronamic_pay_gf_form_id' );
		elements.configId = $element.find( '#gf_ideal_config_id' );
		elements.delayPostCreationItem = $element.find( '#gf_ideal_delay_post_creation_item' );
		elements.conditionEnabled = $element.find( '#gf_ideal_condition_enabled' );
		elements.conditionConfig = $element.find( '#gf_ideal_condition_config' );
		elements.conditionFieldId = $element.find( '#gf_ideal_condition_field_id' );
		elements.conditionOperator = $element.find( '#gf_ideal_condition_operator' );
		elements.conditionValue = $element.find( '#gf_ideal_condition_value' );
		elements.userRoleFieldId = $element.find( '#gf_ideal_user_role_field_id' );
		elements.delayNotifications = $element.find( '#gf_ideal_delay_notifications' );
		elements.delayNotificationsHolder = $element.find( '.pronamic-pay-gf-notifications' );
		elements.fieldSelectFields = $element.find( 'select.field-select' );

		// Data
		var feed = $.parseJSON( elements.feed.val() );
		var gravityForm = $.parseJSON( elements.gravityForm.val() );

		/**
		 * Update delay post creation item
		 */
		this.updateDelayPostCreationItem = function() {
			var display = false;

			if ( gravityForm ) {
				// Displaying delayed post creation setting if current form has a post field
				var postFields = obj.getFieldsByType( [ 'post_title', 'post_content', 'post_excerpt', 'post_category', 'post_custom_field', 'post_image', 'post_tag' ] );

				if ( postFields.length > 0 ) {
					display = true;
				}
			}
			
			elements.delayPostCreationItem.toggle( display );
		};

		/**
		 * Toggle condition config
		 */
		this.toggleConditionConfig = function() {
			if ( elements.conditionEnabled.prop( 'checked' ) ) {
				elements.conditionConfig.fadeIn( 'fast' );
			} else {
				elements.conditionConfig.fadeOut( 'fast' );
			}
		};
		
		/**
		 * Update condition fields
		 */
		this.updateConditionFields = function() {
			elements.conditionFieldId.empty();
			$( '<option>' ).appendTo( elements.conditionFieldId );

			if ( gravityForm ) {
				$.each( gravityForm.fields, function( key, field ) {
					var type = field.inputType ? field.inputType : field.type;
	
					var index = $.inArray( type, [ 'checkbox', 'radio', 'select' ] );
					if ( index >= 0 ) {
						var label = field.adminLabel ? field.adminLabel : field.label;

						$( '<option>' )
							.attr( 'value', field.id )
							.text (label )
							/* jshint eqeqeq: false */
							.prop( 'selected', feed.conditionFieldId == field.id )
							/* jshint eqeqeq: true */
							.appendTo( elements.conditionFieldId );
					}
				});
				
				elements.conditionOperator.val( feed.conditionOperator );
			}
		};
		
		/**
		 * Update condition values
		 */
		this.updateConditionValues = function() {
			var id	= elements.conditionFieldId.val();
			var field = obj.getFieldById( id );
			
			elements.conditionValue.empty();
			$( '<option>' ).appendTo( elements.conditionValue );

			if ( field && field.choices ) {
				$.each( field.choices, function( key, choice ) {
					var value = choice.value ? choice.value : choice.text;

					$( '<option>' )
						.attr( 'value', value )
						.text( choice.text )
						.appendTo( elements.conditionValue );
				} );
				
				elements.conditionValue.val( feed.conditionValue );
			}
		};

		/**
		 * Get field by the specified id
		 */
		this.getFieldById = function( id ) {
			if ( gravityForm ) {
				for ( var i = 0; i < gravityForm.fields.length; i++ ) {
					/* jshint eqeqeq: false */
					if ( gravityForm.fields[i].id == id ) {
						return gravityForm.fields[i];
					}
					/* jshint eqeqeq: true */
				}
			}
			
			return null;
		};

		/**
		 * Get fields by types
		 * 
		 * @param types
		 * @return Array
		 */
		this.getFieldsByType = function( types ) {
			var fields = [];

			if ( gravityForm ) {				
				for ( var i = 0; i < gravityForm.fields.length; i++ ) {
					if ( $.inArray( gravityForm.fields[i].type, types ) >= 0 ) {
						fields.push(gravityForm.fields[i]);
					}
				}
			}

			return fields;
		};
		
		this.getInputs = function() {
			var inputs = [];
			
			if ( gravityForm ) {
				$.each( gravityForm.fields, function( key, field ) {
					if ( field.inputs ) {
						$.each( field.inputs, function( key, input ) {
							inputs.push( input );
						} );
					} else if ( ! field.displayOnly ) {
						inputs.push ( field );
					}
				} );
			}
			
			return inputs;
		};
		
		/**
		 * Change form
		 */
		this.changeForm = function() {
			jQuery.get(
				ajaxurl, {
					action: 'gf_get_form_data', 
					formId: elements.formId.val()
				},
				function( response ) {
					if ( response.success ) {
						gravityForm = response.data;

						obj.updateFields();
					}
				}
			);
		};
		
		/**
		 * Update user role 
		 */
		this.updateUserRoleFields = function() {
			elements.userRoleFieldId.empty();
			$( '<option>' ).appendTo( elements.userRoleFieldId );

			if ( gravityForm ) {
				$.each( gravityForm.fields, function( key, field ) {
					var label = field.adminLabel ? field.adminLabel : field.label;
	
					$( '<option>' )
						.attr( 'value', field.id )
						.text( label )
						/* jshint eqeqeq: false */
						.prop( 'selected', feed.userRoleFieldId == field.id )
						/* jshint eqeqeq: true */
						.appendTo( elements.userRoleFieldId );
				} );
			}
		};
		
		/**
		 * Update config
		 */
		this.updateConfigFields = function() {
			var method = elements.configId.find( 'option:selected' ).attr( 'data-ideal-method' );

			$element.find( '.extra-settings' ).hide();
			$element.find( '.method-' + method ).show();
		};
		
		this.updateNotifications = function() {			
			elements.delayNotificationsHolder.empty();

			if ( gravityForm ) {
				var list = $( '<ul>' ).appendTo( elements.delayNotificationsHolder );

				$.each( gravityForm.notifications, function( key, notification ) {
					var item = $( '<li>' ).appendTo( list );
					
					var fieldId = 'pronamic-pay-gf-notification-' + notification.id;

					$( '<input type="checkbox" name="_pronamic_pay_gf_delay_notification_ids[]">' )
						.attr( 'id', fieldId )
						.val( notification.id )
						.prop( 'checked', $.inArray( notification.id, feed.delayNotificationIds ) >= 0 )
						.appendTo( item );
					
					item.append( ' ' );
					
					$( '<label>' )
						.attr( 'for', fieldId )
						.text( notification.name )
						.appendTo( item );
				} );
			}
		};
		
		/**
		 * Update select fields
		 */
		this.updateSelectFields = function() {
			if ( gravityForm ) {
				elements.fieldSelectFields.empty();

				elements.fieldSelectFields.each( function( i, element ) {
					$element = $( element );
					
					var name = $element.data( 'gateway-field-name' );

					$( '<option>' ).appendTo( $element );

					$.each( obj.getInputs(), function( key, input ) {
						var label = input.adminLabel ? input.adminLabel : input.label;

						$( '<option>' )
							.attr( 'value', input.id )
							.text( label )
							/* jshint eqeqeq: false */
							.prop( 'selected', feed.fields[name] == input.id )
							/* jshint eqeqeq: true */
							.appendTo( $element );
					} );
				} );
			}
		};
		
		/**
		 * Update fields
		 */
		this.updateFields = function() {
			obj.updateConfigFields();
			obj.updateDelayPostCreationItem();
			obj.toggleConditionConfig();
			obj.updateConditionFields();
			obj.updateConditionValues();
			obj.updateUserRoleFields();
			obj.updateSelectFields();
			obj.updateNotifications();
		};

		// Function calls
		obj.updateFields();

		elements.formId.change( obj.changeForm );
		elements.configId.change( obj.updateConfigFields );
		elements.conditionEnabled.change( obj.toggleConditionConfig );
		elements.conditionFieldId.change( obj.updateConditionValues );
		elements.delayNotifications.change( function() {
			$( 'input', elements.delayNotificationsHolder ).prop( 'checked', $( this ).prop( 'checked' ) );
		} );
	};
	
	//////////////////////////////////////////////////

	/**
	 * jQuery plugin - Gravity Forms iDEAL feed editor
	 */
	$.fn.gravityFormsIdealFeedEditor = function() {
		return this.each( function() {
			var $this = $( this );

			if ( $this.data( 'gf-ideal-feed-editor' ) ) {
				return;
			}

			var editor = new GravityFormsIDealFeedEditor( this );

			$this.data( 'gf-ideal-feed-editor', editor );
		} );
	};
	
	//////////////////////////////////////////////////

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
			var method = elements.variantId.find( 'option:selected' ).attr( 'data-ideal-method' );

			$element.find( '.extra-settings' ).hide();
			$element.find( '.method-' + method ).show();
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

	/**
	 * Ready
	 */
	$( document ).ready( function() {
		if ( typeof fieldSettings === 'object' ) {
			fieldSettings.ideal_issuer_drop_down = '.label_setting, .admin_label_setting, .size_setting, .description_setting, .css_class_setting, .error_message_setting, .rules_setting, .conditional_logic_field_setting';
			fieldSettings.pronamic_pay_payment_method_selector = '.label_setting, .admin_label_setting, .size_setting, .description_setting, .css_class_setting, .error_message_setting, .rules_setting, .conditional_logic_field_setting';
		}

		$( '.gforms_edit_form .ideal-edit-link' ).click( function( event ) {
			event.stopPropagation();
		} ); 
		
		$( '#gf-ideal-feed-editor' ).gravityFormsIdealFeedEditor();
		$( '#pronamic-pay-gateway-config-editor' ).pronamicPayGatewayConfigEditor();

		if ( 'undefined' != typeof gform ) {
			gform.addFilter( 'gform_is_conditional_logic_field', function( isConditionalLogicField, field ) {
				return 'pronamic_pay_payment_method_selector' == field.type || isConditionalLogicField;
			} );
		}
	} );
} )( jQuery );
