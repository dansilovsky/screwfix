(function($){
	var common = {};
	
	var appGlobal = {
		$body: $('body'),
		templates: {
			alert: _.template($('#commonAlert').html(), null, {variable: 'data'})
		}
	};
	
	var ConnectingAnimationView = common.ConnectingAnimationView = Backbone.View.extend({
		className: 'popupBox',
		
		initialize: function() {
			this.render();
		},
		
		render: function() {
			this.$el.text('Loading ...');
			this.$el.css({top: 0, left: 0})
			.appendTo(appGlobal.$body);
		}		
	});
	
	var LayoverView = common.LayoverView = Backbone.View.extend({
		className: 'layover',
		autoRemove: true,
		
		initialize: function(options) {
			var that = this;
			
			if (!_.isUndefined(options) && !_.isUndefined(options.autoRemove)) {
				this.autoRemove = options.autoRemove;
			}
			
			this.$el.one('click', function() {that.trigger('click');});
			
			this.render();			
		},
		
		render: function() {
			this.$el.appendTo(appGlobal.$body);
		},
		
		events: {
			"click": "clear"
		},
		
		clear: function(e) {
			e.stopPropagation();
			
			if (this.autoRemove) {
				this.remove();
			}
		}
		
	});
	
	var AlertView = common.AlertView = Backbone.View.extend({
		id: 'alert',
		className: 'alert',
		template: appGlobal.templates.alert,
		
		initialize: function(options) {			
			this.data = {
				title: options.title,
				message: options.message
			};
			
			this.render();			
		},
		
		events: {
			"click button": "clear"
		},
		
		render: function() {
			this.layoverView = new LayoverView({autoRemove: false});
			
			this.$el.html(this.template(this.data))
			.appendTo(appGlobal.$body);
			
//			var h = Math.floor(this.$el.outerHeight()/2);
//			var documentH = Math.floor($(document).height()/2);

			var top = Math.floor($(document).height()/2) - Math.floor(this.$el.outerHeight()/2);
			
			this.$el.css({top: top, left: 0});			
		},
		
		clear: function() {
			if (this.layoverView) {
				this.layoverView.remove();
			}
			
			this.remove();			
		}
	});
	
	Screwfix = $.extend({}, Screwfix, {common: common});
	
}).call(this, jQuery);

// Extend backbones model functionality

Backbone.screwfixQueues  = {
	'connectingAnimation': [],
};

Backbone.Model.prototype.connectionErrorAlert = function() {
	new Screwfix.common.AlertView({
		title: 'Connection error', 
		message: "There's a problem connecting to Calendar at the moment. Please make sure that you're connected to the Internet and try again."
	});
}

Backbone.Model.prototype.connectingAnimation = function() {
	if (Backbone.screwfixQueues.connectingAnimation.length === 0) {
		var animation = new Screwfix.common.ConnectingAnimationView();
		
		Backbone.screwfixQueues.connectingAnimation.push(animation);	
	}
	else {
		Backbone.screwfixQueues.connectingAnimation.push(null);
	}
	
}

Backbone.Model.prototype.stopConnectingAnimationView = function() {
	if (Backbone.screwfixQueues.connectingAnimation.length > 1) {
		Backbone.screwfixQueues.connectingAnimation.pop();
	}
	else if(Backbone.screwfixQueues.connectingAnimation.length === 1){
		Backbone.screwfixQueues.connectingAnimation.pop().remove();
	}
}

Backbone.Model.prototype.on('request', Backbone.Model.prototype.connectingAnimation);

Backbone.Model.prototype.on('sync', Backbone.Model.prototype.stopConnectingAnimationView);

Backbone.Model.prototype.on('error', Backbone.Model.prototype.stopConnectingAnimationView);

Backbone.Model.prototype.on('error', Backbone.Model.prototype.connectionErrorAlert);
