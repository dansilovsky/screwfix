<?php

namespace FrontModule;

/**
 * Home presenter.
 */
class HomePresenter extends CalendarPresenter {
	
	public function renderDefault()
	{		
		$this->setCalendarTodayData();
		$this->template->calendarToday = $this->calendarTodayData;
		
		if ($this->user->isLoggedIn())
		{
			$shiftPatternFilter = $this->getShiftPatternFilter();
			$bankHolidayFilter = $this->getBankHolidayFilter();
			$holidayFilter = $this->getHolidayFilter();
			$noteFilter = $this->getNoteFilter();
			$sysNoteFilter = $this->getSysNoteFilter();

			$calendarPeriod = new \Screwfix\CalendarDayPeriod($this->from, $this->to);

			$this->calendarData = new \Screwfix\CalendarData($calendarPeriod);
			$this->calendarData->addFilter($shiftPatternFilter)
				->addFilter($bankHolidayFilter)
				->addFilter($holidayFilter)
				->addFilter($noteFilter)
				->addFilter($sysNoteFilter)
				->build();
			$this->calendarData->rewind();
		}
		else
		{
			$sysShiftPatternFilter = $this->getSysShiftPatternFilter();
			$bankHolidayFilter = $this->getBankHolidayFilter();
			$sysNoteFilter = $this->getSysNoteFilter();

			$calendarPeriod = new \Screwfix\CalendarDayPeriod($this->from, $this->to);

			$this->calendarData = new \Screwfix\CalendarData($calendarPeriod);
			$this->calendarData->addFilter($sysShiftPatternFilter)
				->addFilter($bankHolidayFilter)
				->addFilter($sysNoteFilter)
				->build();
			$this->calendarData->rewind();
		}
		
		$this->template->calendarData = $this->calendarData;
	}

	protected function registerCookiesValidators()
	{
		$this->cookies->registerValidator('sys_shift_pattern_id', function($id) {
			return \Nette\Utils\Validators::isNumericInt($id);
		});
	}

	protected function fromTo()
	{
		$this->from = new \Screwfix\CalendarDateTime('now');
		$this->from->subMonth()
			->subMonth()
			->floor();

		$interval = new \Screwfix\CalendarInterval(5, \Screwfix\CalendarInterval::M);

		$this->to = clone $this->from;
		$this->to->add($interval)->subDay();
	}
}
