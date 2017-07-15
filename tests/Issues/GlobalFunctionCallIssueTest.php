<?php

use PhpParser\Node\Stmt\Function_;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\GlobalFunctionCallIssue;

class GlobalFunctionCallIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\GlobalFunctionCallIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(Function_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new GlobalFunctionCallIssue ($node);

		$this->assertEquals('Global function call', $issue->getTitle());
	}
}