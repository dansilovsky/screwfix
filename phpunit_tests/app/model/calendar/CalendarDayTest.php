<?php

namespace Screwfix;

/**
 * CalendarDayTest
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class CalendarDayTest extends \UnitTestCase {

	/**
	 * @var CalendarDay
	 */
	protected $object;
	
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'shift' => array('07:00', '15:00'),
			'sys_note' => array('Sysnote1', 'Sysnote2'),
		);
		
		$this->object = new CalendarDay($date, $data, 1970, 1);
	}
	
	/**
	 * @covers Screwfix\CalendarDay::day
	 */
	public function testDay()
	{
		$this->assertEquals('26', $this->object->day());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isPadding
	 */
	public function testIsPaddingPositive()
	{
		$date = '1970-02-01';
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(true, $object->isPadding());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isPadding
	 */
	public function testIsPaddingNegative()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(false, $object->isPadding());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isShift
	 */
	public function testIsShiftPositive()
	{
		$this->assertEquals(true, $this->object->isShift());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isShift
	 */
	public function testIsShiftNegative()
	{		
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'sys_note' => array('Sysnote1', 'Sysnote2')
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(false, $object->isShift());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::shiftStart
	 */
	public function testShiftStart()
	{
		$this->assertEquals('07:00', $this->object->shiftStart());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::shiftStart
	 */
	public function testShiftStartShiftIsNotSet()
	{		
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'sys_note' => array('Sysnote1', 'Sysnote2')
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(null, $object->shiftStart());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::shiftEnd
	 */
	public function testShiftEnd()
	{
		$this->assertEquals('15:00', $this->object->shiftEnd());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::shiftEnd
	 */
	public function testShiftEndShiftIsNotSet()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'sys_note' => array('Sysnote1', 'Sysnote2')
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);		
		
		$this->assertEquals(null, $object->shiftEnd());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isHoliday
	 */
	public function testIsHolidayPositive()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'holiday' => 0,
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(true, $object->isHoliday());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isHoliday
	 */
	public function testIsHolidayNegative()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(false, $object->isHoliday());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::holiday
	 */
	public function testHolidayHoliday()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'holiday' => 0,
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals('Holiday', $object->holiday());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::holiday
	 */
	public function testHolidayHalfdayHoliday()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'holiday' => 1,
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals('Halfday holiday', $object->holiday());
	}
	
	public function testHolidayIsNotSet()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(null, $object->holiday());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isBankHoliday
	 */
	public function testIsBankHolidayPositive()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'bank_holiday' => 'Bank holiday',
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(true, $object->isBankHoliday());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isBankHoliday
	 */
	public function testIsBankHolidayNegative()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(false, $object->isBankHoliday());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::bankHoliday
	 */
	public function testBankHoliday()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'bank_holiday' => 'Bank holiday',
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals('Bank holiday', $object->bankHoliday());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::bankHoliday
	 */
	public function testBankHolidayIsNotSet()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(null, $object->bankHoliday());
	}

	/**
	 * @covers Screwfix\CalendarDay::isNote
	 */
	public function testIsNotePositive()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'shift' => array('07:00', '15:00'),
			'note' => array('Note1', 'Note2'),
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(true, $object->isNote());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isNote
	 */
	public function testIsNoteNegative()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(false, $object->isNote());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::note
	 */
	public function testNote()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'shift' => array('07:00', '15:00'),
			'note' => array('Note1', 'Note2'),
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(array('Note1', 'Note2'), $object->note());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::note
	 */
	public function testNoteIsNotSet()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(null, $object->note());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isSysNote
	 */
	public function testIsSysNotePositive()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'shift' => array('07:00', '15:00'),
			'sys_note' => array('Sysnote1', 'Sysnote2'),
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(true, $object->isSysNote());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::isSysNote
	 */
	public function testIsSysNoteNegative()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(false, $object->isSysNote());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::sysNote
	 */
	public function testSysNote()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array(
			'shift' => array('07:00', '15:00'),
			'sys_note' => array('Sysnote1', 'Sysnote2'),
		);
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(array('Sysnote1', 'Sysnote2'), $object->sysNote());
	}
	
	/**
	 * @covers Screwfix\CalendarDay::sysNote
	 */
	public function testSysNoteIsNoteSet()
	{
		$date = '1970-01-26'; //Mon 1970-01-26 00:00:00
		$data = array();
		
		$object = new CalendarDay($date, $data, 1970, 1);
		
		$this->assertEquals(null, $object->sysNote());
	}
	
}
