<?php

use PhpParser\Node\Expr\New_;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\NewInstanceIssue;

class NewInstanceIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\NewInstanceIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(New_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new NewInstanceIssue ($node);

		$this->assertEquals('New instance', $issue->getTitle());
	}
}