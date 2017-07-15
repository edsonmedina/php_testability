<?php

use PhpParser\Node\Stmt\ClassMethod;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\FinalMethodIssue;

class FinalMethodIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\FinalMethodIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(ClassMethod::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new FinalMethodIssue ($node);

		$this->assertEquals('Final method declaration', $issue->getTitle());
	}
}