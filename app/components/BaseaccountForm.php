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

	public function __construct(\Nette\ComponentModel\IContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		
		$this->getElementPrototype()->id = 'frm-baseaccountForm';
	}
}
