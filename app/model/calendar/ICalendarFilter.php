<?php

namespace Screwfix;

/**
 * ICalendarFilter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
interface ICalendarFilter {

	/**
	 * Returns name of filter
	 *
	 * @return string
	 */
	public function name();
	
	/**
	 * Takes date for which to run filter against.
	 * The argument $result is edited accordingly.
	 * It also overwrites results of other filters if their names are defined in property overwritable.
	 *  
	 * @param string $date  date format
	 * @param array  $result
	 */
	public function filter($date, array &$result);

	/**
	 * Takes date for which to run filter against.
	 * It returns string with value. Null if nothig happens on given day.
	 *
	 * @param   string $date  date format
	 * @return  mixed
	 */
	public function day($date);
}
