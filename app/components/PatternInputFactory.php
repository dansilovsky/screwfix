<?php

namespace Screwfix;

/**
 * PatternInputFactory
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class PatternInputFactory {

	/** 
	 * @var Template 
	 */
	protected $template;
	
	/** 
	 * @var string 
	 */
	protected $latteName;
	
	public function __construct(Template $template)
	{
		$this->template = $template;
	}
	
	public function create()
	{
		return new PatternInput($this->template, $this->latteName);
	}
}
