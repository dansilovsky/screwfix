<?php

namespace Screwfix;

/**
 * CalendarPeriod
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class CalendarPeriod extends CalendarIterator {	

	const FORMAT_DATE = 'Y-m-d';
	
	public function set(CalendarDateTime $from, CalendarDateTime $to)
	{
		$this->_array = array();

		$this->_setUp($from, $to);
	}
}
