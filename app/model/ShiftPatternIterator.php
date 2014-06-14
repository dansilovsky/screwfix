<?php

namespace Screwfix;

/**
 * PatternIterator iterates over the shift pattern from a current shift pattern week.
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class ShiftPatternIterator extends \Dan\Iterators\AroundIterator {

	protected $firstDay;
	
	public function __construct(array $pattern, ShiftPatternDate $shiftPatternDate)
	{
		parent::__construct($pattern);
		
		$startPosition = $shiftPatternDate->week(count($pattern));
		
		$this->setStart($startPosition);
	}
}
