(function($){
	var root = this;

	var common = {};
	
	Screwfix = $.extend({}, Screwfix, common);
	
	common.templates = {
		alert: _.template($('#commonAlert').html(), null, {variable: 'data'})
	};
	
	var AlertView = common.Alert = Backbone.View.extend({
		id: 'alert',
		className: 'popup',
		template: common.templates.alert,
		
		initialize: function(options) {
			this.data = {
				title: options.title,
				message: options.message
			};
			
			this.render();
			
		},
		
		events: {
			"click button": "remove"
		},
		
		render: function() {
			this.$el.html(this.template({data: this.data}));
			
			var h = Math.floor(this.$el.outerHeight()/2);
			var documentH = Math.floor($(document).height()/2);
			
			this.$el.css({top: documentH - h, left: 0});			
		}
	});
	
}).call(this, jQuery);


