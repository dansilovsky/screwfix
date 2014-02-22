<?php

namespace Screwfix;

use \Nette\Security;


/*
CREATE TABLE users (
	id int(11) NOT NULL AUTO_INCREMENT,
	username varchar(50) NOT NULL,
	password char(60) NOT NULL,
	role varchar(20) NOT NULL,
	PRIMARY KEY (id)
);
*/

/**
 * Users authenticator.
 */
class Authenticator extends \Nette\Object implements Security\IAuthenticator
{
	/** @var Nette\Database\Connection */
	private $_userFacade;



	public function __construct(UserFacade $facade)
	{
		$this->_userFacade = $facade;
	}



	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
        {
            list($username, $password) = $credentials;
            $row = $this->_userFacade->getByUsername($username);

            if (!$row) {
                throw new \Nette\Security\AuthenticationException("User '$username' not found.", self::IDENTITY_NOT_FOUND);
            }

            if ($row->password !== self::calculateHash($password, $row->password)) {
                throw new \Nette\Security\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
            }
	    
            return new \Nette\Security\Identity($row->id, $row->role, $row->toArray());
        }



	/**
	 * Computes salted password hash.
	 * @param  string
	 * @return string
	 */
	public static function calculateHash($password, $salt = null)
        {
            if ($salt === null) {
                $salt = '$2a$07$' . \Nette\Utils\Strings::random(22);
            }
            return crypt($password, $salt);
        }

}
