<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\ExternalClassConstantFetchIssue;

class ExternalClassConstantFetchIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\ExternalClassConstantFetchIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new ExternalClassConstantFetchIssue ($node);

		$this->assertEquals('External class constant fetch', $issue->getTitle());
	}
}