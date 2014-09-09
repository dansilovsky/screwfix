<?php

namespace Screwfix;

/**
 * SysNoteFacade
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class SysNoteFacade extends RepositoryFacade {

	public function __construct(SysNoteRepository $repository, Cache $cache, CalendarDateTime $date)
	{
		parent::__construct($repository, $cache, $date);
	}

	/**
	 * Builds array from \Nette\Database\Table\Selection adopted for use in SysNoteFilter
	 *
	 * @param    string   $from
	 * @param    string   $to     
	 * @return   array          empty array if no notes found between given dates
	 */
	public function getNotesBetween(CalendarDateTime $from, CalendarDateTime $to)
	{
		$from = $from->format(Repository::FORMAT_DATE);
		$to = $to->format(Repository::FORMAT_DATE);
		
		$selection = $this->repository->between($from, $to);

		$notes = array();

		$prevDate = null;

		foreach ($selection as $row)
		{
			// Nette database converts date type fiels into \Nette\DateTime object
			$date = (string) $row->date;
			$date = substr($date, 0, 10);

			if ($date === $prevDate)
			{
				$notes[$prevDate][] = array('id' => $row->id, 'note' => $row->note);;
			}
			else
			{
				$notes[$date][] = array('id' => $row->id, 'note' => $row->note);;
			}

			$prevDate = $date;
		}

		return $notes;
	}
	
	/**
	 * Get sys notes for given date. 
	 * Returned array is adopted for the use by clients javascript.
	 * 
	 * @param string   $date
	 * @return array eg. array('sysNote' => array(
	 *                      array('id' => 1, 'note' => 'text 1'),
	 *                      array('id' => 2, 'note' => 'text 2'),
	 *                   ))
	 */
	public function getAjaxNote($date)
	{
		$note = array();
		
		$selection = $this->repository->getByDate($date);
		
		foreach($selection as $row)
		{
			$note['sysNote'][] = array('id' => $row->id, 'note' => $row->note);
		}
		
		if (empty($note)) {
			$note = array('sysNote' => null);
		}
		
		return $note;
	}
	
	public function updateNotes($date, $note)
	{
		if ($note['id'] === null)
		{
			// create
			$this->repository->save($date, $note['note']);
		}
		else if ($note['note'] === null)
		{
			// delete
			$this->repository->get($note['id'])
				->delete();
		}
		else if (isset($note['id'], $note['note'])) 
		{
			// update
			$this->repository->get($note['id'])
				->update(array('note' => $note['note']));
		}
	}
}
