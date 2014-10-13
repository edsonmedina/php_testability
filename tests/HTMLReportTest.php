<?php

require_once __DIR__.'/../vendor/autoload.php';
use edsonmedina\php_testability\HTMLReport;

class HTMLReportTest extends PHPUnit_Framework_TestCase
{
	public function testFindBaseDirectory ()
	{
		$list = array (
			'/Whatever/dir/where/stuff/is/should/be/fine.php',
			'/Whatever/dir/where/stuff/is/should/work.php',
			'/Whatever/dir/where/stuff/is/good.php',
			'/Whatever/dir/where/stuff/sits.php',
		);

		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')->disableOriginalConstructor()->getMock();
		$data->expects($this->any())->method('getFileList')->will($this->returnValue($list));

		$report = new HTMLReport ('/tmp', $data);

		$this->assertEquals ('/Whatever/dir/where/stuff/', $report->findBaseDirectory());
	}	
}