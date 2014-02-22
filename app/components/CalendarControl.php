<?php

namespace Screwfix;

/**
 * CalendarControl
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarControl extends \Nette\Application\UI\Control {
	
	/**
	 * @var \Screwfix\CalendarRepository 
	 */
	protected $_calendarData;
	
	public function __construct(CalendarData $calendarData)
	{
		parent::__construct();
		
		$this->_calendarData = $calendarData;
	}	
	
	public function render()
	{		
		$dataMonths = $this->_calendarData->spawn();
		
		foreach ($dataMonths as $key => $dataMonth)
		{
			$this->addComponent(new MonthControl($dataMonth), $key);
		}
		
		$this->template->setFile(__DIR__ . '/Calendar.latte');
		
		$this->template->render();
	}
}
