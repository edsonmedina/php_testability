<?php

use PhpParser\Node\Stmt\Class_;

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\StaticVariableDeclarationIssue;

class StaticVariableDeclarationIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\Issues\StaticVariableDeclarationIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder(Class_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new StaticVariableDeclarationIssue ($node);

		$this->assertEquals('Static variable declaration', $issue->getTitle());
	}
}