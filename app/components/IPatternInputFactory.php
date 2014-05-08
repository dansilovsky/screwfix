<?php
namespace Screwfix;

/**
 * IPatternInputInterface
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
interface IPatternInputFactory {
	/**
	 * @return \Screwfix\PatternInput
	 */
	public function create();
}
