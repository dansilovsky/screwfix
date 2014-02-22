<?php

namespace Screwfix;

/**
 * CalendarDataInterpreter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDataInterpreter extends CalendarIterator {

	/**
	 * Calendar data array
	 * @var array
	 */
	protected $_array = array();
	
	/**
	 * Array of instances of ICalendarDataProcessor
	 * @var array 
	 */
	protected $_processorsNote = array();

	/**
	 * Array of instances of ICalendarDataProcessor
	 * @var array 
	 */
	protected $_processorsWork = array();


	protected $_positionRewindY;
	protected $_positionRewindM;

	public function __construct(CalendarData $calendarData)
	{
		$this->_array = $calendarData->getArray();
		
		$calendarData->rewind();
		$this->_positionRewindY = $calendarData->keyY();
		$this->_positionRewindM = $calendarData->keyM();
	}
	
	public function addProcessor(ICalendarDataProcessor $processor)
	{
		switch ($processor->type())
		{
			case 'work':
				$this->_processorsWork[] = $processor;
				break;
			case 'note':
				$this->_processorsNote[] = $processor;
				break;
			default:
				throw new CalendarDataInterpreter_InvalidCalendarDataProcessorType_Exception;
				break;
		}
	}	
	
	public function rewind()
	{
		$this->_positionY = $this->_positionRewindY;		
		$this->_positionM = $this->_positionRewindM;		
		$this->_positionD = 0;
	}
	
	public function work()
	{
		
	}
	
	public function note()
	{
		
	}
	
	public function isWeekStart()
	{
		
	}
	
	public function isWeekEnd()
	{
		
	}
}
