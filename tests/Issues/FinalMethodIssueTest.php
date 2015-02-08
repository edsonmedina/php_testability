<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\FinalMethodIssue;

class FinalMethodIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\FinalMethodIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\ClassMethod')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new FinalMethodIssue ($node);

		$this->assertEquals('Final method declaration', $issue->getTitle());
	}
}