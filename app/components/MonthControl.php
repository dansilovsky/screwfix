<?php

namespace Screwfix;

/**
 * MonthControl
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class MonthControl extends \Nette\Application\UI\Control {

	/**
	 * @var \Nette\Database\Table\Selection 
	 */
	private $_calendarMonthData;
	
	public function __construct(CalendarData $calendarMonthData)
	{
		parent::__construct();
		
		$this->_calendarMonthData = $calendarMonthData;
	}
	
	public function render()
	{
		$this->_calendarMonthData->rewind();
		
		$this->template->data = $this->_calendarMonthData;
		
		$this->template->setFile(__DIR__ . '/Month.latte');
		$this->template->render();
	}
	
	/**
	 * Register a helper method with the template
	 * 
	 * @param  string|NULL  $class
	 * @return Nette\Templating\ITemplate
	 */
	protected function createTemplate($class = null)
	{
		$template = parent::createTemplate($class);
		
		$template->registerHelper('monthToStr', function($month){
			
			$monthsMap = array(1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December');
			
			return $monthsMap[$month];
		});
		
		return $template;
	}

}
