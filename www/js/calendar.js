(function($) {	
	
	var appGlobal = {
		// stores compiled tamplates
		templates: {
			dayView: _.template($('#dayTemplate').html(), null, {variable: 'mo'}),
			navigatorView: _.template($('#calendarNavigatorTemplate').html(), null, {variable: 'mo'}),
			toolsView: _.template($('#calendarToolsTemplate').html(), null, {variable: 'mo'}),
			monthView: _.template($('#monthTemplate').html(), null, {variable: 'mo'}),
			calendarView: $('#calendarTemplate')
		}
	}
	
	/**
	 * Manages 
	 * 
	 * @param {object} initial object containing todays date. eg. {year: 2000, month: 1, day: 1}
	 */
	var DateNavigator = function(initial, settings) {
		
		var y = initial.year;
		var m = initial.month || 1;
		var d = initial.day || 1;
		
		var now = new Zidane.Calendar(y, m, d);
		now.setFormat(function(year, month, day) {
			var y = year;
			var m = month < 10 ? '0' + month : month;
			var d = day < 10 ? '0' + day : day;
			
			return y + '-' + m + '-' + d;
		});
		
		var current = now.clone();
		current.startMonth();
		
		var start = current.clone().startWeek();
		var end = current.clone().endMonth().endWeek();
		
		return {
			/**
			 * Get clone of now
			 * 
			 * @returns {Zidane.Calendar}
			 */
			getNow: function() {
				return now.clone();
			},
			
			/**
			 * Get clone of current display month. 
			 * It's an Zidane.Calendar instance set to first day of given month.
			 * 
			 * @returns {Zidane.Calendar}
			 */
			getCurrentMonth: function() {
				return current.clone();
			},
				
			currentRange: function() {
				return {
					start: start.toString(), 
					end: end.toString()
				};
			},
			
			/**
			 * Returns clone of current start 
			 * 
			 * @returns {Zidane.Calendar}
			 */	
			getCurrentStart: function() {
				return start.clone();
			},
			
			/**
			 * Returns clone of current end 
			 * 
			 * @returns {Zidane.Calendar}
			 */	
			getCurrentEnd: function() {
				return end.clone();
			},
				
			prevMonth: function() {
				current.prevMonth().startMonth();
				
				start = current.clone().startWeek();
				end = current.clone().endMonth().endWeek();
				
				return this;
			},
			
			nextMonth: function() {
				current.nextMonth().startMonth();
				
				start = current.clone().startWeek();
				end = current.clone().endMonth().endWeek();
				
				return this;
			},
				
			prevWeek: function() {
				start.prevWeek();
				end.prevWeek();
				current.prevWeek();
				
				return this;
			},
				
			nextWeek: function() {
				start.nextWeek();
				end.nextWeek();
				current.nextWeek();
				
				return this;
			}
		};
	};
	
	/**
	 * Manages selecting of cells
	 * @trigger started()             selection started
	 * @trigger added(selection)      cell added to selection
	 * @trigger selected(selection)   selection ended
	 * @param {jQuery} $selector  
	 * @param {array}  cells      array of all cells(jQuery objects) in a grid
	 */
	var Selector = function($selector, cells, options) {
//		var that = this;
		var defaults = {
			showInfo: false,
			// {callback: function, context: anObject} like backbones events
			info: {callback: null, context: null}
		}
		
		var S = {
			that: this,
			isSelecting: false,
			startCell: null,
			endCell: null,
			$selector: $selector,
			$document: $(document),
			cells: cells,
			selection: [],
			$selectionInfo: null,
			settings: $.extend({}, defaults, options),
		}
		
		S.$document.mouseup(function(event) {
			// only for left mouse button
			if (event.which === 1) {
				S.isSelecting = false;
				
				// selection ended deactivate info box
				if (S.settings.showInfo) {
					S.deactivateInfo();
				}

				S.that.trigger('selected', S.selection);
			}
		});
		
		for (var i=0; i<S.cells.length; i++) {
			
			S.cells[i].on('leftmousedown', function(cell, event) {
				S.that.trigger('started')
				
				S.isSelecting = true;
				
				S.startCell = cell.order;
				S.endCell = cell.order
				
				S.select();
				
				// show selection info
				if (S.settings.showInfo) {					
					S.activateInfo(event);
				}				
			});
			
			S.cells[i].on('mouseover', function(cell) {
				if (S.isSelecting) {
					S.endCell = cell.order;
					S.select();
				}
			});
		}
		
		S.select = function() {			
			var check = S.startCell - S.endCell;
			
			S.selection = [];
			
			// unselect all cells
			for (var i=0; i<S.cells.length; i++) {
				S.cells[i].unselect();
			}
			
			if (check === 0) {
				S.cells[S.startCell].select();
				S.selection.push(S.cells[S.startCell]);
			}
			else if (check < 0) {
				for (var i=S.startCell; i<=S.endCell; i++) {
					S.cells[i].select();
					S.selection.push(S.cells[i]);
				}
			}
			else {
				for (var i=S.endCell; i<=S.startCell; i++) {
					S.cells[i].select();
					S.selection.push(S.cells[i]);
				}
			}
			
			// trigger new cell added. !must be triggered afeter all selection is done
			S.that.trigger('added', S.selection);
		};
		
		S.activateInfo = function(event) {			
			S.$selectionInfo = Zidane.create('div')
				.attr('id','calendarSelectionInfo')
				.addClass('popupBox')
//				.text('Info box')
				.appendTo(S.$selector)
				.css('display', 'block');
			
			// append result of info callback function
			if (S.settings.info.callback) {
				S.that.appendInfo(S.selection);
				S.that.on('added', S.that.appendInfo, S.that.settings);				
			}
			
			var boxWidth = S.$selectionInfo.outerWidth();
			var boxHeight = S.$selectionInfo.outerHeight();
			
			var infoArgs = {
				document: {
					height: S.$document.height(),
					width: S.$document.width(),
				},
				box: {
					height: S.$selectionInfo.outerHeight(),
					width: S.$selectionInfo.outerWidth()
				}
			}
			
			S.positionInfoBox(infoArgs, event.pageX, event.pageY);

			S.$selector.mousemove(function(event) {
				var x = event.pageX + 'px';
				var y = event.pageY + 'px';
				
				S.positionInfoBox(infoArgs, event.pageX, event.pageY);
			});
		}
		
		S.deactivateInfo = function() {
			S.$selectionInfo.remove();
			S.$selector.unbind('mousemove');
			S.that.off('added', S.that.appendInfo, S.that.settings);
		}
		
		/**
		 * Helper function to position selection Info box
		 * @param {object} args
		 * @param {int} x
		 * @param {int} y
		 */
		S.positionInfoBox = function(args, pageX, pageY) {
			var offset = 5;
			var newX = pageX;
			var newY = pageY - args.box.height - offset;
			
			if (newX > args.document.width - args.box.width - offset) {
				newX = args.document.width - args.box.width - offset;
			}
			
			if (newX < offset) {
				newX = offset;
			}
			
			if (newY < offset) {
				newY = offset;
			}
			
			S.$selectionInfo.css({top: newY, left: newX});
		}
		
		/**
		 * Sets settings.showInfo to given argument.
		 * 
		 * @param {bool} show
		 * @returns {this}
		 */
		this.setShowInfo = function(show) {
			S.settings.showInfo = show;
		}
		
		this.appendInfo = function() {
			S.$selectionInfo.empty();
			
			var callback = S.settings.info.callback;
			var context = S.settings.info.context;
			if (context) {
				// use given context
				S.$selectionInfo.append(callback.call(context, S.selection));
			}
			else {
				// use current context(Selector)
				S.$selectionInfo.append(callback(S.selection));
			}
		}
	}	
	
	_.extend(Selector.prototype, Backbone.Events);	
	
	// Navigator model
	var NavigatorModel = Backbone.Model.extend({});
	
	// Day model
	var DayModel = Backbone.Model.extend({
		/**
		 * 
		 * @param {string} today  date in format yyyy-mm-dd
		 * @returns {bool}
		 */
		isToday: function(today) {
			return todday === this.id ? true : false;				
		}
	});

	// Calendar day collection
	var CalendarDayCollection = Backbone.Collection.extend({
		model: DayModel,
			
		initialize: function(options) {
			this.comparator = 'id';
		},
		
		filterByDateRange: function(range) {
			this.check(range);
				
			return this.filter(function(model) {
				var start = range.start;
				var end = range.end;
				
				return (model.id >= start && model.id <= end) ? true : false;					
			});
		},
		
		/**
		 * Checks if models between given dates are available. 
		 * If not then builds and adds new simple days to collection 
		 * and then tries to load complete days data from server.
		 * 
		 * @param {object} range 
		 */	
		check: function(range) {
			var missingDays = [];
			var dateRunner = new Zidane.Calendar();
			dateRunner.setFromStr(range.start);
			var loop = 0;
			while (dateRunner.toString() <= range.end) {
				if (_.isUndefined(this.get(dateRunner.toString()))) {
					missingDays.push(dateRunner.toString());
				}
				
				loop++;
				dateRunner.nextDay();
				
				if (loop > 100) {
					throw 'Only 100 loops in while statement allowed';
				}
			}
			
			// failed check, missing days in this collection were found
			if (missingDays.length > 0) {
				var loadRange = {};
				// first item of array missingDays is actually first day of load range
				loadRange.start = missingDays[0];
				// last item of array missingDays is actually last day of load range
				loadRange.end = missingDays[missingDays.length-1];
				
				// build missing days
				var days = this.buildDays(loadRange);
				
				this.add(days);
				
				this.load(loadRange);
			}
			
			
			
		},
		
		/**
		 * Helper function builds array of days for given date range.
		 * @param {object}  range  contains start and end date string
		 * @returns {array}
		 */
		buildDays: function(range) {
			var dateRunner = new Zidane.Calendar();
			dateRunner.setFromStr(range.start);			
			var days = [];
			var loop;
			while (dateRunner.toString() <= range.end) {
				days.push({
					"id": dateRunner.toString(),
					"day": dateRunner.getDate(),
					"note":null,
					"sysNote":null,
					"holiday":null,
					"bankHoliday":null,
					"shiftStart":null,
					"shiftEnd":null,
					"year": dateRunner.getYear(),
					"isFirstDayOfWeek": dateRunner.isFirstDayOfWeek(),
					"isLastDayOfWeek": dateRunner.isLastDayOfWeek(),
				});
				
				loop++;
				dateRunner.nextDay();
				
				if (loop > 100) {
					throw 'Only 100 loops in while statement allowed';
				}
			}
			
			return days;
		},
		
		getNextMonth: function() {
		
		},
		
		/**
		 * Loads new days into collection given by argument loadRange.
		 * Informs buffer controller about result of operation.
		 * 
		 * @param   {object} loadRange    eg. {start: '2000-01-01', end: '2000-02-28'}
		 * @returns {bool} if loaded is succesfully true otherwise false
		 */	
		load: function(loadRange) {
			var that = this;
			
			var xhr = $.ajax({
				url: "api/days?from=" + loadRange.start + '&to=' + loadRange.end,
				type: 'GET',
				context: this
			})
			.done(function(data) {
				this.add(data, {merge: true});
			})
			.fail(function() {
				
			})
//			.always(function() {
//				alert("complete");
//			});
		}
	});
	
	var AppView = Backbone.View.extend({
		el: $('#body'),
		
		initialize: function() {
			this.calendar = new CalendarView();
		}
	})
	
	// Calendar view
	var CalendarView = Backbone.View.extend({
		el: $('#calendar'),
		$calendarBar: null,
		mode: null,

		initialize: function() {
			// collection of day models
			this.calendarDayCollection = new CalendarDayCollection(screwfix.calendarDaysData, {comparator: false});
			
			// date navigator
			this.dateNavigator = new DateNavigator(screwfix.today);
			
			// model navigator
			this.navigatorModel = new NavigatorModel(screwfix.today);			
			
			// view navigator
			this.navigatorView = new NavigatorView({model: this.navigatorModel, master: this});
			
			this.toolsView = new ToolsView({master: this});
			
			// view month
			this.monthView = new MonthView({collection: this.calendarDayCollection, master: this});
			
			this.on('change:month:prev', this.dateNavigator.prevMonth, this.dateNavigator);
			this.on('change:month:next', this.dateNavigator.nextMonth, this.dateNavigator);
			this.on('change:month', this.monthView.changeMonth, this.monthView);
			this.on('change:month', this.navigatorView.changeMonthDate, this.navigatorView);
			
			this.on('change:week:prev', this.dateNavigator.prevWeek, this.dateNavigator);
			this.on('change:week:next', this.dateNavigator.nextWeek, this.dateNavigator);
			this.on('change:week', this.navigatorView.changeWeekDate, this.navigatorView);
			this.on('change:week', this.monthView.changeMonth, this.monthView);
			
			this.renderCalendar();
		},
			
		renderCalendar: function() {
			this.$el.append(appGlobal.templates.calendarView.html());
			
			this.$calendarBar = this.$el.find('#calendarBar');
			
			this.renderNavigator();
			this.renderTools();
			this.renderMonth();
			
			return this;
		},
			
		renderNavigator: function() {			
			this.$calendarBar.append(this.navigatorView.el);
			
			return this;
		},
		
		renderTools: function() {
			this.$calendarBar.append(this.toolsView.el);
		},

		renderMonth: function() {
			this.$el.append(this.monthView.el);
			this.monthView.resize();
			
			return this;
		},
			
		prevMonth: function() {
			this.trigger('change:month:prev', {dateNavigator: this.dateNavigator});
			this.trigger('change:month', {dateNavigator: this.dateNavigator});
			
			return this;
		},
			
		nextMonth: function() {
			this.trigger('change:month:next', {dateNavigator: this.dateNavigator});
			this.trigger('change:month', {dateNavigator: this.dateNavigator});
			
			return this;
		},
			
		prevWeek: function() {
			this.trigger('change:week:prev', {dateNavigator: this.dateNavigator});
			this.trigger('change:week', {dateNavigator: this.dateNavigator});
			
			return this;
		},
			
		nextWeek: function() {			
			this.trigger('change:week:next', {dateNavigator: this.dateNavigator});
			this.trigger('change:week', {dateNavigator: this.dateNavigator});
			
			return this;
		},		
		
		holidayInfo: function(selection) {
			var count = selection.length;
			
			return '<div>'+ count + '/30</div>';
				}
			
			
	});

	// Navigator view
	var NavigatorView = Backbone.View.extend({
		tagName: 'div',
		id: 'calendarNavigator',
		template: appGlobal.templates.navigatorView,
		
		initialize: function(options) {
			// master view is CalendarView
			this.master = options.master;
			
			this.render();
			
			this.$date = this.$el.find('#dateLabel');
		},
			
		render: function() {
			this.$el.html(this.template(this.model.attributes));

			return this;
		},
		
		events: {
			"click #prevMonth": "prevMonth",
			"click #nextMonth": "nextMonth",
		},
		
		prevMonth: function() {
			this.master.prevMonth();
			
			return this;
		},
			
		nextMonth: function() {
			this.master.nextMonth();
			
			return this;
		},
		
		changeMonthDate: function(options) {
			var currMonth = options.dateNavigator.getCurrentMonth();
			var month = Zidane.capitalize(currMonth.getMonthString());
			var year = currMonth.getYear();
			
			this.$date.text(month + ' ' + year);
			
			return this;
		},
		
		changeWeekDate: function(options) {
			var start = options.dateNavigator.getCurrentStart();
			var startDay = start.getDate();
			var startMonth = Zidane.capitalize(start.getMonthString());
			var startYear = start.getYear();
			
			var end = options.dateNavigator.getCurrentEnd();
			var endDay = end.getDate();
			var endMonth = Zidane.capitalize(end.getMonthString());
			var endYear = end.getYear();
			
			this.$date.text(startDay + ' ' + startMonth + ' ' + startYear + ' - ' + endDay + ' ' + endMonth + ' ' + endYear);
			
			return this;
		}
	});
	
	var ToolsView = Backbone.View.extend({
		tagName: 'ul',
		id: 'calendarTools',
		template: appGlobal.templates.toolsView,
		
		initialize: function(options) {
			// master is CalendarView
			this.master = options.master;
			
			this.render();
		},
		
		render: function() {
			var that = this;
			
			this.$el.html(this.template);
			
			this.$el.find('li a').click(function() {
				that.select(this);
				
			});
		},
		
		select: function(el) {
			var $el = $(el);
			
			if ($el.hasClass('selected')) {
				$el.removeClass('selected');
				this.master.mode = null;
				
			}
			else {
				$el.addClass('selected');
				this.master.mode = 'holidays';
			}
			
			
		}
	});
	
	var MonthView = Backbone.View.extend({
		tagName: 'div',
		id: 'calendarTableContainer',
		template: appGlobal.templates.monthView,
		
		initialize: function(options) {
			// master view is CalendarView
			this.master = options.master;
			
			this.$el.mousewheel(function(event, delta){
				if (delta > 0) {
					options.master.prevWeek();
				}
				else {
					options.master.nextWeek();
				}
			});
			
			this.selectionMode = false;
			
			this.dateNavigator = options.master.dateNavigator;
			
			this.dayViews = [];
			
			this.render(this.dateNavigator);
		},
		
		events: {
			"mousedown table#calendarMainTable": "select"
		},
		
		render: function(dateNavigator) {
			this.clear();
			
			this.$el.html(this.template({}));
			
			this.$tableHeader = this.$el.find('table#calendarHeaderTable')
			this.$tableMain = this.$el.find('table#calendarMainTable');

			var that = this;
			var now = dateNavigator.getNow().toString();
			// current display monht date string yyyy-mm
			var current = dateNavigator.getCurrentMonth().toString().substr(0, 7);
			// order number of cell in display month
			var order = 0;
			var $tr;		
			
			var models = this.collection.filterByDateRange(dateNavigator.currentRange());

			_.each(models, function(item) {
				var dayView = new DayView({
					model: item,
					now: now,
					currDisplayMonth: current,
					parent: that,
					order: order++
				});

				that.dayViews.push(dayView);

				if (dayView.isFirstDayOfWeek()) {
					$tr = Zidane.create('tr');
					that.$tableMain.append($tr);
				}

				$tr.append(dayView.render().el);
			});
			
			var selector = new Selector(this.$el, this.dayViews, {info: {callback: this.master.holidaysInfo, context: this.master}});
			selector.on('started', function() {
				if (that.master.mode === 'holidays') {
					this.setShowInfo(true);
				}
				else {
					this.setShowInfo(false);
				}
			});
			selector.on('selected', function(selection){
				// pridej akci
				var test = selection;
			});
			
			return this;
		},

		resize: function() {
			var documentHeight = $(document).height();
			var substract = $('#mainBar').height() + $('#calendarBar').height() + this.$tableHeader.height() + $('#footer').height();

			this.$tableMain.height(documentHeight-substract);			
			
			if (this.dayViews[0]) {
				// get height of first day view and use it to resize all dayViews div cell elements
				var dayViewHeight = this.dayViews[0].$el.height();

				_.each(this.dayViews, function(dayView){
					dayView.resize(dayViewHeight);
				});
			}
		},
		
		clear: function() {
			this.dayViews = [];
			this.$el.children().remove();
		},
			
		changeMonth: function(options) {
			this.render(options.dateNavigator);
			this.resize();
		}
		
		
	});
	
	var DayView = Backbone.View.extend({
		tagName: 'td',
		template: appGlobal.templates.dayView,
		selection: {
			isSelected: true
		},

		initialize: function(options) {
			var that = this;
			
			this.$cell = null;
			
			this.now = options.now;
			this.currDisplayMonth = options.currDisplayMonth;
			// parent is MonthView
			this.parent = options.parent;
			// order number in display month 
			this.order = options.order;
			
			this.model.on('change', this.render, this);
			
			// height of cell
			this.height = null;
			
			this.$el.mousedown(function(event){
				if (event.which === 1) {
					that.trigger('leftmousedown', that, event);
				}
			});
			
			this.$el.mouseover(function() {
				that.trigger('mouseover', that);
			});
		},

		render: function() {
			this.$el.html(this.template({data: this.model.attributes, view: this}));
			
			if (this.height !== null){
				this.resize(this.height);
			}
			
			this.$cell = this.$el.find('div.selected')
			
			return this;
		},

		resize: function(height) {
			this.$el.children().height(height);
			this.height = height;
		},

		isFirstDayOfWeek: function() {
			return this.model.get('isFirstDayOfWeek');
		},

		isLastDayOfWeek: function() {
			return this.model.get('isLastDayOfWeek');
		},
			
		isToday: function() {
			return this.model.id === this.now ? true : false;
		},
		
		isEdge: function() {
			var viewMonth = this.model.id.substr(0, 7);
			
			return viewMonth !== this.currDisplayMonth;
		},
		
		height: function() {
			return this.$el.height();
		},
		
		select: function() {
			//this.$cell.css('background-color', 'black');
			this.$cell.css('display', 'block');
		},
		
		unselect: function() {
			this.$cell.css('display', 'none');
		}
	});

	//create instance of master view
	var app = new AppView();

}(jQuery));


