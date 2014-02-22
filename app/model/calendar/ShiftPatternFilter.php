<?php

namespace Screwfix;

/**
 * ShiftPatternFilter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class ShiftPatternFilter extends CalendarFilter {

	/**
	 * @var string
	 */
	protected $_name = 'shift';

	/**
	 * @var array
	 */
	private $_pattern;

	/**
	 * Number of weeks in this shift pattern
	 * @var int
	 */
	private $_weeksInPattern;

	/**
	 * @var ShiftPatternDate
	 */
	private $_patternDate;

	public function __construct(ShiftPatternDate $patternDate)
	{
		$this->_patternDate = $patternDate;
	}

	public function setPattern(array $pattern)
	{
		$this->_pattern = $pattern;
		$this->_weeksInPattern = count($pattern);
	}

	/**
	 * Takes a date and returns working hours or null(off work) on that particular date given.
	 *
	 * @param   string   $date        a date in date format
	 * @throws \InvalidArgumentException
	 * @thows  ShiftPattern_ArrayNotSet_Exeption
	 * @return array|null                    array contains start and end time of a shift (eg. array('07:00', '15:00')), if off work returns null
	 */
	public function day($date)
	{
		if ($this->_pattern === null)
		{
			throw new ShiftPatternFilter_ArrayNotSet_Exception;
		}
		// set a date for which you want to get working hours
		$this->_patternDate->set($date);

		$week = $this->_patternDate->week($this->_weeksInPattern);

		$day = $this->_patternDate->day();

		return $this->_pattern[$week][$day];
	}
}