<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\StaticMethodCallIssue;

class StaticMethodCallIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\StaticMethodCallIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\ClassMethod')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new StaticMethodCallIssue ($node);

		$this->assertEquals('Static method call', $issue->getTitle());
	}
}