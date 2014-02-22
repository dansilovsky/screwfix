<?php

namespace Screwfix;

/**
 * NoteFilter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class SysNoteFilter extends CalendarFilter {

	/**
	 * @var string
	 */
	protected $_name = 'sys_note';

	/**
	 * @var array
	 */
	private $_notes = array();

	/**
	 * Example of argument array:
	 * 
	 *	array (
	 *		'2012-02-14' => array (
	 *			0 => 'Note 1',
	 *		),
	 *		'2012-04-02' => array (
	 *			0 => 'Note 2',
	 *			1 => 'Note 3',
	 *		),
	 *	)
	 *
	 * @param  array  $notes
	 */
	public function __construct(array $notes)
	{
		$this->_notes = $notes;
	}

	public function day($date)
	{
		return (isset($this->_notes[$date])) ? $this->_notes[$date] : null;
	}
}
