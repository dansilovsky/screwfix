<?php

namespace Screwfix;

/**
 * ICalendarPeriod
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
interface ICalendarPeriod {

	/**
	 * Set calendar iterator
	 * 
	 * @param CalendarDateTime  $from	   
	 * @param CalendarDateTime  $to
	 */
	public function set(CalendarDateTime $from, CalendarDateTime $to);
}
