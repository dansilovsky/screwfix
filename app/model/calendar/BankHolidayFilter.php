<?php

namespace Screwfix;

/**
 * BankHolidayFilter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class BankHolidayFilter extends CalendarFilter {

	/**
	 * @var string
	 */
	protected $_name = 'bank_holiday';
	
	protected $_overwrites = array('shift', 'holiday');


	/**
	 * @var array
	 */
	private $_bankHolidays;
	
	/**
	 * Example of argument
	 *     array(
	 *         '2012-05-05' => 'Bankholiday 1',
	 *         '2012-08-06' => 'Bankholiday 2',
	 *     )
	 * 
	 * @param array $holidays
	 */
	public function __construct(array $bankHolidays)
	{
		$this->_bankHolidays = $bankHolidays;
	}

	public function day($date)
	{
		return (isset($this->_bankHolidays[$date])) ? $this->_bankHolidays[$date] : null;
	}
}
