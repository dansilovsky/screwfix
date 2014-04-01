(function($){

	var root = this;

	var Zidane = root.Zidane = {};

	/**
	 * 
	 * @param {int} year
	 * @param {int} month
	 * @param {int} day
	 * @param {callback} format callback function to format date to string, function takes 3 arguments year, month and day
	 */
	var Calendar = Zidane.Calendar = function(year, month, day, format) {
		// constants
		var DAY = 24 * 60 * 60 * 1000;
		var WEEK = 7 * DAY;
		// number of last day of week
		var LDW = 6;
		// number of first day of week
		var FWD = 0;		
		var MONTH_STRINGS = {1: 'january', 2: 'february', 3: 'march', 4: 'april', 5: 'may', 6: 'june', 7: 'july', 8: 'august', 9: 'september', 10: 'october', 11: 'november', 12: 'december'}
		var WEEK_DAY_STRINGS = {0: 'monday', 1: 'tuesday', 2: 'wednesday', 3: 'thursday', 4: 'friday', 5: 'saturday', 6: 'sunday'};
		
		var format = format || function(year, month, day) {
			var y = year;
			var m = month < 10 ? '0' + month : month;
			var d = day < 10 ? '0' + day : day;
			
			return y + '-' + m + '-' + d;
		};

		var y = year || 1970;
		var m = month || 1;
		var d = day || 1;
		
		m--;
		
		var date = new Date(y, m, d);
		var helperDate = new Date();

		return {
			clone: function() {
				var y = this.getYear();
				var m = this.getMonth();
				var d = this.getDate();
				
				return new Calendar(y, m, d, format);			
			},

			set: function(year, month, day) {
				var y = year;
				var m = month || 1;
				var d = day || 1;

				this.setYear(y);
				this.setMonth(m);
				this.setDate(d);

				return this;
			},
			
			/**
			 * Set date from given string
			 * 
			 * @param {string} date  date string in format yyyy-mm-dd
			 * @returns {this}
			 */
			setFromStr: function(date) {
				var y = date.substr(0, 4);
				var m = date.substr(5, 2);
				var d = date.substr(8,2);
				
				this.set(y, m, d);
				
				return this;
			},
			
			/**
			 * Attaches a callback format function to format
			 * 
			 * @param {function} callback  formating function. Takes 4 arguments year, month, day and this. Callback must return formated date string.
			 * @returns {this}
			 */	
			setFormat: function(callback) {
				format = callback;
				
				return this;
			},

			/**
			 * Returns number of days in month of year
			 *
			 * @returns {int}
			 */
			getDaysInMonth: function() {
				helperDate.setFullYear(date.getFullYear(), date.getMonth() + 1, 1);

				helperDate.setDate(0);

				var daysInMonth = helperDate.getDate();

				return daysInMonth;
			},
				
			startMonth: function() {
				this.setDate(1);
				
				return this;
			},
				
			endMonth: function() {
				var end = this.getDaysInMonth();
				
				this.setDate(end);
				
				return this;
			},

			startWeek: function() {
				var weekDay = this.getDay();
				
				var day = this.getDate() - weekDay;
				
				this.setDate(day);
				
				return this;
			},

			endWeek: function() {
				var weekDay = this.getDay();
				
				var day = (LDW - weekDay) + this.getDate();
				
				this.setDate(day);
				
				return this;
			},
			
			prevWeek: function() {
				var day = this.getDate() - 7;				
				this.setDate(day);
				
				return this;
			},
			
			nextWeek: function() {
				var day = this.getDate() + 7;				
				this.setDate(day);
				
				return this;
			},
				
			prevDay: function(count) {
				var count = count || 1;
				var day = this.getDate();
				
				day-=count;
				
				this.setDate(day);
				
				return this;
			},
				
			nextDay: function(count) {
				var count = count || 1;
				var day = this.getDate();
				
				day+=count;
				
				this.setDate(day);
				
				return this;
			},
			
			isFirstDayOfWeek: function() {
				var helperDate = this.clone();
				helperDate.startWeek();
				
				return helperDate.getDate() === this.getDate();
			},
			
			isLastDayOfWeek: function() {
				var helperDate = this.clone();
				helperDate.endWeek();
				
				return helperDate.getDate() === this.getDate();
			},
			
			/**
			 * Moves date to a previous month. If argument provided moves date by given number of months.
			 * @param {int} count  number of month to be substracted, if count < 1 then is one month substracted
			 * @returns {this}
			 */	
			prevMonth: function(count) {
				var count = count || 1;
				var month = this.getMonth();
				var day = this.getDate();
				
				month-=count;
				
				this.setDate(1);
				this.setMonth(month);
				
				var daysInPrevMonth = this.getDaysInMonth();
				
				if (day > daysInPrevMonth) {
					day = daysInPrevMonth;
				}
				
				this.setDate(day);
				
				return this;
			},
			
			/**
			 * Moves date to a next month. If argument provided moves date by given number of months.
			 * @param {int} count  number of month to be added, if count < 1 then is one month added
			 * @returns {this}
			 */	
			nextMonth: function(count) {
				var count = count || 1;
				var month = this.getMonth();				
				var day = this.getDate();
				
				month+= count;
				
				this.setDate(1);
				this.setMonth(month);
				
				var daysInNextMonth = this.getDaysInMonth();
				
				if (day > daysInNextMonth) {
					day = daysInNextMonth;
				}
				
				this.setDate(day);
				
				return this;
			},

			// facade methods that just copy methos of Date object
			toString: function() {
				
				if (format) {
					return format.call(this, this.getYear(), this.getMonth(), this.getDate());
				}
				
				return date.toDateString();
			},
				
			getYear: function() {
				return date.getFullYear();
			},
			
			/**
			 * Get month. Integer 1 to 12. Not like native Date object 0 to 11
			 * @returns {int}
			 */
			getMonth: function() {
				return date.getMonth() + 1;
			},
				
			getMonthString: function() {
				return MONTH_STRINGS[this.getMonth()];
			},
			
			getWeekDayString: function() {
				return WEEK_DAY_STRINGS[this.getDay()];
			},

			getDate: function() {
				return date.getDate();
			},
			
			/**
			 * Returns day of week. Not like in Date object. 
			 * First day of week is 0 = monday, 1 = tuesday and so on. 
			 * 
			 * @returns {int}
			 */
			getDay: function() {
				var map = {0: 6, 1: 0, 2: 1, 3: 2, 4: 3, 5: 4, 6: 5};
				
				return map[date.getDay()];
			},

			setYear: function(y) {
				date.setFullYear(y);	
				return this;
			},
			
			/**
			 * Set month.
			 * 
			 * @param {int}  m  month 1 to 12 not like native Date object 0 to 11
			 * @returns {this}
			 */
			setMonth: function(m) {
				m--;
				date.setMonth(m);
				return this;
			},

			setDate: function(d) {
				date.setDate(d);
				return this;
			},
		}

	}
	
	/**
	 * Object representing a user
	 * 
	 * @param {object}     user	eg.{role: member}
	 * @param {Zidane.Acl} acl
	 */
	var User = Zidane.User = function(user, acl) {
		var user = user;
		var acl = acl;
		
		return {
			getRole: function() {
				return user.role;
			},
			
			/**
			 * Is allowed only if users role matches or is higher privelage than demanded role
			 * 
			 * @param {string} demandedRole
			 * @returns {bool}
			 */
			isAllowed: function(demandedRole) {
				return acl.isAllowed(demandedRole, user.role);
			}
		}
	}

	/**
	 * Lightweight permissions
	 * 
	 * @param {array} rolesArray 
	 */
	var Acl = Zidane.Acl = function(rolesArray) {		
		var roles = {};
		
		for (var i=0; i<rolesArray.length; i++) {
			roles[rolesArray[i]] = i;
		}
		
		return {
			/**
			 * Is allowed only if given usersRole matches or is higher privalege than role demanded.
			 * 
			 * @param {string} roleDemanded
			 * @param {string} usersRole
			 * @returns {bool} true if allowed otherwis false
			 */
			isAllowed: function(roleDemanded, usersRole) {
				if (_.isUndefined(roles[roleDemanded])) {
					throw 'Demanded role is undefined';
				}
				if (_.isUndefined(roles[usersRole])) {
					throw "User's role is undefined";
				}
				
				return (roles[roleDemanded] <= roles[usersRole]);				
			}
		}
	}
	
	// acl constants
	Acl.GUEST = 'guest';
	Acl.MEMBER = 'member';
	Acl.EDITOR = 'editor';
	Acl.ADMIN = 'admin';
	
	/**
	 * Placer places a popup boxes to positions calculated 
	 * from positions of given parent cells in a grid.
	 */
	var Placer = Zidane.Placer = function() {
		var 
		boxH, boxW, boxHHalf, boxWHalf,
		cellH, cellW, cellHHalf, cellWHalf, 
		cellT, cellL, cellTCenter, cellLCenter, 
		docH, docW;
	
		var offset = 5;
		
		var P = {
			$doc: $(document)
		}
		
		var setup = function($box, $cell) {
			boxH = $box.outerHeight();
			boxW = $box.outerWidth();

			boxHHalf = Math.floor(boxH/2);
			boxWHalf = Math.floor(boxW/2);

			cellH = $cell.outerHeight();
			cellW = $cell.outerWidth();

			cellHHalf = Math.floor(cellH/2);
			cellWHalf = Math.floor(cellW/2);

			docH =  P.$doc.height();
			docW = P.$doc.width();

			var cellOffset = $cell.offset();

			cellT = cellOffset.top;
			cellL = cellOffset.left;

			cellTCenter = cellT + cellHHalf;
			cellLCenter = cellL + cellWHalf;
		}
		
		var checkTop = function() {
			return (cellT - offset - boxH - offset > 0);
		}
		
		var checkBottom = function() {
			return (cellT + cellH + offset + boxH + offset < docH);
		}
		
		var checkLeft = function() {
			return (boxWHalf + offset < cellLCenter);
		}
		
		var checkRight = function() {
			return (cellLCenter + boxWHalf + offset < docW);
		}
		
		var checkSideTop = function() {
			return (cellTCenter - boxHHalf > 0);
		}
		
		var checkSideBottom = function() {
			return (cellTCenter + boxHHalf < docH);
		}
		
		var placeTop = function() {
			var top = cellT - offset - boxH;
			
			if (!checkTop()) {
				top = offset;
			}
			
			return {
				top: top,
				left: cellLCenter - boxWHalf
			}
		}
		
		var placeBottom = function() {
			var top = cellT + cellH + offset;
			
			if (!checkBottom()) {
				top = docH - offset;
			}
			
			return {
				top: top,
				left: cellLCenter - boxWHalf
			}
		}
		
		var placeLeft = function() {
			var top = cellTCenter - boxHHalf;
			
			if (!checkSideBottom()) {
				top = docH - offset - boxH;
			}
			
			if (!checkSideTop()) {
				top = offset;
			}
			
			return {
				top: top,
				left: cellL - offset - boxW
			}
		}
		
		var placeRight = function() {
			var top = cellTCenter - boxHHalf;
			
			if (!checkSideBottom()) {
				top = docH - offset - boxH;
			}
			
			if (!checkSideTop()) {
				top = offset;
			}
			
			return {
				top: top,
				left: cellL + cellW + offset
			}
		}
		
		return {
			/**
			 * Returns new offset position of given $box calculeted from $cell position
			 * @param {type} $box
			 * @param {type} $cell
			 * @returns {object} eg. {top: 100, left: 50}
			 */
			offset: function($box, $cell) {
				if (checkLeft() && checkRight()) {
					return checkBottom() ? placeBottom() : placeTop();
				}
				
				if (checkLeft()) {
					return placeLeft();
				}
				else {
					return placeRight();
				}
				
				var smaz = 0;
				
				return placeTop();
			},
			
			/**
			 * Places a $box to new position calculated from a $cell position
			 * @param {jQuery} $box
			 * @param {jQuery} $cell
			 */
			place: function($box, $cell) {
				setup($box, $cell);
				
				var offset = this.offset();
				
				$box.css({top: offset.top, left: offset.left})				
			}
		}
	}	
	
	// Utilities
	Zidane.capitalize = function(str) {
		return str.charAt(0).toUpperCase() + str.substring(1).toLowerCase();
	};
	
	/*
	* Function creates new element and wraps it in jQuery object
	* @param  string   el           name of element
	* @param  string   className    name of class
	* @return  object               element given by parameter wrapped in jQuery object
	*/
	Zidane.create = function(el, className) {
		var $el = $(document.createElement(el));
		if (className) {
			$el.addClass(className);
		}
		return $el;
	}

}).call(this, jQuery);