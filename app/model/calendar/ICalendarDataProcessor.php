<?php

namespace Screwfix;

/**
 * ICalendarDataProcessor
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
interface ICalendarDataProcessor {
	
	/**
	 * Returns name of processor. 
	 * Must be the same name as a name of an instance of ICalendarFilter.
	 * 
	 * @return string
	 */
	public function name();

	/**
	 * Returns a type of processor. 
	 * 
	 * @return string
	 */
	public function type();
	
	/**
	 * Takes an array with day data. 
	 * Processes only part of an array that corresponds with the name of this processor. 
	 * Returns resulted html output.
	 * 
	 * @param   array   $day
	 * @return  string
	 */
	public function process(array $day);
	
	
}