<?php

namespace Screwfix;

/**
 * BankHolidayProcessor
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class BankHolidayProcessor extends CalendarDataProcessor {

	protected $_name = 'bank_holiday';
	
	protected $_type = 'work';
	
	/**
	 * @param array $day
	 * @return string
	 * @throws CalendarProcessorExeption
	 */
	public function process(array $day)
	{
		$data = $this->extractData($day);
		
		return $data;
	}
}
