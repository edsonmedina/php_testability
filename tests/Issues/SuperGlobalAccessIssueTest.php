<?php

use PhpParser\Node\Stmt\Class_;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\SuperGlobalAccessIssue;

class SuperGlobalAccessIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\SuperGlobalAccessIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(Class_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new SuperGlobalAccessIssue ($node);

		$this->assertEquals('Super global access', $issue->getTitle());
	}
}