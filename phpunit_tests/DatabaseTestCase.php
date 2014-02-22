<?php

/**
 * PHPUnit_MyDatabaseConnection
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase {

	
	protected $netteConnection =  null;

	/**
	 * @var PHPUnit_Extensions_Database_DB_IDatabaseConnection
	 */
	protected $connection = null;
	
	protected function getNetteConnection()
	{
		if ($this->netteConnection === null)
		{
			$this->netteConnection = new \Nette\Database\Connection('mysql:host=localhost;dbname=screwfix_test', 'root', 'winona', NULL);;
		}
		
		return $this->netteConnection;
	}

	protected function getConnection()
	{
		if ($this->connection === null)
		{
			$this->connection = $this->createDefaultDBConnection(
				new PDO('mysql:host=localhost;dbname=screwfix_test', 'root', 'winona')
			);
		}

		return $this->connection;
	}
	
	protected function getDataSet()
	{		
		return $this->createFlatXMLDataSet(__DIR__ . "/baseDataSet.xml");
	}

}
