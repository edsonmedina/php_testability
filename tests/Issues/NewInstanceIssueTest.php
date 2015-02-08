<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\NewInstanceIssue;

class NewInstanceIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\NewInstanceIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Expr\New_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new NewInstanceIssue ($node);

		$this->assertEquals('New instance', $issue->getTitle());
	}
}