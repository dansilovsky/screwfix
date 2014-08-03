<?php

namespace Screwfix;

/**
 * CalendarDayPeriodFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDayPeriodFactory {

	/**
	 * Create instance of \Screwfix\CalendarDayPeriod
	 * 
	 * @param \Screwfix\CalendarDateTime $from
	 * @param \Screwfix\CalendarDateTime $to
	 * @return \Screwfix\CalendarDayPeriod
	 */
	public function create(CalendarDateTime $from, CalendarDateTime $to) 
	{
		return new CalendarDayPeriod($from, $to);
	}
}
