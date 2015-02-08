<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\GlobalFunctionCallIssue;

class GlobalFunctionCallIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\GlobalFunctionCallIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\Function_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new GlobalFunctionCallIssue ($node);

		$this->assertEquals('Global function call', $issue->getTitle());
	}
}