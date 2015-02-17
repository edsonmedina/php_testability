<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\StaticVariableDeclarationIssue;

class StaticVariableDeclarationIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\StaticVariableDeclarationIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new StaticVariableDeclarationIssue ($node);

		$this->assertEquals('Static variable declaration', $issue->getTitle());
	}
}