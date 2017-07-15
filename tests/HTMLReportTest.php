<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\HTMLReport;
use edsonmedina\php_testability\Contexts\DirectoryContext;

class HTMLReportTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\HTMLReport::convertPathToRelative
	 * @uses \edsonmedina\php_testability\Contexts\DirectoryContext::__construct
	 */
	public function testConvertPathToRelative ()
	{
		$report = new HTMLReport (new DirectoryContext('/Whatever/path/files/are'), '', false);

		$this->assertEquals ('is/fine.php', $report->convertPathToRelative('/Whatever/path/files/are/is/fine.php'));
	}	
}