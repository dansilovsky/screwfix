<?php

namespace Screwfix;

/**
 * CalendarPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class CalendarPresenter extends BasePresenter {
	
	/**
	 * @var CalendarData 
	 */
	protected $calendarData;
	
	/**
	 * @var CalendarDateTime 
	 */
	protected $from;
	
	/**
	 * @var CalendarDateTime 
	 */
	protected $to;

	protected function startup()
	{
		parent::startup();
		
		$this->fromTo();
	}
	
	protected function createComponentCalendar()
	{
		return new CalendarControl($this->calendarData);
	}
	
	/**
	 * Sets properties CalendarPresenter::from and CalendarPresenter::to
	 */
	abstract protected function fromTo();
	
	protected function getShiftPatternFilter()
	{
		return $this->patternFacade->getPatternFilter($this->identity->id);
	}
	
	protected function calendarDataToResponseArray() 
	{
		$responseArr = array();
		
		foreach ($this->calendarData as $day)
		{
			$dayData = array(
				'id' => $day->id(),
				'day' => (int) $day->day(),
				'note' => $day->note(),
				'sysNote' => $day->sysNote(),
				'holiday' => $day->holiday(),
				'bankHoliday' => $day->bankHoliday(),
				'shiftStart' => $day->shiftStart(),
				'shiftEnd' => $day->shiftEnd(),
				'year' => $day->year(),
				'isFirstDayOfWeek' => $day->isFirstDayOfWeek(),
				'isLastDayOfWeek' => $day->isLastDayOfWeek(),
			);

			$responseArr[] = $dayData;
		}
		
		return $responseArr;
	}

	protected function getSysShiftPatternFilter()
	{
		$patternId = $this->cookies['sys_shift_pattern_id'];

		// reset cookie
		$this->cookies->set('sys_shift_pattern_id', $patternId);

		$shiftPatternFilter = $this->sysPatternFacade->getPatternFilter($patternId);

		if ($shiftPatternFilter === false)
		{
			throw new \Screwfix\BadDataSentException('Sys Shift Pattern for given pattern id does not exist. Bad data sent via cookie.');
		}

		return $shiftPatternFilter;
	}
	
	protected function getHolidayFilter()
	{
		return new \Screwfix\HolidayFilter($this->holidayFacade->holidays($this->identity->id, $this->from, $this->to));
	}
	
	protected function getBankHolidayFilter()
	{
		return new \Screwfix\BankHolidayFilter($this->bankHolidayFacade->holidays($this->from, $this->to));
	}
	
	protected function getNoteFilter()
	{
		return new \Screwfix\NoteFilter($this->noteFacade->notes($this->identity->id, $this->from, $this->to));
	}
	
	protected function getSysNoteFilter()
	{
		return new \Screwfix\SysNoteFilter($this->sysNoteFacade->notes($this->from, $this->to));
	}

}
