<?php

namespace Screwfix;

/**
 * CalendarDayPeriod
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDayPeriod extends CalendarPeriod implements ICalendarPeriod {
	
	/**
	 * @var CalendarDateTime
	 */
	private $_from;

	/**
	 * Date format
	 * @var string
	 */
	private $_to;
	
	/**
	 * @var CalendarDateTime 
	 */
	private $_runner;
	
	/**
	 * @var int 
	 */
	private $_yearKey;
	
	/**
	 * @var int 
	 */
	private $_monthKey;
	
	/**
	 * @var int 
	 */
	private $_positionRewindY;
	
	/**
	 * @var int 
	 */
	private $_positionRewindM;
	
	/**
	 * @var int 
	 */
	private $_positionRewindD;
	
	public function __construct(CalendarDateTime $from, CalendarDateTime $to)
	{		
		$this->_setUp($from, $to);
	}
	
	protected function _setUp(CalendarDateTime $from, CalendarDateTime $to)
	{
		$this->_from = $from;
		
		// setup iterator starting positions
		$this->_positionY = $this->_positionRewindY = $this->_from->getYear();		
		$this->_positionM = $this->_positionRewindM = $this->_from->getMonth();
		$this->_positionD = $this->_positionRewindD = $this->_from->getDay() - 1;
		
		$this->_to = $to->format(self::FORMAT_DATE);

		$this->_buildArray();
	}
	
	protected function _buildArray()
	{		
		$this->_runner = $this->_from;
		
		$this->_yearKey = $this->_runner->getYear();
		$this->_monthKey = $this->_runner->getMonth();
		
		$this->_array[$this->_yearKey] = array();

		$dayIndex = $this->_runner->getDay() - 1;
		
		while ($this->_runner->format(self::FORMAT_DATE) <= $this->_to)
		{
			if ($this->_runner->isFirstDayOfMonth())
			{
				$dayIndex = $this->_runner->getDay() - 1;
				
				if ($this->_runner->isFirstDayOfYear())
				{
					$this->_yearKey = $this->_runner->getYear();
					
					$this->_array[$this->_yearKey] = array();
				}
				
				$this->_monthKey = $this->_runner->getMonth();
					
				$this->_array[$this->_yearKey][$this->_monthKey] = array();
			}
			
			$this->_array[$this->_yearKey][$this->_monthKey][$dayIndex] = $this->_runner->format(self::FORMAT_DATE);

			$this->_runner->addDay();
			
			$dayIndex++;
		}
	}
	
	public function rewind()
	{
		$this->_positionY = $this->_positionRewindY;
		
		$this->_positionM = $this->_positionRewindM;
		
		$this->_positionD = $this->_positionRewindD;
	}
}
