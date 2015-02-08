<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\CodeOnGlobalSpaceIssue;

class CodeOnGlobalSpaceIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\CodeOnGlobalSpaceIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new CodeOnGlobalSpaceIssue ($node);

		$this->assertEquals('Code on global space', $issue->getTitle());
	}

	/**
	 * @covers edsonmedina\php_testability\Issues\CodeOnGlobalSpaceIssue::getID
	 */
	public function testGetID ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new CodeOnGlobalSpaceIssue ($node);

		$this->assertEquals('', $issue->getID());
	}
}