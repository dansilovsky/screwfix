<?php

namespace Screwfix;

class HolidayProcessorTest extends \PHPUnit_Framework_TestCase {

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

		$this->object = new HolidayProcessor;
	}
	
	/**
	 * @covers Screwfix\HolidayProcessor::name
	 */
	public function testName()
	{
		$this->assertSame( 'holiday', $this->object->name());
	}
	
	/**
	 * @covers Screwfix\HolidayProcessor::type
	 */
	public function testType()
	{
		$this->assertSame( 'work', $this->object->type());
	}

	/**
	 * @covers Screwfix\HolidayProcessor::process
	 */
	public function testProcessHoliday()
	{
		$dayArray = array(
			'day' => 2156400, 
			'data' => array(
				'holiday' => 0,
			)
		);
		
		$this->assertSame( 'Holiday', $this->object->process($dayArray));
	}

	/**
	 * @covers Screwfix\HolidayProcessor::process
	 */
	public function testProcessHalfdayHoliday()
	{
		$dayArray = array(
			'day' => 2156400, 
			'data' => array(
				'holiday' => 1,
			)
		);
		
		$this->assertSame( 'Halfday holiday', $this->object->process($dayArray));
	}

	/**
	 * @covers Screwfix\HolidayProcessor::process
	 * @expectedException Screwfix\CalendarProcessorException
	 */
	public function testProcessCalendarProcessorException()
	{
		$dayArray = array(
			'day' => 2156400, 
			'data' => array(
				'holiday' => '1',
			)
		);
		
		$this->object->process($dayArray);
	}

}

