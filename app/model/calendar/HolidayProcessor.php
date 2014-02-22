<?php

namespace Screwfix;

/**
 * HolidayProcessor
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class HolidayProcessor extends CalendarDataProcessor {

	protected $_name = 'holiday';
	
	protected $_type = 'work';
	
	/**
	 * @param array $day
	 * @return string
	 * @throws CalendarProcessorExeption
	 */
	public function process(array $day)
	{
		$data = $this->extractData($day);
		
		if ($data === 0)
		{
			return 'Holiday';
		}
		else if ($data === 1)
		{
			return 'Halfday holiday';
		}
		
		throw new CalendarProcessorException('Invalid data extracted from argument. Only 0, 1 allowed');
	}
}
