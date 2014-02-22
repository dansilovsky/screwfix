<?php

namespace Screwfix;

/**
 * CalendarIterator
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class CalendarIterator extends \Nette\Object implements ICalendarIterator {

	/**
	 * Types of iteration mode
	 */
	const ITERATION_MODE_DAY = 'day';
	const ITERATION_MODE_MONTH = 'month';
	const ITERATION_MODE_YEAR = 'year';

	/**
	 * Holds days in timestamp.
	 * @var array
	 */
	protected $_array = array();

	/**
	 * Type of iteration mode.
	 * Default is iteration through days
	 *
	 * @var string
	 */
	protected $_mode = 'day';

	/**
	 * @var int
	 */
	protected $_positionY;

	protected $_positionM;

	protected $_positionD;

	public function getArray()
	{
		return $this->_array;
	}

	/**
	 * Sets or gets mode of iteration
	 *
	 * @param   string   $mode   either 'day' or 'month' or 'year'
	 * @return  $this|string
	 * @throws  \InvalidArgumentException
	 */
	public function mode($mode = null)
	{
		if ($mode === null)
		{
			return $this->_mode;
		}

		if ($mode === self::ITERATION_MODE_DAY || $mode === self::ITERATION_MODE_MONTH || $mode === self::ITERATION_MODE_YEAR)
		{
			$this->_mode = $mode;
		}
		else
		{
			throw new \InvalidArgumentException();
		}

		$this->rewind();

		return $this;
	}
	
	public function current()
	{
		switch ($this->_mode)
		{
			case self::ITERATION_MODE_DAY:
				return $this->_currentD();
				break;

			case self::ITERATION_MODE_MONTH:
				return $this->_currentM();
				break;

			case self::ITERATION_MODE_YEAR:
				return $this->_currentY();
				break;

			default:
				throw new CalendarIterator_InvalidIterationMode_Exception;
				break;
		}
	}

	public function key()
	{
		switch ($this->_mode)
		{
			case self::ITERATION_MODE_DAY:
				return $this->_keyD();
				break;

			case self::ITERATION_MODE_MONTH:
				return $this->_keyM();
				break;

			case self::ITERATION_MODE_YEAR:
				return $this->_keyY();
				break;

			default:
				throw new CalendarIterator_InvalidIterationMode_Exception;
				break;
		}
	}

	public function next()
	{
		switch ($this->_mode)
		{
			case self::ITERATION_MODE_DAY:
				$this->_nextD();
				break;

			case self::ITERATION_MODE_MONTH:
				$this->_nextM();
				break;

			case self::ITERATION_MODE_YEAR:
				$this->_nextY();
				break;

			default:
				throw new CalendarIterator_InvalidIterationMode_Exception;
				break;
		}
	}

	public function valid()
	{
		switch ($this->_mode)
		{
			case self::ITERATION_MODE_DAY:
				return $this->_validD();
				break;

			case self::ITERATION_MODE_MONTH:
				return $this->_validM();
				break;

			case self::ITERATION_MODE_YEAR:
				return $this->_validY();
				break;

			default:
				throw new CalendarIterator_InvalidIterationMode_Exception;
				break;
		}
	}

	// day iteration functions
	protected function _currentD()
	{
		return $this->_array[$this->_positionY][$this->_positionM][$this->_positionD];
	}

	protected function _keyD()
	{
		return $this->_positionY.'.'.$this->_positionM.'.'.$this->_positionD;
	}

	protected function _nextD()
	{
		++$this->_positionD;

		if ($this->_positionD > 27 && !$this->valid())
		{
			$this->_positionD = 0;

			if ($this->_positionM < 12)
			{
				++$this->_positionM;
			}
			else
			{
				$this->_positionM = 1;

				++$this->_positionY;
			}
		}
	}

	protected function _validD()
	{
		return isset($this->_array[$this->_positionY][$this->_positionM][$this->_positionD]);
	}

	// month iteration functions
	protected function _currentM()
	{
		return $this->_array[$this->_positionY][$this->_positionM];
	}

	protected function _keyM()
	{
		return $this->_positionY.'.'.$this->_positionM;
	}

	protected function _nextM()
	{
		if ($this->_positionM < 12)
		{
			++$this->_positionM;
		}
		else
		{
			$this->_positionM = 1;

			++$this->_positionY;
		}
	}

	protected function _validM()
	{
		return isset($this->_array[$this->_positionY][$this->_positionM]);
	}

	// year iteration functions
	protected function _currentY()
	{
		return $this->_array[$this->_positionY];
	}

	protected function _keyY()
	{
		return (string) $this->_positionY;
	}

	protected function _validY()
	{
		return isset($this->_array[$this->_positionY]);
	}

	protected function _nextY()
	{
		++$this->_positionY;
	}
	
	/**
	 * @return integer
	 */
	public function keyY()
	{
		return $this->_positionY;
	}

	/**
	 * @return int
	 */
	public function keyM()
	{
		return $this->_positionM;
	}

	/**
	 * @return int
	 */
	public function keyD()
	{
		return $this->_positionD;
	}
}
