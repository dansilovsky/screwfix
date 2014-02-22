<?php

namespace Screwfix;

class ShiftPatternProcessorTest extends \PHPUnit_Framework_TestCase {

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

		$this->object = new ShiftPatternProcessor;
	}
	
	/**
	 * @covers Screwfix\ShiftPatternProcessor::name
	 */
	public function testName()
	{
		$this->assertSame( 'shift', $this->object->name());
	}
	
	/**
	 * @covers Screwfix\ShiftPatternProcessor::type
	 */
	public function testType()
	{
		$this->assertSame( 'work', $this->object->type());
	}

	/**
	 * @covers Screwfix\ShiftPatternProcessor::process
	 */
	public function testProcess()
	{
		$dayArray = array(
			'day' => 2156400, 
			'data' => array(
				'shift' => array('07:00', '15:00'),
			)
		);
		
		$this->assertSame( '07:00 - 15:00', $this->object->process($dayArray));
	}

}

