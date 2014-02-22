<?php

namespace Screwfix;

/**
 * ICalendarIterator buids array of calendar days in timestamp from given date to number of recurrences
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
interface ICalendarIterator extends \Iterator {
	
	/**
	 * Gets iterator's array
	 * 
	 * @return array
	 */
	public function getArray();
	
	/**
	 * @return integer
	 */
	public function keyY();
	
	/**
	 * @return int
	 */
	public function keyM();
	
	/**
	 * @return int
	 */
	public function keyD();
}
