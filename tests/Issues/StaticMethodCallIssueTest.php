<?php

use PhpParser\Node\Stmt\ClassMethod;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\StaticMethodCallIssue;

class StaticMethodCallIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\StaticMethodCallIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(ClassMethod::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new StaticMethodCallIssue ($node);

		$this->assertEquals('Static method call', $issue->getTitle());
	}
}