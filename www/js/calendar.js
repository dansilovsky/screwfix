(function($) {
	
	var appGlobal = {
		// stores compiled tamplates
		templates: {
			dayView: _.template($('#dayTemplate').html(), null, {variable: 'mo'}),
			navigatorView: _.template($('#calendarNavigatorTemplate').html(), null, {variable: 'mo'}),
			toolsView: _.template($('#calendarToolsTemplate').html(), null, {variable: 'mo'}),
			monthView: _.template($('#monthTemplate').html(), null, {variable: 'mo'}),
			calendarView: $('#calendarTemplate'),
			holidaysInfo: _.template($('#holidaysInfoTemplate').html(), null, {variable: 'data'}),
			addNote: _.template($('#addNoteTemplate').html(), null, {variable: 'data'}),
			editNote: _.template($('#editNoteTemplate').html(), null, {variable: 'data'})
		}
	};
	
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
		};
		
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
			enabled: false
		};
		
		S.$document.mouseup(function(event) {
			// only for left mouse button
			if (S.isSelecting && event.which === 1) {				
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
				if (S.enabled) {
					// start selection procedures only if selection is enabled
					S.that.trigger('started');

					S.isSelecting = true;

					S.startCell = cell.order;
					S.endCell = cell.order;

					S.select();

					// show selection info
					if (S.settings.showInfo) {					
						S.activateInfo(event);
					}
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
			S.that.unselect();
			
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
			
			var infoArgs = {
				document: {
					height: S.$document.height(),
					width: S.$document.width()
				},
				box: {
					height: S.$selectionInfo.outerHeight(),
					width: S.$selectionInfo.outerWidth()
				}
			};
			
			S.positionInfoBox(infoArgs, event.pageX, event.pageY);

			S.$selector.mousemove(function(event) {
				var x = event.pageX + 'px';
				var y = event.pageY + 'px';
				
				S.positionInfoBox(infoArgs, event.pageX, event.pageY);
			});
		};
		
		S.deactivateInfo = function() {
			S.$selectionInfo.remove();
			S.$selector.unbind('mousemove');
			S.that.off('added', S.that.appendInfo, S.that.settings);
		};
		
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
		};
		
		/**
		 * Sets settings.showInfo to given argument.
		 * 
		 * @param {bool} show
		 * @returns {this}
		 */
		this.setShowInfo = function(show) {
			S.settings.showInfo = show;
		};
		
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
		};
		
		/**
		 * Unselects all cells
		 * 
		 * @return {Selector} this
		 */
		this.unselect = function() {
			for (var i=0; i<S.cells.length; i++) {
				S.cells[i].unselect();
			}
			
			return this;
		};
		
		this.enable =  function() {
			S.enabled = true;
			return this;
		};
		
		this.disable = function() {
			S.enabled = false;
			return this;
		};
		
		this.clear = function() {
			for (var i=0; i<S.cells.length; i++) {
				S.cells[i].off();
			}
			this.off();
		};
	};	
	
	_.extend(Selector.prototype, Backbone.Events);	
	
	// Navigator model
	var NavigatorModel = Backbone.Model.extend({});
	
	// Day model
	var DayModel = Backbone.Model.extend({		
		urlRoot: window.document.URL + 'api/days/',
		
		/**
		 * 
		 * @param {string} today  date in format yyyy-mm-dd
		 * @returns {bool}
		 */
		isToday: function(today) {
			return today === this.id ? true : false;				
		}
	});

	// Calendar day collection
	var CalendarDayCollection = Backbone.Collection.extend({
		model: DayModel,
		loadRange: {start: null, end: null},
			
		initialize: function(options) {
			this.comparator = 'id';
		},
		
		url: function() {
			return window.document.URL + 'api/days';
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
				this.loadRange.start = missingDays[0];
				// last item of array missingDays is actually last day of load range
				this.loadRange.end = missingDays[missingDays.length-1];
				
				// build missing days
				var days = this.buildDays(this.loadRange);
				
				this.add(days);
				
				this.fetch({
					remove: false,
					data: {from: this.loadRange.start, to: this.loadRange.end}
				});
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
					"note": null,
					"sysNote": null,
					"holiday": null,
					"bankHoliday": null,
					"shiftStart": null,
					"shiftEnd": null,
					"year": dateRunner.getYear(),
					"isFirstDayOfWeek": dateRunner.isFirstDayOfWeek(),
					"isLastDayOfWeek": dateRunner.isLastDayOfWeek()
				});
				
				loop++;
				dateRunner.nextDay();
				
				if (loop > 100) {
					throw 'Only 100 loops in while statement allowed';
				}
			}
			
			return days;
		}
	});
	
	var AppView = Backbone.View.extend({
		el: $('body'),
		mode:'default',
		urlRoot: window.document.URL,
		
		initialize: function() {			
			this.user = new Zidane.User(Screwfix.user, new Zidane.Acl(Screwfix.acl.roles));
			
			this.calendar = new CalendarView({master: this});
			
			this.calendarPlacer = new Zidane.Placer();
		},
		
		layover: function() {
			var $layover = Zidane.create('div', 'layover')
			// you need to stop native "mouseup" event used in Selector to fire Selector's "selected" event
			.mouseup(function(event) {
				event.stopPropagation();
			})			
			.appendTo(this.el);
			
			return $layover;
		}
	});
	
	AppView.MODE_DEFAULT = 'default';
	AppView.MODE_HOLIDAYS = 'holidays';
	
	// Calendar view
	var CalendarView = Backbone.View.extend({
		el: $('#calendar'),
		$calendarBar: null,

		initialize: function(options) {
			// master is AppView
			this.master = options.master;
			
			this.holidays = {total: Screwfix.holidays.total, used: Screwfix.holidays.used};
			
			this.user = this.master.user;
			
			// collection of day models
			this.calendarDayCollection = new CalendarDayCollection(Screwfix.calendarDaysData, {comparator: false});
			
			// date navigator
			this.dateNavigator = new DateNavigator(Screwfix.today);
			
			// model navigator
			this.navigatorModel = new NavigatorModel(Screwfix.today);			
			
			// view navigator
			this.navigatorView = new NavigatorView({model: this.navigatorModel, master: this.master, parent: this});
			
			// view tools
			this.toolsView = new ToolsView({master: this.master, parent: this});
			
			// view month
			this.monthView = new MonthView({collection: this.calendarDayCollection, master: this.master, parent: this});
			
			this.on('change:month:prev', this.dateNavigator.prevMonth, this.dateNavigator);
			this.on('change:month:next', this.dateNavigator.nextMonth, this.dateNavigator);
			this.on('change:month', this.monthView.changeMonth, this.monthView);
			this.on('change:month', this.navigatorView.changeMonthDate, this.navigatorView);
			
			this.on('change:week:prev', this.dateNavigator.prevWeek, this.dateNavigator);
			this.on('change:week:next', this.dateNavigator.nextWeek, this.dateNavigator);
			this.on('change:week', this.navigatorView.changeWeekDate, this.navigatorView);
			this.on('change:week', this.monthView.changeMonth, this.monthView);
			
			this.toolsView.on(
				'holidayson', 
				function() {
					this.master.mode = AppView.MODE_HOLIDAYS; 
					this.monthView.selector.enable();
				}, 
				this
			);
			this.toolsView.on(
				'holidaysoff', 
				function() {
					this.master.mode = AppView.MODE_DEFAULT; 
					this.monthView.selector.disable();
				}, 
				this
			);
			
			this.render();
		},
			
		render: function() {
			this.$el.append(appGlobal.templates.calendarView.html());
			
			this.$calendarBar = this.$el.find('#calendarBar');
			
			this.renderNavigator();
			
			if (this.user.isAllowed(Zidane.Acl.MEMBER)) {
				// render only if user role is member or higher
				this.renderTools();
			}
			
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
		
		holidaysInfo: function(selection) {
			var total = this.holidays.total;
			var used = this.holidays.used;
			var selected = 0;
			var template = appGlobal.templates.holidaysInfo;
			
			for (var i=0; i<selection.length; i++) {
				if (!selection[i].isDayOff()) {
					selected++;
				}
			}
			
			var left = total - used - selected;
			
			var hi = {selected: selected, left: left};
			
			return template(hi);
		}
		
		
	});

	// Navigator view
	var NavigatorView = Backbone.View.extend({
		tagName: 'div',
		id: 'calendarNavigator',
		template: appGlobal.templates.navigatorView,
		
		initialize: function(options) {
			// parent view is CalendarView
			this.parent = options.parent;
			
			this.render();
			
			this.$date = this.$el.find('#dateLabel');
		},
			
		render: function() {
			this.$el.html(this.template(this.model.attributes));

			return this;
		},
		
		events: {
			"click #prevMonth": "prevMonth",
			"click #nextMonth": "nextMonth"
		},
		
		prevMonth: function(e) {
			e.preventDefault();
			
			this.parent.prevMonth();
			
			return this;
		},
			
		nextMonth: function(e) {
			e.preventDefault();
			
			this.parent.nextMonth();
			
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
			// parent is CalendarView
			this.parent = options.parent;
			
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
				this.trigger('holidaysoff');
				
			}
			else {
				$el.addClass('selected');
				this.trigger('holidayson');
			}		
			
		}
	});
	
	var MonthView = Backbone.View.extend({
		tagName: 'div',
		id: 'calendarTableContainer',
		template: appGlobal.templates.monthView,
		
		initialize: function(options) {
			// parent view is CalendarView
			this.parent = options.parent;
			// master is AppView
			this.master = options.master;
			
			this.$el.mousewheel(function(event, delta){
				if (delta > 0) {
					options.parent.prevWeek();
				}
				else {
					options.parent.nextWeek();
				}
			});
			
			this.selectionMode = false;
			
			this.dateNavigator = this.parent.dateNavigator;
			
			this.dayViews = [];
			
			this.selector = null;
			
			this.render(this.dateNavigator);
		},
		
		events: {
			"mousedown table#calendarMainTable": "select"
		},
		
		render: function(dateNavigator) {
			this.clear();
			
			this.$el.html(this.template({}));
			
			this.$tableHeader = this.$el.find('table#calendarHeaderTable');
			this.$tableMain = this.$el.find('table#calendarMainTable');

			var that = this;
			var now = dateNavigator.getNow().toString();
			// current display monht date string yyyy-mm
			var current = dateNavigator.getCurrentMonth().toString().substr(0, 7);
			// order number of cell in display month
			var order = 0;
			var $tr;			
			// use fragment to avoid unnecessary browser DOM reflows viz. http://ozkatz.github.io/avoiding-common-backbonejs-pitfalls.html
			var fragment = document.createDocumentFragment();
			var models = this.collection.filterByDateRange(dateNavigator.currentRange());

			_.each(models, function(item) {
				var dayView = new DayView({
					model: item,
					now: now,
					currDisplayMonth: current,
					master: that.parent.master,
					parent: that,
					order: order++
				});

				that.dayViews.push(dayView);

				if (dayView.isFirstDayOfWeek()) {
					$tr = Zidane.create('tr');
					fragment.appendChild($tr[0]);
				}

				$tr.append(dayView.render().el);
			});
			
			this.$tableMain.append(fragment);
			
			this.selector = new Selector(this.$el, this.dayViews, {info: {callback: this.parent.holidaysInfo, context: this.parent}});
			
			if (this.master.mode === AppView.MODE_HOLIDAYS) {
				this.selector.enable();
			}

			this.selector.on('started', function() {
				if (that.master.mode === AppView.MODE_HOLIDAYS) {
					this.setShowInfo(true);
				}
				else {
					this.setShowInfo(false);
				}
			});
			
			this.selector.on('selected', function(selection) {
//				if (that.parent.mode === 'default') {
//					// no mode selected
//					if (selection.length == 1) {
//						selection[0].addNote(this);
//					}
//					else {
//						// add some code
//					}
//				}
//				
//				if (that.parent.mode === 'holidays') {
//					// holidays mode selected
//					// add some code
//				}
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
			for (var i=0; i<this.dayViews.length; i++) {
				this.dayViews[i].remove();
			}
			
			this.dayViews = [];
			
			if (this.selector !== null) {
				// remove old events
				this.selector.off();
				this.selector = null;
			}
		},
			
		changeMonth: function(options) {
			this.render(options.dateNavigator);
			this.resize();
		}
		
		
	});
	
	var DayView = Backbone.View.extend({
		tagName: 'td',
		template: appGlobal.templates.dayView,
		templateAddNote: appGlobal.templates.addNote,
		selection: {
			isSelected: true
		},

		initialize: function(options) {
			var that = this;
			
			this.$cell = null;
			
			this.now = options.now;
			this.currDisplayMonth = options.currDisplayMonth;			
			// master is AppView
			this.master = options.master;
			// parent is MonthView
			this.parent = options.parent;
			this.user = options.master.user;
			// order number in display month 
			this.order = options.order;
			
			this.listenTo(this.model, 'change', this.renderOnChange);
			
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
		
		events: {
			"click": "addNote",
			"click .note": "editNote",
			"click .sysNote": "editNote",
			"render": "afterRender"
		},

		render: function() {
			this.$el.html(this.template({data: this.model.attributes, view: this}));
			
			if (this.height !== null){
				this.resize(this.height);
			}
			
			this.$cell = this.$el.find('div.selected');
			
			this.$el.trigger('render');
			
			return this;
		},
		
		renderOnChange: function(model, options) {			
			if (this.model.id === model.id) {
				this.render();
			}
		},
		
		/**
		 * Called after view is rendered
		 */
		afterRender: function() {
			var notes = this.model.get('note');
			var sysNotes = this.model.get('sysNote');
			
			if (notes !== null) {
				this.$el.find('.note').each(function(i){
					$(this).data('note', {i: i, type: 'personal', val: notes[i]});
				});
			}
			
			if (sysNotes !== null) {
				this.$el.find('.sysNote').each(function(i){
					$(this).data('note', {i: i, type: 'system', val: sysNotes[i]});
				});
			}
		},
		
		/**
		 * Returns string representation of this DayView. (eg. Wednesday, 26 April)
		 * 
		 * @returns {string}
		 */
		toString: function() {
			var format = function() {
				var test = this;
				test;
				return Zidane.capitalize(this.getWeekDayString())+', '+this.getDate()+' '+Zidane.capitalize(this.getMonthString());
			};
			
			var calendar = new Zidane.Calendar(null, null, null, format);
			calendar.setFromStr(this.model.id);
			
			return calendar.toString();
		},
		
		resize: function(height) {
			this.$el.children().height(height);
			this.height = height;
		},
		
		/**
		 * Event handler. 
		 * Displays form for adding note and saves it.
		 * @param {jQuery event} e
		 */
		addNote: function(e) {
			var note = {
				when: this.toString(),
				i: null,
				val: null,
				type: 'personal',
				action: 'add'
			};

			this.noteForm(note);			
		},
		
		/**
		 * Event handler
		 * @param {jQuery event} e
		 */
		editNote: function(e) {
			e.stopPropagation();
			
			var defaults = {
				when: this.toString(),
				i: null,
				val: null,
				type: null,
				action: 'edit'
			};				

			var note = $(e.target).data('note');

			note = $.extend({}, defaults, note);				

			this.noteForm(note);
						
		},
		
		noteForm: function(note) {
			if (this.master.mode === AppView.MODE_DEFAULT && this.user.isAllowed(Zidane.Acl.MEMBER)) {
				if (note.type === 'system' && !this.user.isAllowed(Zidane.Acl.EDITOR)) {
					return;
				}
				new NoteFormView({
					note: note, 
					master: this.master, 
					parent: this, 
					user: this.user
				});
			}
		},
		
		/**
		 * Save note to model
		 * @param {object} note
		 */
		saveNote: function(note) {
			var oldNote = note.type === 'personal' ? this.model.get('note') : this.model.get('sysNote');
			var newNote = [];
			
			if (oldNote !== null) {
				newNote = oldNote.slice();
				
				if (note.action === 'add') {
					newNote.push(note.val);
				}
				else {
					newNote[note.i] = note.val;
				}
			}
			else {
				newNote.push(note.val);
			}
			
			if (note.type === 'personal')
			{
				if (this.user.isAllowed(Zidane.Acl.MEMBER)) {
					this.model.save({note: newNote}, {patch: true, wait: true});
				}
			}
			else {
				if (this.user.isAllowed(Zidane.Acl.EDITOR)) {
					this.model.save({sysNote: newNote}, {patch: true, wait: true});
				}
			}
		},
		
		deleteNote: function(note) {
			var oldNote = note.type === 'personal' ? this.model.get('note') : this.model.get('sysNote');
			
			var newNote = oldNote.slice();
			newNote.splice(note.i, 1);
			
			if (newNote.length === 0) {
				newNote = null;
			}
			
			if (note.type === 'personal')
			{
				if (this.user.isAllowed(Zidane.Acl.MEMBER)) {
					this.model.save({note: newNote}, {patch: true, wait: true});
				}
			}
			else {
				if (this.user.isAllowed(Zidane.Acl.EDITOR)) {
					this.model.save({sysNote: newNote}, {patch: true, wait: true});
				}
			}
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
		
		isDayOff: function() {			
			if (this.model.get('shiftStart') !== null) {
				return false;
			}
			
			return true;
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
		},
		
		placePopup: function($popup) {
			var popupH = $box.height();
			var popupW = $box.width();
			
			var cellH = this.$el.outerHeight();
			var cellW = this.$el.outerWidth();
			
			
			
		}
	});
	
	var NoteFormView = Backbone.View.extend({
		tagName: 'td',
		className: 'popupBox form',		
		templateAdd: appGlobal.templates.addNote,
		templateEdit: appGlobal.templates.editNote,
		
		initialize: function(options) {
			this.master = options.master;
			this.parent = options.parent;
			this.user = options.user;
			this.note = options.note;
			
			this.layover = null;
			
			this.render();
		},
		
		events: {
			"click button[name|='save']": "save",
			"click button[name|='delete']": "delete",
			"click #personal": "switchToPersonal",
			"click #system": "switchToSystem"
		},
		
		render: function() {			
			var that = this;
			
			if (this.note.action === 'add') {
				this.renderAdd();
			}
			else {
				this.renderEdit();
			}
			
			this.layover = new Screwfix.common.LayoverView();
			this.layover.on(
				'click', 
				function() {
					this.parent.unselect();
					this.clear();
				},
				this			
			);
			
			this.$el.css('display', 'block')
			.appendTo(this.master.el)
			.find('textarea').setCursorPosition();
			
			this.master.calendarPlacer.place(this.$el, this.parent.$el);			
		},
		
		renderAdd: function() {
			$.extend( 
				this.note, 
				{showSwitcher: this.user.isAllowed(Zidane.Acl.EDITOR)}
			);
			
			this.$el.html(this.templateAdd(this.note));
						
		},
		
		renderEdit: function() {
			if (this.note.type === 'system' && !this.user.isAllowed(Zidane.Acl.EDITOR)) {
				this.remove();
				return;
			}
			
			this.$el.html(this.templateEdit(this.note));
		},
		
		switchToPersonal: function(e) {
			this.switch('personal', e);
		},
		
		switchToSystem: function(e) {			
			this.switch('system', e);
		},
		
		switch: function(type, e) {
			this.note.type = type;
			
			$(e.target)
			.closest('div.switcher')
			.find('a')
			.each(function(){
				var $el = $(this);
			
				$el.removeClass();
				
				if ($el.attr('id') === type) {
					$el.addClass('selected');
				}
			});
		},
		
		save: function() {
			var val = this.$el.find('textarea').val().trim();
			
			if (val === '' || val === this.note.val) {
				this.clear();
				return;
			}
			
			this.note.val = val;
			
			this.parent.saveNote(this.note);
			
			this.clear();			
		},
		
		delete: function() {
			this.parent.deleteNote(this.note);
			
			this.clear();
		},
		
		clear: function() {
			if (this.layover) {
				this.layover.remove();
			}
			
			this.remove();
		}
		
		
	});
	
	

	//create instance of master view
	var app = new AppView();

}(jQuery));


