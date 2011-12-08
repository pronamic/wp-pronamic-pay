(function($) {
	/**
	 * Gravity Forms iDEAL feed editor
	 */
	var GravityFormsIDealFeedEditor = function(element, options) {
		var obj = this;
		var element = $(element);

		// Elements
		var elements = {};
		elements.feed = element.find("#gf_ideal_feed");
		elements.gravityForm = element.find("#gf_ideal_gravity_form");
		elements.formId = element.find("#gf_ideal_form_id");
		elements.configurationId = element.find("#gf_ideal_configuration_id");
		elements.conditionEnabled = element.find("#gf_ideal_condition_enabled");
		elements.conditionConfig = element.find("#gf_ideal_condition_config");
		elements.conditionFieldId = element.find("#gf_ideal_condition_field_id");
		elements.conditionOperator = element.find("#gf_ideal_condition_operator");
		elements.conditionValue = element.find("#gf_ideal_condition_value");
		elements.userRoleFieldId = element.find("#gf_ideal_user_role_field_id");
		elements.fieldSelectFields = element.find("select.field-select");

		// Data
		var feed = $.parseJSON(elements.feed.val());
		var gravityForm = $.parseJSON(elements.gravityForm.val());

		/**
		 * Toggle condition config
		 */
		this.toggleConditionConfig = function() {
			if(elements.conditionEnabled.prop("checked")) {
				elements.conditionConfig.fadeIn("fast");
			} else {
				elements.conditionConfig.fadeOut("fast");
			}
		};
		
		/**
		 * Update condition fields
		 */
		this.updateConditionFields = function() {
			elements.conditionFieldId.empty();
			$("<option>").appendTo(elements.conditionFieldId);

			if(gravityForm) {
				$.each(gravityForm.fields, function(key, field) {
					var type = field.inputType ? field.inputType : field.type;
	
					var index = $.inArray(type, ["checkbox", "radio", "select"]);
					if(index >= 0) {
						var label = field.adminLabel ? field.adminLabel : field.label;

						$("<option>")
							.attr("value", field.id)
							.text(label)
							.prop("selected", feed.conditionFieldId == field.id)
							.appendTo(elements.conditionFieldId);
					}
				});
				
				elements.conditionOperator.val(feed.conditionOperator);
			}
		};
		
		/**
		 * Update condition values
		 */
		this.updateConditionValues = function() {
			var id = elements.conditionFieldId.val();
			var field = obj.getFieldById(id);
			
			elements.conditionValue.empty();
			$("<option>").appendTo(elements.conditionValue);

			if(field && field.choices) {
				$.each(field.choices, function(key, choice) {
                    var value = choice.value ? choice.value : choice.text;

    				$("<option>")
						.attr("value", value)
						.text(choice.text)
						.appendTo(elements.conditionValue);
				});
				
				elements.conditionValue.val(feed.conditionValue);
			}
		};

		/**
		 * Get field by the specified id
		 */
		this.getFieldById = function(id) {
			if(gravityForm) {
				for(var i = 0; i < gravityForm.fields.length; i++) {
					if(gravityForm.fields[i].id == id) {
						return gravityForm.fields[i];
					}
				}
			}
			
			return null;
		};
		
		/**
		 * Change form
		 */
		this.changeForm = function() {
			jQuery.get(
				ajaxurl, {
					action: "gf_get_form_data" , 
					formId: elements.formId.val()
				} , 
				function(response) {
					if(response.success) {
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
			$("<option>").appendTo(elements.userRoleFieldId);

			if(gravityForm) {
				$.each(gravityForm.fields, function(key, field) {
	                var label = field.adminLabel ? field.adminLabel : field.label;
	
					$("<option>")
						.attr("value", field.id)
						.text(label)
						.prop("selected", feed.userRoleFieldId == field.id)
						.appendTo(elements.userRoleFieldId);
				});
			}
		};
		
		/**
		 * Update configuration
		 */
		this.updateConfigurationFields = function() {
			var method = elements.configurationId.find("option:selected").attr("data-ideal-method");

			element.find(".extra-settings").hide();
			element.find(".method-" + method).show();
		};
		
		/**
		 * Update fields
		 */
		this.updateFields = function() {
			obj.updateConfigurationFields();
			obj.toggleConditionConfig();
			obj.updateConditionFields();
			obj.updateConditionValues();
			obj.updateUserRoleFields();
		};

		// Function calls
		obj.updateFields();

		elements.formId.change(obj.changeForm);
		elements.configurationId.change(obj.updateConfigurationFields);
		elements.conditionEnabled.change(obj.toggleConditionConfig);
		elements.conditionFieldId.change(obj.updateConditionValues);
	};
	
	//////////////////////////////////////////////////

	/**
	 * jQuery plugin - Gravity Forms iDEAL feed editor
	 */
	$.fn.gravityFormsIdealFeedEditor = function(options) {
		return this.each(function() {
			var element = $(this);

			if(element.data("gf-ideal-feed-editor")) return;

			var editor = new GravityFormsIDealFeedEditor(this, options);

			element.data("gf-ideal-feed-editor", editor);
		});
	};
	
	//////////////////////////////////////////////////

	/**
	 * Pronamic iDEAL configuration prototype
	 */
	var PronamicIDealConfigurationEditor = function(element, options) {
		var obj = this;
		var element = $(element);

		// Elements
		var elements = {};
		elements.variantId = element.find("#pronamic_ideal_variant_id");

		/**
		 * Update configuration
		 */
		this.updateConfigurationFields = function() {
			var method = elements.variantId.find("option:selected").attr("data-ideal-method");

			element.find(".extra-settings").hide();
			element.find(".method-" + method).show();
		};
		
		/**
		 * Update fields
		 */
		this.updateFields = function() {
			obj.updateConfigurationFields();
		};

		// Function calls
		obj.updateFields();

		elements.variantId.change(obj.updateConfigurationFields);
	};

	/**
	 * jQuery plugin - Pronamic iDEAL configuration editor
	 */
	$.fn.pronamicIdealConfigurationEditor = function(options) {
		return this.each(function() {
			var element = $(this);

			if(element.data("pronamic-ideal-configuration-editor")) return;

			var editor = new PronamicIDealConfigurationEditor(this, options);

			element.data("pronamic-ideal-configuration-editor", editor);
		});
	};

	/**
	 * Ready
	 */
	$(document).ready(function() {
		if(typeof fieldSettings == "object") {
			fieldSettings.ideal_issuer_drop_down = ".label_setting, .admin_label_setting, .size_setting, .description_setting, .css_class_setting, .error_message_setting, .rules_setting, .conditional_logic_field_setting";
		}

		$(".gforms_edit_form .ideal-edit-link").click(function(event) {
			event.stopPropagation();
		}); 
		
		$("#gf-ideal-feed-editor").gravityFormsIdealFeedEditor();
		$("#pronamic-ideal-configration-editor").pronamicIdealConfigurationEditor();
	});
})(jQuery);