<?php

namespace Screfix;

/**
 * BaseaccountForm assigns common id.
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class BaseaccountForm extends \Nette\Application\UI\Form {
	
	private $_name = 'baseaccountForm';

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		
		$this->getElementPrototype()->id = 'frm-baseaccountForm';
	}
	
	public function getName()
	{
		return $this->_name;
	}
}
