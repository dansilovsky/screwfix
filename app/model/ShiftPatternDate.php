<?php

namespace Screwfix;

/**
 * ShiftPatternDate
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class ShiftPatternDate extends \Nette\DateTime {
	
	/**
	 * Start date Monday 1970-01-05 00:00:00
	 */
	const START = '1970-01-05 00:00:00';
	
	private $_start;
	
	public function __construct($time = null, $object = null)
	{
		parent::__construct($time, $object);
		
		$this->setTime(0, 0, 0);
		
		$this->_start = new \Nette\DateTime(self::START);
	}


	/**
	 * 0 = monday, ... , 6 = sunday
	 * 
	 * @return   int   numeric representation of the day of the week
	 */
	public function day()
	{
		$day = (int) $this->format('N');
		
		return $day - 1;
	}
	
	/**
	 * Returns numeric representation of the week in pattern. From 0 to $weeksInPattern - 1
	 * 
	 * @param  int  $weeksInPattern   number of weeks in pattern
	 * @throws ShiftPatternDateException
	 * @return int
	 */
	public function week($weeksInPattern)
	{
		$interval = date_diff($this->_start, $this);
		
		$days = (int) $interval->format('%R%a');
		
		if ($days < 0)
		{
			throw new ShiftPatternDateException('ShiftPatterdDate date must be equal or greater than start date.');
		}
		
		$weeks = (int) $days/7;
		
		$weekPatternNum = $weeks % $weeksInPattern;
		
		if ($weekPatternNum === 0)
		{
			$weekPatternNum = $weeksInPattern - 1;
		}
		else
		{
			$weekPatternNum--;
		}
		
		return $weekPatternNum;
		
	}
	
	/**
	 * Sets date
	 * 
	 * @param  string $date   must be >= 1970-01-05 00:00:00
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function set($date)
	{
		$this->modify($date);
		
		return $this;
	}
	
	/**
	 * Overides parent to check validity of an argument.
	 * 
	 * @param  string $date
	 * @throws \InvalidArgumentException
	 */
	public function modify($date)
	{
		parent::modify($date);
		
		if ($date < self::START)
		{
			throw new \InvalidArgumentException('Argument must be a date equal or greater than ' . self::START . '.');
		}
	}
}
