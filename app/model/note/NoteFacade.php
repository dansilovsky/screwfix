<?php

namespace Screwfix;

/**
 * NoteFacade
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class NoteFacade extends RepositoryFacade {

	public function __construct(NoteRepository $repository, \Nette\Caching\Cache $cache, CalendarDateTime $date)
	{
		parent::__construct($repository, $cache, $date);
	}
	
	/**
	 * Builds array from \Nette\Database\Table\Selection adopted for use in NoteFilter
	 *
	 * @param    integer  $user_id
	 * @param    string   $from
	 * @param    string   $to     
	 * @return   array          empty array if no notes found between given dates
	 */
	public function notes($user_id, CalendarDateTime $from, CalendarDateTime $to)
	{
		$from = $from->format(Repository::FORMAT_DATE);
		$to = $to->format(Repository::FORMAT_DATE);
		
		$selection = $this->repository->between($user_id, $from, $to);

		$notes = array();

		$prevDate = null;

		foreach ($selection as $row)
		{
			// Nette database converts date type fiels into \Nette\DateTime object
			$date = (string) $row->date;
			$date = substr($date, 0, 10);

			if ($date === $prevDate)
			{
				$notes[$prevDate][] = $row->note;
			}
			else
			{
				$notes[$date][] = $row->note;
			}

			$prevDate = $date;
		}

		return $notes;
	}

	
}
