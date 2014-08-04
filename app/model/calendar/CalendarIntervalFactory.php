<?php

namespace Screwfix;

/**
 * CalendarIntervalFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarIntervalFactory {

	/**
	 * Creates instance of \Screwfix\CalendarInterval
	 * 
	 * @param integer  $recurrences
	 * @param string   $periodDesignator
	 * @return \Screwfix\CalendarInterval
	 */
	public function create($recurrences = 1, $periodDesignator = CalendarInterval::Y) 
	{
		return new CalendarInterval($recurrences, $periodDesignator);
	}
}
