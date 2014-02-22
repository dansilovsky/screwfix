<?php

namespace Screwfix;

/**
 * UserFacade
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class UserFacade extends RepositoryFacade {

	public function __construct(UserRepository $repository, \Nette\Caching\Cache $cache, CalendarDateTime $date)
	{		
		parent::__construct($repository, $cache, $date);
	}
	
	/**
         * Get user by username
         * 
         * @param   string   $username
         * @return  Nette\Database\Table\ActiveRow
         */
	public function getByUsername($username)
	{
		return $this->repository->findByUsername($username)->fetch();
	}
}
