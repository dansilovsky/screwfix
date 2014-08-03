<?php

namespace Screwfix;

/**
 * AroundIteratorFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class AroundIteratorFactory {

	/**
	 * Creates instance of AroundIterator. 
	 * 
	 * @param array $array
	 * @return \Dan\Iterators\AroundIterator
	 */
	public function create(array $array) {
		return new \Dan\Iterators\AroundIterator($array);
	}
}
