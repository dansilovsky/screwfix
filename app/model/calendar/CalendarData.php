<?php

namespace Screwfix;

/**
 * CalendarArray puts particular filters together and creates caledar array frame from them.
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarData extends CalendarIterator implements \Iterator {

	const SPAWN_MODE_MONTH = 2;
	const SPAWN_MODE_YEAR = 3;

	/**
	 * Key names used in day array
	 */
	const KEY_DAY = 'day';
	const KEY_DATA = 'data';

	/**
	 * @var CalendarPeriod
	 */
	private $_calendarPeriod;

	/**
	 * @var int
	 */
	protected $_positionRewindY;

	/**
	 * @var int
	 */
	protected $_positionRewindM;

	/**
	 * @var int
	 */
	protected $_positionRewindD;

	/**
	 * Array of IFilterItem filters.
	 * @var array
	 */
	private $_filters = array();

	/**
	 * Count of filters
	 * @var int
	 */
	private $_filtersCount = 0;

	/**
	 *
	 * @var CalendarDateTime
	 */
	private $_dateHelper;

	public function __construct(CalendarPeriod $calendarPeriod = null)
	{
		$this->_calendarPeriod = $calendarPeriod;

		$this->_dateHelper = new CalendarDateTime();
	}

	public function addFilter(ICalendarFilter $filter)
	{
		$this->_filters[] = $filter;

		$this->_filtersCount++;

		return $this;
	}

	/**
	 * Builds calendar array from calendar filters.
	 *
	 * @param  ICalendarIterator  $calendarPeriod
	 * @return CalendarData
	 */
	public function build(CalendarPeriod $calendarPeriod = null)
	{
		if ($calendarPeriod !== null)
		{
			$this->_calendarPeriod = $calendarPeriod;
		}

		if ($this->_calendarPeriod === null)
		{
			throw new CalendarData_CalendarPeriodNotSet_Exception;
		}

		$this->_calendarPeriod->rewind();
		$this->_positionRewindY = $this->_calendarPeriod->keyY();
		$this->_positionRewindM = $this->_calendarPeriod->keyM();
		$this->_positionRewindD = $this->_calendarPeriod->keyD();

		$this->_array = $this->_calendarPeriod->getArray();

		foreach ($this->_calendarPeriod as $date)
		{
			$y = $this->_calendarPeriod->keyY();
			$m = $this->_calendarPeriod->keyM();
			$d = $this->_calendarPeriod->keyD();

			$this->_array[$y][$m][$d] = new CalendarDay($date, $this->_filter($date), $y, $m);
		}

		$this->rewind();

		return $this;
	}

	private function _filter($dayTimestamp)
	{
		if (empty($this->_filters))
		{
			throw new CalendarData_FiltersNotSet_Exception;
		}

		$result = array();

		foreach ($this->_filters as $filter)
		{
			$filter->filter($dayTimestamp, $result);
		}

		return empty($result) ? null : $result;
	}

	public function next()
	{
		switch ($this->_mode)
		{
			case self::ITERATION_MODE_DAY:
				return $this->_nextD();
				break;

			case self::ITERATION_MODE_MONTH:
				return $this->_nextM();
				break;

			case self::ITERATION_MODE_YEAR:
				return $this->_nextY();
				break;

			default:
				throw new CalendarIterator_InvalidIterationMode_Exception;
				break;
		}

		parent::next();
	}

	public function rewind()
	{
		$this->_positionY = $this->_positionRewindY;

		$this->_positionM = $this->_positionRewindM;

		$this->_positionD = $this->_positionRewindD;
	}

	/**
	 *
	 * @param   string   $mode
	 * @return  array   array of CalendarData instances
	 * @throws  CalendarData_InvalidSpawnMode_Exception
	 */
	public function spawn($mode = self::SPAWN_MODE_MONTH)
	{
		$memorizeMode = $this->mode();

		$children = array();

		switch ($mode)
		{
			case self::SPAWN_MODE_MONTH:
				$children = $this->_spawnMonth();
				break;

			case self::SPAWN_MODE_YEAR:
				$children = $this->_spawnYear();
				break;

			default:
				throw new CalendarData_InvalidSpawnMode_Exception;
				break;
		}

		// set iteration mode back to mode before spawning
		$this->mode($memorizeMode);

		return $children;
	}

	private function _spawnMonth()
	{
		$this->mode(self::ITERATION_MODE_MONTH);

		$children = array();

		foreach ($this as $childData)
		{
			$keyYear = $this->keyY();
			$keyMonth = $this->keyM();

			$fullArray = array(
				$keyYear => array(
					$keyMonth => $childData
				)
			);

			$child = new CalendarData;

			$children[] = $child->buildFromArray($fullArray, $keyYear, $keyMonth);
		}

		return $children;
	}

	private function _spawnYear()
	{
		$this->mode(self::ITERATION_MODE_YEAR);

		$children = array();

		foreach ($this as $childData)
		{
			$keyYear = $this->keyY();
			$keyMonth = $this->keyM();

			$fullArray = array($keyYear => $childData);

			$child = new CalendarData;

			$children[] = $child->buildFromArray($fullArray, $keyYear, $keyMonth);
		}

		return $children;
	}

	/**
	 * Should not be used publicly, it's intended for spawning.
	 *
	 * @param   array  $calendarArray
	 * @param   int    $rewindKeyY
	 * @param   int    $rewindKeyM
	 * @return  CalendarData
	 */
	public function buildFromArray(array $calendarArray, $rewindKeyY, $rewindKeyM)
	{
		$this->_array = $calendarArray;

		$this->_positionRewindY = $rewindKeyY;
		$this->_positionRewindM = $rewindKeyM;

		return $this;
	}

}
