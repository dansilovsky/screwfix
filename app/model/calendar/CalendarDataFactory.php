<?php

namespace Screwfix;

/**
 * CalendarDataFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDataFactory {

	/**
	 * Create instance of CalendarData
	 * @param \Screwfix\CalendarPeriod $calendarPeriod
	 * @return \Screwfix\CalendarData
	 */
	public function create(CalendarPeriod $calendarPeriod)
	{
		return new CalendarData($calendarPeriod);
	}
}
