<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\Issues\SuperGlobalAccessIssue;

class SuperGlobalAccessIssueTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\Issues\SuperGlobalAccessIssue::getTitle
	 */
	public function testGetTitle ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$issue = new SuperGlobalAccessIssue ($node);

		$this->assertEquals('Super global access', $issue->getTitle());
	}
}