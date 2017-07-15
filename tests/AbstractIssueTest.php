<?php

use edsonmedina\php_testability\AbstractIssue;
use PhpParser\Node\Expr\StaticCall;

require_once __DIR__.'/../vendor/autoload.php';

class AbstractIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers \edsonmedina\php_testability\AbstractIssue::getLine
	 */
	public function testGetLine ()
	{
		$node = $this->getMockBuilder(StaticCall::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method('getLine')->willReturn (123);

		$issue = $this->getMockBuilder(AbstractIssue::class)
		              ->setConstructorArgs([$node])
		              ->getMockForAbstractClass();

		$this->assertEquals (123, $issue->getLine());
	}	
}