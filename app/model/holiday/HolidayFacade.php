<?php

namespace Screwfix;

/**
 * HolidayFacade
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class HolidayFacade extends RepositoryFacade {

	public function __construct(HolidayRepository $repository, Cache $cache, CalendarDateTime $date)
	{
		parent::__construct($repository, $cache, $date);
	}

	/**
	 * Builds array from \Nette\Database\Table\Selection adopted for use in HolidayFilter
	 *
	 * @param    integer  $user_id
	 * @param    string   $from
	 * @param    string   $to     
	 * @return   array          empty array if no holidays found between given dates
	 */
	public function holidays($user_id, CalendarDateTime $from, CalendarDateTime $to)
	{
		$from = $from->format(Repository::FORMAT_DATE);
		$to = $to->format(Repository::FORMAT_DATE);
		
		$selection = $this->repository->between($user_id, $from, $to);

		$holidays = array();

		foreach ($selection as $row)
		{
			// Nette database converts date type fiels into \Nette\DateTime object
			$date = (string) $row->date;
			$date = substr($date, 0, 10);

			$holidays[$date] = (int) $row->halfday;;
		}
		
		return $holidays;
	}
	
	public function updateHolidays($user_id, array $holidays) 
	{
		$count = count($holidays);
		
		$from = $holidays[0]['id'];
		
		$to = $count > 1 ? $holidays[$count - 1]['id'] : null;
		
		if ($holidays[0]['holiday'] === null)
		{
			// delete
			if ($to !== null)
			{
				$this->repository->between($user_id, $from, $to)->delete();
			}
			else
			{
				$this->repository->getByDateUser($from, $user_id)->delete();
			}
		}
		else
		{
			//create
			foreach($holidays as $holiday)
			{
				$this->repository->save($holiday['id'], $holiday['holiday'], $user_id);
			}
		}
	}
}
