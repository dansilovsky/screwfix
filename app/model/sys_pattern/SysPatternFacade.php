<?php

namespace Screwfix;

/**
 * SysPatternFacade
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class SysPatternFacade extends RepositoryFacade {

	public function __construct(SysPatternRepository $repository, \Nette\Caching\Cache $cache, CalendarDateTime $date)
	{
		parent::__construct($repository, $cache, $date);
	}

	public function getPatterns()
	{
		return $this->repository;
	}

	/**
	 * @param  int  $id
	 * @return SysShiftPatternFilter|false
	 */
	public function getPatternFilter($id = null)
	{
		if ($id)
		{
			$patternRow = $this->repository->get($id);
		}
		else
		{
			$patternRow = $this->repository->first()->fetch();
		}
		
		return ($patternRow === false) ? false : unserialize($patternRow->pattern);
	}
	
	/**
	 * Inserts now row into repository into repository. 
	 * Argument $pattern (instance of ShiftPatternFilter) is serialized before inserting.
	 * 
	 * @param  string              $name      name of shift pattern
	 * @param  ShiftPatternFilter  $pattern   instance of ShiftPatternFilter
	 */
	public function insert($name, ShiftPatternFilter $pattern)
	{
		$pattern = serialize($pattern);
		
		$this->repository->insert(array('name' => $name, 'pattern' => $pattern));
	}
	
	/**
	 * Gets associative array where "key = name of pattern" and "value = shift pattern array"
	 * 
	 * @return array
	 */
	public function getFormSelection()
	{
		$selection = array();
		
		foreach ($this->repository as $patternRow)
		{
			$patternFilter = unserialize($patternRow->pattern);
			$selection[$patternFilter->toJson()] = $patternRow->name;
		}
		
		return $selection;
	}

}
