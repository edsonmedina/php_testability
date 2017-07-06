<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\GlobalVariableIssue;

class GlobalVariableIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\GlobalVariableIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new GlobalVariableIssue ($node);

		$this->assertEquals('Global variable', $issue->getTitle());
	}
}