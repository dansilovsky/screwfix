<?php

namespace Dan;

class CookiesTest extends \UnitTestCase {
	
	
	protected $object;
	
	protected $options = array(
		'time' => '+ 100 days',
		'path' => null,
		'domain' => null,
		'secure' => null,
		'httpOnly' => null,
	);


	protected $mockRequest;
	
	protected $mockResponse;
	
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$mockUrl = $this->getMock('\Nette\Http\UrlScript');
		
		$this->mockRequest = $this->getMock('\Nette\Http\Request', null, array($mockUrl));
		
		$this->mockResponse = $this->getMock('\Nette\Http\Response');
			
		$this->object = new Cookies($this->options, $this->mockRequest, $this->mockResponse);
		
	}
	
	public function testSetOptionsFromArray()
	{
		$this->object->setOptions(array('time' => '+ 365 days', 'secure' => true));
		
		$this->assertEquals(
			array(
			'time' => '+ 365 days',
			'path' => null,
			'domain' => null,
			'secure' => true,
			'httpOnly' => null,
			), 
			$this->object->getOptions()
		);
	}
	
	
}

?>
