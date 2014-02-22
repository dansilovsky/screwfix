<?php

namespace Screwfix;

/**
 * JsCalendarDataControl
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class JsCalendarDataControl extends \Nette\Application\UI\Control {
	
	/**
	 * array of \Nette\Database\Table\Selection instances
	 * @var array 
	 */
	private $_calendarData;
	
	public function __construct(array $calendarMonthData)
	{
		parent::__construct();
		
		$this->_calendarData = $calendarMonthData;
	}
	
}
