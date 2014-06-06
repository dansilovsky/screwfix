<?php
namespace Screwfix;

/**
 * Template
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class Template extends \Nette\Templating\FileTemplate {

	public function __construct(\Latte\Engine $latte)
	{
		parent::__construct(null);
		
		$this->registerFilter($latte);
		$this->registerHelperLoader('Nette\\Templating\\Helpers::loader');
	}
}
