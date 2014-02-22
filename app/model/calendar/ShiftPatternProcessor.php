<?php

namespace Screwfix;

/**
 * ShiftPatternProcessor
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class ShiftPatternProcessor extends CalendarDataProcessor {
	
	protected $_name = 'shift';
	
	protected $_type = 'work';
	
	public function process(array $day)
	{
		$workingHours = $this->extractData($day);
		
		return $workingHours[0].' - '.$workingHours[1];
	}
}
