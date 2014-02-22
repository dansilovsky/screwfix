<?php

namespace Screwfix;

/**
 * PatterFacade
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class PatternFacade extends RepositoryFacade {

	public function __construct(PatternRepository $repository, \Nette\Caching\Cache $cache, CalendarDateTime $date)
	{
		parent::__construct($repository, $cache, $date);
	}

	/**
	 * Fetches a pattern row for given user. 
	 * Unserializes a pattern and returns it.
	 * 
	 * @param  int  $user_id
	 * @return ShiftPatternFilter|false
	 */
	public function getPatternFilter($user_id)
	{		
		$patternRow = $this->repository->findByUserId($user_id)->fetch();
		
		return ($patternRow === false) ? false : unserialize($patternRow->pattern);
	}
}
