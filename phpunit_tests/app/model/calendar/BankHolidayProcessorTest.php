<?php

namespace Screwfix;

class BankHolidayProcessorTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var SysNoteFilter
	 */
	protected $object;	

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{

		$this->object = new BankHolidayProcessor;
	}
	
	/**
	 * @covers Screwfix\BankHolidayProcessor::name
	 */
	public function testName()
	{
		$this->assertSame( 'bank_holiday', $this->object->name());
	}
	
	/**
	 * @covers Screwfix\BankHolidayProcessor::type
	 */
	public function testType()
	{
		$this->assertSame( 'work', $this->object->type());
	}

	/**
	 * @covers Screwfix\BankHolidayProcessor::process
	 */
	public function testProcess()
	{
		$dayArray = array(
			'day' => 2156400, 
			'data' => array(
				'bank_holiday' => 'Bank Holiday',
			)
		);
		
		$this->assertSame( 'Bank Holiday', $this->object->process($dayArray));
	}

}

