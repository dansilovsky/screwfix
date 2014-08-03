<?php

namespace Screwfix;

/**
 * ShiftPatternFilterFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class ShiftPatternFilterFactory {

	/** 
	 * @var ShiftPatternDate
	 */
	private $shiftPatternDate;
	
	public function __construct(ShiftPatternDateFactory $shiftPatternDateFactory)
	{
		$this->shiftPatternDate = $shiftPatternDateFactory->create();
	}

	/**
	 * @param array $pattern  pattern array
	 * @return ShiftPatternFilter
	 */
	public function create(array $pattern)
	{
		$shiftPatternFilter = new ShiftPatternFilter($this->shiftPatternDate);
		
		$shiftPatternFilter->setPattern($pattern);
		
		return $shiftPatternFilter;
		
	}
}
