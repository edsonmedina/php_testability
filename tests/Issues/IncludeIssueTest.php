<?php

use PhpParser\Node\Stmt\Class_;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\IncludeIssue;

class IncludeIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\IncludeIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(Class_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new IncludeIssue ($node);

		$this->assertEquals('Include', $issue->getTitle());
	}
}