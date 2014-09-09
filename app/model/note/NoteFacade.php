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

	public function __construct(NoteRepository $repository, Cache $cache, CalendarDateTime $date)
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
	public function getNotesBetween($user_id, CalendarDateTime $from, CalendarDateTime $to)
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
				$notes[$prevDate][] = array('id' => $row->id, 'note' => $row->note);
			}
			else
			{
				$notes[$date][] = array('id' => $row->id, 'note' => $row->note);
			}

			$prevDate = $date;
		}

		return $notes;
	}
	
	/**
	 * Get notes for given user and date. 
	 * Returned array is adopted for the use by clients javascript.
	 * 
	 * @param integer  $user_id
	 * @param string   $date
	 * @return array eg. array('note' => array(
	 *                      array('id' => 1, 'note' => 'text 1'),
	 *                      array('id' => 2, 'note' => 'text 2'),
	 *                   ))
	 */
	public function getAjaxNote($user_id, $date)
	{
		$note = array();
		
		$selection = $this->repository->getByUserDate($user_id, $date);
		
		foreach($selection as $row)
		{
			$note['note'][] = array('id' => $row->id, 'note' => $row->note);
		}
		
		if (empty($note)) {
			$note = array('note' => null);
		}
		
		return $note;
	}
	
	public function updateNotes($user_id, $date, $note)
	{
		if ($note['id'] === null)
		{
			// create
			$this->repository->save($user_id, $date, $note['note']);
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
