<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\HTMLReport;

class HTMLReportTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\HTMLReport::convertPathToRelative
	 */
	public function testConvertPathToRelative ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')->disableOriginalConstructor()->getMock();
		$report = new HTMLReport ('/Whatever/path/files/are', '', $data);

		$this->assertEquals ('is/fine.php', $report->convertPathToRelative('/Whatever/path/files/are/is/fine.php'));
	}	
}