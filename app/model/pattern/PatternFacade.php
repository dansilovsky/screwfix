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

	public function __construct(PatternRepository $repository, Cache $cache, CalendarDateTime $date)
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
	
	public function save($userId, ShiftPatternFilter $pattern)
	{
		$data = array(
			'user_id' => $userId, 
			'pattern' => serialize($pattern)
		);
		
		$this->repository->insert($data);
	}
	
	public function update($userId, ShiftPatternFilter $pattern)
	{
		$data = array(
			'pattern' => serialize($pattern)
		);
		
		$this->repository->findByUserId($userId)->update($data);
	}
	
	public function getFormSelection($user_id)
	{		
		$patternJson = $this->getPatternFilter($user_id)
			->toJson();		
		
		return array(
			$patternJson => 'My pattern'
		);
		
	}
}
