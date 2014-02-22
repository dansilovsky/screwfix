<?php
namespace FrontModule;
/**
 * HeaderControl
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class HeaderControl extends \Nette\Application\UI\Control {
    
	public function __construct()
	{
		parent::__construct();
	}
	
	public function render()
	{
		$this->template->setFile(__DIR__ . '/Header.latte');
	}
	
	
}
