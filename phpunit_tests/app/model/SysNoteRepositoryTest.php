<?php

namespace Screwfix;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-07-12 at 19:51:34.
 */
class SysNoteRepositoryTest extends \DatabaseTestCase {

	/**
	 * @var SysNoteRepository
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new SysNoteRepository($this->getNetteConnection());
	}

	protected function getDataSet()
	{
		return $this->createXMLDataSet(__DIR__ . "\sysNoteRepositoryTest.xml");
	}

	/**
	 * @covers Screwfix\SysNoteRepository::table
	 */
	public function testTable()
	{
		$this->assertInstanceOf('\Nette\Database\Table\Selection', $this->object->table());
	}

	/**
	 * @covers Screwfix\SysNoteRepository::between
	 */
	public function testBetween()
	{
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers Screwfix\SysNoteRepository::notesBetween
	 */
	public function testNotesBetween()
	{
		$expected = array(
			2156400 => array(
				'Note1',
				'Note3',
			),
			4921200 => array('Note4'),
		);

		$this->assertSame($expected, $this->object->notesBetween(0, 4921200));
	}

}
