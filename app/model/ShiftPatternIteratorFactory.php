<?php

namespace Screwfix;

/**
 * PatternIteratorFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class ShiftPatternIteratorFactory {
	
	/** 
	 * @var ShiftPatternDate
	 */
	private $shiftPatternDate;
	
	public function __construct(ShiftPatternDate $shiftPatternDate)
	{
		$this->shiftPatternDate = $shiftPatternDate;
	}

	/**
	 * Creates new instance of ShiftPatternIteratorFactory set to current date
	 * 
	 * @param array $pattern  pattern array
	 * @return ShiftPatternIterator
	 */
	public function create(array $pattern)
	{
		return new ShiftPatternIterator($pattern, $this->shiftPatternDate);
	}
}
