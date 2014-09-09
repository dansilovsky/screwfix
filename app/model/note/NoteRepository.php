<?php

namespace Screwfix;

/**
 * NoteRepository
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class NoteRepository extends Repository {

	private $_name = 'note';
	
	public function __construct(\Nette\Database\Context $context)
	{
		parent::__construct($this->_name, $context);
	}

	/**
	 * Fetch array of notes between given dates
	 *
	 * @param    integer  $user_id   user id
	 * @param    string   $from      date format yyyy-mm-dd
	 * @param    string   $to        date format yyyy-mm-dd
	 * @return   \Nette\Database\Table\Selection
	 */
	public function between($user_id, $from, $to)
	{
		return $this->where('user_id', $user_id)
			->where('date >= ?', $from)
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
	public function getByUserDate($user_id, $date)
	{
		return $this->where('user_id', $user_id)
			->where('date', $date);
	}
	
	/**
	 * Inserts one new note.
	 * 
	 * @param string $note note value
	 * @return \Nette\Database\IRow  inserted row
	 */
	public function save($user_id, $date, $note)
	{
		$data = array(
			'user_id' => $user_id, 
			'date' => $date, 
			'note' => $note
		);
		
		return $this->insert($data);
	}
}
