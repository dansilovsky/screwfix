<?php

namespace Screwfix;

/**
 * CalendarDateTime
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDateTime extends DateTime {
	
	const W = 0,
	      M = 1,
	      Y = 2;
	
	/**
	 * Rounds date down. 
	 * So far it supporst only rounding to a year, month, and week
	 * 
	 * @param  int  $unit  date unit to which we watnt to round date down
	 * @return CalendarDateTime  
	 */
	public function floor($unit = self::M)
	{
		switch ($unit)
		{
			case self::W:
				$weekDay = (int) $this->format('N');
				$sub = $weekDay -1;
				$this->sub(new \DateInterval('P'.$sub.'D'));
				break;
			
			case self::M:
				list($year, $month) = explode(',', $this->format('Y,n'));
				$this->setDate((int) $year, (int) $month, 1);
				break;
			
			case self::Y:
				$year = (int) $this->format('Y');
				$this->setDate($year, 1, 1);
				break;

			default:
				break;
		}
		
		$this->setTime(0, 0, 0);
		
		return $this;
	}
	
	/**
	 * Rounds date down on the clone of this instance and returns it. 
	 * So far it supporst only rounding to a year and a month.
	 * 
	 * @param  int  $unit  date unit to which we watnt to round date down
	 * @return CalendarDateTime  
	 */
	public function floorClone($unit = self::M)
	{
		$dolly = clone $this;
		return $dolly->floor($unit);
	}
	
	/**
	 * Rounds date up.
	 * So far it supporst only rounding to a year, month, and week.
	 * 
	 * @param type $unit
	 * @return \Screwfix\CalendarDateTime
	 * @throws \InvalidArgumentException
	 */
	public function ceil($unit = self::M)
	{
		switch ($unit)
		{
			case self::W:
				$weekDay = (int) $this->format('N');
				$add = 7 - $weekDay;
				$this->add(new \DateInterval('P'.$add.'D'));
				break;
			
			case self::M:
				list($year, $month, $daysInMonth) = explode(',', $this->format('Y,n,t'));				
				$this->setDate((int) $year, (int) $month, (int) $daysInMonth);
				break;
			
			case self::Y:
				$year = $this->format('Y');
				$this->setDate((int) $year, 12, 31);
				break;

			default:
				throw new \InvalidArgumentException();
				break;
		}
		
		$this->setTime(0, 0, 0);
		
		return $this;
	}
	
	/**
	 * Rounds date up on the clone of this instance and returns it. 
	 * So far it supporst only rounding to a year and a month.
	 * 
	 * @param  int  $unit  date unit to which we watnt to round date down
	 * @return CalendarDateTime  
	 */
	public function ceilClone($unit = self::M)
	{
		$dolly = clone $this;
		return $dolly->ceil($unit);
	}
	
	/**
	 * First day is monday
	 * 
	 * @return bool
	 */
	public function isFirstDayOfWeek()
	{
		return ($this->format('w') === '1');
	}
	
	/**
	 * Last day is sunday
	 * 
	 * @return bool
	 */
	public function isLastDayOfWeek()
	{
		return ($this->format('w') === '0');
	}
	
	/**
	 * @return bool
	 */
	public function isFirstDayOfMonth()
	{
		return ($this->format('j') === '1');
	}
	
	/**
	 * @return bool
	 */
	public function isLastDayOfMonth()
	{
		return ($this->format('j') === $this->format('t'));
	}
	
	/**
	 * @return bool
	 */
	public function isFirstDayOfYear()
	{
		return ($this->format('nj') === '11');
	}
}
