<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class SuperGlobalVisitorTest extends PHPUnit\Framework\TestCase
{
	public function setup ()
	{
		$this->context = new RootContext ('/');

		$this->stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		                    ->setConstructorArgs([$this->context])
		                    ->setMethods(['addIssue'])
		                    ->getMock();

		$this->wrongNode = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor')
		                      ->setConstructorArgs([$this->stack, $this->context])
		                      ->setMethods(['inGlobalScope'])
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = new SuperGlobalVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}
}
