<?php

namespace Dan\Iterators;

/**
 * AroundArray iterates around array. If starting position is set to non zero 
 * then it iterates from given position towards the top position 
 * then it returns to zero position and iterates towards starting position.
 * 
 * e.g array(a, b, c, d) with start position set to 2 would print out c, d, a, b 
 * 
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class AroundIterator implements \Iterator {
	
	protected $array;
	
	protected $position = 0;
	
	protected $start = 0;
	
	protected $top;
	
	protected $movesCount = 0;
	
	/**
	 * @param array $array  accepts only non-associative array with index starting at 0
	 */
	public function __construct(array $array)
	{
		$this->array = $array;
		
		$this->top = count($this->array) - 1;
	}
	
	function rewind()
	{
		$this->position = $this->start;
		
		$this->movesCount = 0;
	}
	
	function current($position = null)
	{		
		return $this->array[$this->position];
	}
	
	function key()
	{
		return $this->position;
	}

	function next()
	{
		++$this->position;
		
		++$this->movesCount;
		
		if ($this->position > $this->top)
		{
			$this->position = 0;
		}
	}

	function valid()
	{
		return isset($this->array[$this->position]) && $this->top >= $this->movesCount;
	}
	
	/**
	 * Sets start position
	 * 
	 * @param int $position
	 * @throws \Dan\AroundIterator_OutOfScope_Exception
	 */
	function setStart($position)
	{		
		$this->start = $this->position = $position;

		if (!isset($this->array[$this->position]))
		{
			throw new \Dan\AroundIterator_OutOfScope_Exception('Given start position is out of scope.');
		}		
	}
}
