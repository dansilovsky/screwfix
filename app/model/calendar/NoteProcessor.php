<?php

namespace Screwfix;

use \Nette\Utils\Html;

/**
 * NoteProcessor
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class NoteProcessor extends CalendarDataProcessor {

	protected $_name = 'holiday';
	
	protected $_type = 'work';
	
	public function process(array $day)
	{
		$data = $this->extractData($day);
		
		Html::el('ul');
		
	}
}
