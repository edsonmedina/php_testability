<?php

use PhpParser\Node\Stmt\Catch_;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\EmptyCatchIssue;

class EmptyCatchIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\EmptyCatchIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(Catch_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new EmptyCatchIssue ($node);

		$this->assertEquals('Empty catch block', $issue->getTitle());
	}

	/**
	 * @covers \edsonmedina\php_testability\Issues\EmptyCatchIssue::getID
	 */
	public function testGetID ()
	{
		$node = $this->getMockBuilder(Catch_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new EmptyCatchIssue ($node);

		$this->assertEquals('', $issue->getID());
	}
}