<?php

namespace ApiModule;

/**
 * DaysPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class DaysPresenter extends BasePresenter {

	public function actionDefault() 
	{		
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
		
		$responseArr = $this->calendarDataToResponseArray();	
		
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse($responseArr));
	}
	
	public function actionCreate()
	{
		
	}
	
	public function actionUpdate()
	{	
//		sleep(60);
//		$this->response->setCode(\Nette\Http\Response::S400_BAD_REQUEST);
		$responseArr = $this->getJson();
		
		$this->sendResponse(new \Nette\Application\Responses\JsonResponse($responseArr));
	}
	
	public function actionDelete()
	{
		
	}
	
	protected function fromTo() 
	{
		$from = $this->request->getQuery('from');
		$this->from = new \Screwfix\CalendarDateTime($from);
		
		$to = $this->request->getQuery('to');
		$this->to = new \Screwfix\CalendarDateTime($to);
	}
}
