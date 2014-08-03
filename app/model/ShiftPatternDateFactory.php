<?php

namespace Screwfix;

/**
 * ShiftPatternDateFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class ShiftPatternDateFactory {

	/**
	 * Creates instance of ShiftPatternDate. 
	 * If no time given then it creates instance set to date.
	 * 
	 * @param string $time
	 * @return \Screwfix\ShiftPatternDate
	 */
	public function create($time = null)
	{
		return new ShiftPatternDate($time);		
	}
	
}
