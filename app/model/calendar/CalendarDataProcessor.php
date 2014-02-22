<?php

namespace Screwfix;

/**
 * CallendarProcessor
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class CalendarDataProcessor implements ICalendarDataProcessor {

	public function type()
	{
		return $this->_type;
	}
	
	public function name()
	{
		return $this->_name;
	}
	
	protected function extractData(array $day)
	{
		return $day[CalendarData::KEY_DATA][$this->_name];
	}
		
}
