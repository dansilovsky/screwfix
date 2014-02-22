<?php

namespace Screwfix;

/**
 * CalendarMonthIterator
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarMonthPeriod extends CalendarPeriod implements ICalendarPeriod {

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

	public function __construct(CalendarDateTime $from, CalendarDateTime $to)
	{		
		$this->_setUp($from, $to);
	}

	protected function _setUp(CalendarDateTime $from, CalendarDateTime $to)
	{		
		$this->_from = $from;	

		$this->_from->floor()
			->format(self::FORMAT_DATE);
		
		// setup iterator starting positions
		$this->_positionY = $this->_positionRewindY = $this->_from->getYear();		
		$this->_positionM = $this->_positionRewindM = $this->_from->getMonth();		
		// day position always starts from 0;
		$this->_positionD = 0;

		$this->_to = $to->format(self::FORMAT_DATE);

		$this->_buildArray();
	}

	protected function _buildArray()
	{		
		$this->_runner = $this->_from;
		
		$this->_yearKey = $this->_runner->getYear();
		$this->_monthKey = $this->_runner->getMonth();
		
		$this->_array[$this->_yearKey] = array();

		while ($this->_runner->format(self::FORMAT_DATE) <= $this->_to)
		{
			if ($this->_runner->isFirstDayOfMonth())
			{
				if ($this->_runner->isFirstDayOfYear())
				{
					$this->_yearKey = $this->_runner->getYear();
					
					$this->_array[$this->_yearKey] = array();
				}
				
				$this->_monthKey = $this->_runner->getMonth();
					
				$this->_array[$this->_yearKey][$this->_monthKey] = array();
				
				$this->_padDown();
				continue;
			}
			if ($this->_runner->isLastDayOfMonth())
			{
				$this->_padUp();
				continue;
			}

			$this->_array[$this->_yearKey][$this->_monthKey][] = $this->_runner->format(self::FORMAT_DATE);

			$this->_runner->addDay();
		}
	}

	protected function _padDown()
	{
		$stop = $this->_runner->format(self::FORMAT_DATE);

		$this->_runner->floor(CalendarDateTime::W);

		while ($this->_runner->format(self::FORMAT_DATE) <= $stop)
		{
			$this->_array[$this->_yearKey][$this->_monthKey][] = $this->_runner->format(self::FORMAT_DATE);

			$this->_runner->addDay();
		}


	}

	protected function _padUp()
	{
		$start = $this->_runner->format(self::FORMAT_DATE);

		$end = $this->_runner->ceil(CalendarDateTime::W)->format(self::FORMAT_DATE);

		$this->_runner->modify($start);

		while ($this->_runner->format(self::FORMAT_DATE) <= $end)
		{
			$this->_array[$this->_yearKey][$this->_monthKey][] = $this->_runner->format(self::FORMAT_DATE);

			$this->_runner->addDay();
		}

		$this->_runner->modify($start)->addDay();
	}
	
	public function rewind()
	{
		$this->_positionY = $this->_positionRewindY;
		
		$this->_positionM = $this->_positionRewindM;
		
		$this->_positionD = 0;
	}
}
