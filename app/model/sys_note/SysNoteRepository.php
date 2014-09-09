<?php

namespace Screwfix;

/**
 * SysNotesRepository
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class SysNoteRepository extends Repository {

	private $_name = 'sys_note';
	
	public function __construct(\Nette\Database\Context $context)
	{
		parent::__construct($this->_name, $context);
	}

	/**
	 * Fetch array of notes between given dates
	 *
	 * @param    type   $from   date format yyyy-mm-dd
	 * @param    type   $to     date format yyyy-mm-dd
	 * @return   \Nette\Database\Table\Selection
	 */
	public function between($from, $to)
	{
		return $this->where('date >= ?', $from)
			->where('date <= ?', $to)
			->order('date');
	}
	
	/**
	 * Get selection by user id and date.
	 * 
	 * @param integer $user_id
	 * @param string  $date
	 * @return \Nette\Database\Table\Selection
	 */
	public function getByDate($date)
	{
		return $this->where('date', $date);
	}
	
	/**
	 * Inserts one new sys note.
	 * 
	 * @param string $note sys note value
	 * @return \Nette\Database\IRow  inserted row
	 */
	public function save($date, $note)
	{
		$data = array( 
			'date' => $date, 
			'note' => $note
		);
		
		$row = $this->insert($data);
		
		return $row;
	}

	
}
