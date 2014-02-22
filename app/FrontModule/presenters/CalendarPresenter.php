<?php

namespace FrontModule;

/**
 * CalendarPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class CalendarPresenter extends \Screwfix\CalendarPresenter {
	
	/**
	 * Array containig todays data: year, month, month name, day
	 * @var array 
	 */
	protected $calendarTodayData;

	/**
	 * Sets data into CalendarPresenter::currentCalendarData property.
	 * Array should contain year, month, month name, day
	 */
	protected function setCalendarTodayData() {
		$now = new \Screwfix\CalendarDateTime();
		
		$this->calendarTodayData = array(
			'year' => (int) $now->format('Y'),
			'month' => (int) $now->format('n'),
			'monthName' => $now->format('F'),
			'day' => (int) $now->format('j'),
		);
	}
}
