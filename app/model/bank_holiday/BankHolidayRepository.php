<?php

namespace Screwfix;

/**
 * BankHolidayRepository
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class BankHolidayRepository extends Repository {

	private $_name = 'bank_holiday';
	
	public function __construct(\Nette\Database\Context $context)
	{
		parent::__construct($this->_name, $context);
	}
	
	/**
	 * Fetch array of bank holidays between given dates
	 *
	 * @param    string   $from      date format yyyy-mm-dd
	 * @param    string   $to        date format yyyy-mm-dd
	 * @return   \Nette\Database\Table\Selection
	 */
	public function between($from, $to)
	{
		return $this->where('date >= ?', $from)
			->where('date <= ?', $to)
			->order('date');
	}
	
}
