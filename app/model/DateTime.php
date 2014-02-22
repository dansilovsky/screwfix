<?php

namespace Screwfix;

/**
 * DateTime
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class DateTime extends \Nette\DateTime {
	
	private $_oneDayInterval;
	
	private $_oneMonthInterval = null;
	
	public function __construct($time = 'now', $object = null)
	{
		parent::__construct($time, $object);
		
		$this->_oneDayInterval = new \DateInterval('P1D');
		$this->_oneMonthInterval = new \DateInterval('P1M');
	}


	/**
	 * Ads an interval on the clone of this instance and returns the clone.
	 * 
	 * @param   \DateInterval   $interval
	 * @return  \Screwfix\DateTime
	 */
	public function addClone(\DateInterval $interval)
	{
		$dolly = clone $this;
		return $dolly->add($interval);
	}
	
	/**
	 * Subtracts an interval from the clone of this instance and returns the clone.
	 * 
	 * @param   \DateInterval   $interval
	 * @return  \Screwfix\DateTime
	 */
	public function subClone(\DateInterval $interval)
	{
		$dolly = clone $this;
		return $dolly->sub($interval);
	}
	
	/**
	 * Adds one day.
	 * 
	 * @return \Screwfix\DateTime
	 */
	public function addDay()
	{
		$this->add($this->_oneDayInterval);
		
		return $this;
	}
	
	/**
	 * Subtracts one day.
	 * 
	 * @return \Screwfix\DateTime
	 */
	public function subDay()
	{
		$this->sub($this->_oneDayInterval);
		
		return $this;
	}
	
	/**
	 * Adds one month.
	 * 
	 * @return \Screwfix\DateTime
	 */
	public function addMonth() 
	{
		$this->add($this->_oneMonthInterval);
		
		return $this;
	}
	
	/**
	 * Subtracts one month.
	 * 
	 * @return \Screwfix\DateTime
	 */
	public function subMonth() 
	{
		$this->sub($this->_oneMonthInterval);
		
		return $this;
	}
	
	/**
	 * Returns a year
	 * 
	 * @return int
	 */
	public function getYear()
	{
		return (int) $this->format('Y');
	}	
	
	/**
	 * Returns a month
	 * 
	 * @return int
	 */
	public function getMonth()
	{
		return (int) $this->format('n');
	}
	
	/**
	 * Returns a day
	 * 
	 * @return int
	 */
	public function getDay()
	{
		return (int) $this->format('j');
	}
}
