<?php

/**
 * MockedIteratorTest inspired by http://pastebin.com/FVxNf6zq
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class PHPUnit_MockedIteratorTest extends \PHPUnit_Framework_TestCase {	
	
	
	protected function getMockIterator($iteratorClassName, array $items, $includeCallsToKey = FALSE)
	{
		$iteratory = $this->getMock($iteratorClassName);
		
		$iterator->expects($this->at(0))
			->method('rewind');

		$counter = 1;

		foreach ($items as $k => $v)
		{

			$iterator->expects($this->at($counter++))
				->method('valid')
				->will($this->returnValue(TRUE));

			$iterator->expects($this->at($counter++))
				->method('current')
				->will($this->returnValue($v));

			if ($includeCallsToKey)
			{

				$iterator->expects($this->at($counter++))
					->method('key')
					->will($this->returnValue($k));
			}

			$iterator->expects($this->at($counter++))
				->method('next');
		}

		$iterator->expects($this->at($counter))
			->method('valid')
			->will($this->returnValue(FALSE));
	}

}
