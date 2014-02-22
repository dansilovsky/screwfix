<?php

namespace Screwfix;

/**
 * HolidayFilter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class HolidayFilter extends CalendarFilter {

	/**
	 * @var string
	 */
	protected $_name = 'holiday';
	
	protected $_overwrites = array('shift', 'bank_holiday');

	/**
	 * @var array
	 */
	private $_holiday;
	
	/**
	 * Example of argument
	 *     array(
	 *         '2012-05-05' => 0, // all day
	 *         '2012-05-06' => 1, // halfday
	 *     )
	 * 
	 * @param array $holidays
	 */
	public function __construct(array $holidays)
	{
		$this->_holiday = $holidays;
	}

	public function day($date)
	{
		return (isset($this->_holiday[$date])) ? $this->_holiday[$date] : null;
	}
}
