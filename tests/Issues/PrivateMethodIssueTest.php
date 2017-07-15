<?php

use PhpParser\Node\Stmt\ClassMethod;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\PrivateMethodIssue;

class PrivateMethodIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\PrivateMethodIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(ClassMethod::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new PrivateMethodIssue ($node);

		$this->assertEquals('Private method declaration', $issue->getTitle());
	}
}