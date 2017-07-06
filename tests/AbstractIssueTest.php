<?php

require_once __DIR__.'/../vendor/autoload.php';

class AbstractIssueTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @covers edsonmedina\php_testability\AbstractIssue::getLine
	 */
	public function testGetLine ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Expr\StaticCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method('getLine')->willReturn (123);

		$issue = $this->getMockBuilder('edsonmedina\php_testability\AbstractIssue')
		              ->setConstructorArgs([$node])
		              ->getMockForAbstractClass();

		$this->assertEquals (123, $issue->getLine());
	}	
}