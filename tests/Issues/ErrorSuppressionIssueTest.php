<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\ErrorSuppressionIssue;

class ErrorSuppressionIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\ErrorSuppressionIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Expr\ErrorSuppress')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new ErrorSuppressionIssue ($node);

		$this->assertEquals('Error suppression', $issue->getTitle());
	}

	/**
	 * @covers edsonmedina\php_testability\Issues\ErrorSuppressionIssue::getID
	 */
	public function testGetID ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Expr\ErrorSuppress')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new ErrorSuppressionIssue ($node);

		$this->assertEquals('', $issue->getID());
	}
}