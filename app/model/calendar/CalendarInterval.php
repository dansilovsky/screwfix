<?php

namespace Screwfix;

/**
 * CalendarInterval
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarInterval extends \DateInterval {
	
	const Y = 'Y';
	const M = 'M';
	const W = 'W';
	const D = 'D';
	
	private $_periodDesignator;
	
	public function __construct($recurrences = 1, $periodDesignator = self::Y)
	{
		$this->_periodDesignator = $periodDesignator;
		
		switch ($periodDesignator)
		{
			case self::Y:
				break;
			case self::M:
				break;
			case self::W:
				break;
			case self::D:
				break;
			default:
				throw new \InvalidArgumentException("Only 'Y', 'M', 'W' and 'D' periond designators allowed.");
				break;
		}
		
		parent::__construct('P' . $recurrences . $periodDesignator);
	}
	
	public function getPeriodDesignator()
	{
		return $this->_periodDesignator;
	}
}
