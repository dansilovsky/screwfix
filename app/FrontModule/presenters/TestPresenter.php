<?php

namespace FrontModule;

/**
 * TestPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class TestPresenter extends BasePresenter {

	public function beforeRender()
	{
		parent::beforeRender();
		
		
		$res = preg_match('/^\d,\d,(null|(\d{2}:\d{2},\d{2}:\d{2}))$/', '0,0,08:00');
		
		echo "<br>------!!!------<br><pre>";
		var_dump($res);
		exit;
		echo "</pre><br>------!!!------<br>";
	}
}
