<?php

namespace Screwfix;

/**
 * CalendarDay
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDay extends CalendarDateTime {

	const KEY_SHIFT = 'shift';
	const KEY_HOLIDAY = 'holiday';
	const KEY_BANK_HOLIDAY = 'bank_holiday';
	const KEY_NOTE = 'note';
	const KEY_SYS_NOTE = 'sys_note';

	/**
	 * @var array
	 */
	private $_data;	
	
	private $_year;
	
	/**
	 * $_month does not have to correspond with actual date of this day.
	 * It's a month to which actually this day belongs to.
	 * 
	 * @var int 
	 */
	private $_displayMonth;
	
	/**
	 * Constructor
	 * 
	 * @param string $date
	 * @param array $data
	 * @param int $year
	 * @param int $month  display month, it can differ from a month in date
	 */
	public function __construct($date, $data, $year, $month)
	{
		parent::__construct($date);

		$this->_data = $data;
		$this->_year = $year;
		$this->_displayMonth = $month;
	}
	
	/**
	 * Returns id of a day which is a date plus display month.
	 * 
	 * @return type
	 */
	public function id()
	{
		return $this->format('Y-m-d');
	}

	/**
	 * Returns day of the month
	 *
	 * @return int
	 */
	public function day()
	{
		return (int) $this->format('j');
	}
	
	/**
	 * Returns display month
	 * 
	 * @return int
	 */
	public function displayMonth()
	{
		return $this->_displayMonth;
	}
	
	public function year()
	{
		return $this->_year;
	}
	
	/**
	 * Detects weather the day was used as padding of month.
	 * 
	 * @return bool
	 */
	public function isPadding()
	{
		return ((int) $this->format('n') !== $this->_displayMonth);
	}

	/**
	 * Is shift set
	 *
	 * @return bool
	 */
	public function isShift()
	{
		return isset($this->_data[self::KEY_SHIFT]);
	}

	/**
	 * Returns start of shift if is set.
	 *
	 * @return string|null
	 */
	public function shiftStart()
	{
		if ($this->isShift())
		{
			return $this->_data[self::KEY_SHIFT][0];
		}

		return null;
	}

	/**
	 * Returns end of shift if set.
	 *
	 * @return string|null
	 */
	public function shiftEnd()
	{
		if ($this->isShift())
		{
			return $this->_data[self::KEY_SHIFT][1];
		}

		return null;
	}

	/**
	 * Is holiday set
	 *
	 * @return bool
	 */
	public function isHoliday()
	{
		return isset($this->_data[self::KEY_HOLIDAY]);
	}

	/**
	 * Returns holiday if set
	 *
	 * @return integer
	 */
	public function holiday()
	{
		if ($this->isHoliday())
		{
			return $this->_data[self::KEY_HOLIDAY];
		}

		return null;
	}

	/**
	 * Is bank holiday set
	 * 
	 * @return bool
	 */
	public function isBankHoliday()
	{
		return isset($this->_data[self::KEY_BANK_HOLIDAY]);
	}
	
	/**
	 * Returns bank holiday if set
	 * 
	 * @return string|null
	 */
	public function bankHoliday()
	{
		if ($this->isBankHoliday())
		{
			return $this->_data[self::KEY_BANK_HOLIDAY];
		}

		return null;
	}
	
	/**
	 * Is note set
	 * 
	 * @return bool
	 */
	public function isNote()
	{
		return isset($this->_data[self::KEY_NOTE]);
	}
	
	/**
	 * Returns array of notes or null if not set
	 * 
	 * @return array|null
	 */
	public function note()
	{
		if ($this->isNote())
		{
			return $this->_data[self::KEY_NOTE];
		}

		return null;
	}
	
	/**
	 * Is sys note set
	 * 
	 * @return bool
	 */
	public function isSysNote()
	{
		return isset($this->_data[self::KEY_SYS_NOTE]);
	}
	
	/**
	 * Return array of sys notes or null if not set
	 * 
	 * @return array|null
	 */
	public function sysNote()
	{
		if ($this->isSysNote())
		{
			return $this->_data[self::KEY_SYS_NOTE];
		}

		return null;
	}
	
	/**
	 * Returns JSON representation of this day.
	 * 
	 * @return string
	 */
	public function toJSON()
	{
		$day = array(
			'id' => $this->id(),
			'day' => (int) $this->day(),
			'note' => $this->note(),
			'sysNote' => $this->sysNote(),
			'holiday' => $this->holiday(),
			'bankHoliday' => $this->bankHoliday(),
			'shiftStart' => $this->shiftStart(),
			'shiftEnd' => $this->shiftEnd(),
			'year' => $this->year(),
			'displayMonth' => $this->displayMonth(),
			'isFirstDayOfWeek' => $this->isFirstDayOfWeek(),
			'isLastDayOfWeek' => $this->isLastDayOfWeek(),
		);
		
		return json_encode($day);
	}
}
