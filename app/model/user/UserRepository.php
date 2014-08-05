<?php

namespace Screwfix;

/**
 * UserRepository
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/licence
 */
class UserRepository extends \Screwfix\Repository {
	
	private $_name = 'user';
	
	public function __construct(\Nette\Database\Context $context)
	{
		parent::__construct($this->_name, $context);
	}
	
	public function findById($userId)
	{
		return $this->where('id', $userId);
	}
        
        /**
         * Get user by username
         * 
         * @param    string   $username
         * @return Nette\Database\Table\ActiveRow
         */
        public function findByUsername($username)
        {
		return $this->where('username', $username);
        }
	
	public function findByEmail($email) 
	{
		return $this->where('email', $email);
	}
}
