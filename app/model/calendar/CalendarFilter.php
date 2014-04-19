<?php

namespace Screwfix;

/**
 * CalendarFilter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class CalendarFilter extends \Nette\Object implements ICalendarFilter {
	
	/**
	 * Array of other filters that are overwritten by this filter.
	 * @var array
	 */
	protected $_overwrites = array();
	
	public function name()
	{
		// $this->_name must be declared in a child class
		return $this->_name;
	}
	
//	public function filter($date, array &$result)
//	{
//		$data = $this->day($date);		
//		
//		if ($data !== null)
//		{
//			foreach ($this->_overwrites as $name)
//			{
//				if (isset($result[$name]))
//				{
//					unset($result[$name]);
//				}
//			}
//			
//			$result[$this->_name] = $this->day($date);
//		}		
//	}
	
	public function filter($date, array &$result)
	{
		$data = $this->day($date);		
		
		if ($data !== null)
		{			
			$result[$this->_name] = $this->day($date);
		}		
	}
}
