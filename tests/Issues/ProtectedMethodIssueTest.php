<?php

use PhpParser\Node\Stmt\ClassMethod;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\ProtectedMethodIssue;

class ProtectedMethodIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\ProtectedMethodIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(ClassMethod::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new ProtectedMethodIssue ($node);

		$this->assertEquals('Protected method declaration', $issue->getTitle());
	}
}