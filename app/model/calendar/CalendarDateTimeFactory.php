<?php

namespace Screwfix;

/**
 * CalendarDateTimeFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDateTimeFactory {
	
	/**
	 * @param string $time  a date/time string.
	 * @return CalendarDateTime
	 */
	public function create($time = 'now')
	{
		return new CalendarDateTime($time);
	}
}
