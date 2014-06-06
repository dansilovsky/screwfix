(function($) {
	
	var appGlobal  = {
		// stores compiled tamplates
		templates: {
			formInputPattern: _.template($('#formInputPatternTemplate').html(), null, {variable: 'mo'}),
		}
	};
	
	var PatternView = Backbone.View.extend({
		el: $("#frm-credentialsForm-pattern"),
		
		
	});


	
}(jQuery));


