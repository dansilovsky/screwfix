<?php

namespace Screwfix;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-07-14 at 22:53:53.
 */
class CalendarDateTimeTest extends \UnitTestCase {

	/**
	 * @var CalendarDateTime
	 */
	protected $object;
	
	protected $helpDate;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new CalendarDateTime;
		
		$this->helpDate = new \DateTime();
	}

	/**
	 * @covers Screwfix\CalendarDateTime::floor
	 */
	public function testFloorWeek()
	{
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 7, 8);
		
		$this->assertSame($this->helpDate->format('Ymd'), $this->object->floor(CalendarDateTime::W)->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::floor
	 */
	public function testFloorMonth()
	{
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 7, 1);
		
		$this->assertSame($this->helpDate->format('Ymd'), $this->object->floor(CalendarDateTime::M)->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::floor
	 */
	public function testFloorYear()
	{
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 1, 1);
		
		$this->assertSame($this->helpDate->format('Ymd'), $this->object->floor(CalendarDateTime::Y)->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::floorClone
	 */
	public function testFloorClone()
	{		
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 7, 1);
		
		$dolly = $this->object->floorClone();
		
		$this->object->setDate(1000, 1, 1);
		
		$this->assertSame($this->helpDate->format('Ymd'), $dolly->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::ceil
	 */
	public function testCeilWeek()
	{
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 7, 14);
		
		$this->assertSame($this->helpDate->format('Ymd'), $this->object->ceil(CalendarDateTime::W)->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::ceil
	 */
	public function testCeilMonth()
	{
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 7, 31);
		
		$this->assertSame($this->helpDate->format('Ymd'), $this->object->ceil(CalendarDateTime::M)->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::ceil
	 */
	public function testCeilYear()
	{
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 12, 31);
		
		$this->assertSame($this->helpDate->format('Ymd'), $this->object->ceil(CalendarDateTime::Y)->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::ceilClone
	 */
	public function testCeilClone()
	{		
		$this->object->setDate(2013, 7, 13);
		
		$this->helpDate->setDate(2013, 7, 31);
		
		$dolly = $this->object->ceilClone();
		
		$this->object->setDate(1000, 1, 1);
		
		$this->assertSame($this->helpDate->format('Ymd'), $dolly->format('Ymd'));
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfMonth
	 */
	public function testIsFirstDayOfWeekPositive()
	{
		$this->object->setDate(2013, 7, 1); // monday
		
		$this->assertSame(true, $this->object->isFirstDayOfWeek());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfMonth
	 */
	public function testIsFirstDayOfWeekNegative()
	{
		$this->object->setDate(2013, 7, 2); // tuesday
		
		$this->assertSame(false, $this->object->isFirstDayOfWeek());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfMonth
	 */
	public function testIsLastDayOfWeekPositive()
	{
		$this->object->setDate(2013, 7, 7); // sunday
		
		$this->assertSame(true, $this->object->isLastDayOfWeek());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfMonth
	 */
	public function testIsLastDayOfWeekNegative()
	{
		$this->object->setDate(2013, 7, 2); // tuesday
		
		$this->assertSame(false, $this->object->isLastDayOfWeek());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfMonth
	 */
	public function testIsFirstDayOfMonthPositive()
	{
		$this->object->setDate(2013, 7, 1);
		
		$this->assertSame(true, $this->object->isFirstDayOfMonth());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfMonth
	 */
	public function testIsFirstDayOfMonthNegative()
	{
		$this->object->setDate(2013, 7, 23);
		
		$this->assertSame(false, $this->object->isFirstDayOfMonth());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isLastDayOfMonth
	 */
	public function testIsLastDayOfMonthPositive()
	{
		$this->object->setDate(2013, 7, 31);
		
		$this->assertSame(true, $this->object->isLastDayOfMonth());
	}
	/**
	 * @covers Screwfix\CalendarDateTime::isLastDayOfMonth
	 */
	public function testIsLastDayOfMonthNegative()
	{
		$this->object->setDate(2013, 7, 23);
		
		$this->assertSame(false, $this->object->isLastDayOfMonth());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfYear
	 */
	public function testIsFirstDayOfYearPositive()
	{
		$this->object->setDate(2013, 1, 1);
		
		$this->assertSame(true, $this->object->isFirstDayOfYear());
	}

	/**
	 * @covers Screwfix\CalendarDateTime::isFirstDayOfYear
	 */
	public function testIsFirstDayOfYearNegative()
	{
		$this->object->setDate(2013, 7, 23);
		
		$this->assertSame(false, $this->object->isFirstDayOfYear());
	}

}
