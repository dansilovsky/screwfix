<?php

namespace Screwfix;

/**
 * HolidayRepository
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class HolidayRepository extends Repository {

	private $_name = 'holiday';
	
	public function __construct(\Nette\Database\Context $context)
	{
		parent::__construct($this->_name, $context);
	}
	
	/**
	 * Fetch selection for given date and user id
	 * 
	 * @param string $date
	 * @param integer $user_id
	 * @return \Nette\Database\Table\Selection
	 */
	public function getByDateUser($date, $user_id)
	{
		return $this->where('date', $date)
			->where('user_id', $user_id);
	}
	
	/**
	 * Fetch selection of holidays between given dates
	 *
	 * @param    integer  $user_id   user id
	 * @param    string   $from      date format yyyy-mm-dd
	 * @param    string   $to        date format yyyy-mm-dd
	 * @return   \Nette\Database\Table\Selection
	 */
	public function between($user_id, $from, $to)
	{
		return $this->where('user_id', $user_id)
			->where('date >= ?', $from)
			->where('date <= ?', $to)
			->order('date');
	}
	
	/**
	 * Inserts new holiday.
	 * 
	 * @param string $date
	 * @param integer $halfday
	 * @param integer $user_id
	 * @return \Nette\Database\IRow  inserted row
	 */
	public function save($date, $halfday, $user_id)
	{
		$data = array(
			'date' => $date,
			'halfday' => (string) $halfday,
			'user_id' => $user_id
		);
		
		return $this->insert($data);
	}
	
}
