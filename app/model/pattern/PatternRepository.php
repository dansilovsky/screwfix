<?php

namespace Screwfix;

/**
 * PatternRepository
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class PatternRepository extends Repository {
	
	private $_name = 'pattern';
	
	public function __construct(\Nette\Database\Context $context)
	{
		parent::__construct($this->_name, $context);
	}
	
	public function findByUserId($user_id)
	{
		return $this->where('user_id', $user_id);
	}
}
