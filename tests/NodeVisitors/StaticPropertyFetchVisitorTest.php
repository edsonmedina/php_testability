<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class StaticPropertyFetchVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->context = new RootContext ('/');

		$this->stack = $this->getMockBuilder ('edsonmedina\php_testability\ContextStack')
		                    ->setConstructorArgs(array($this->context))
		                    ->setMethods(array('addIssue'))
		                    ->getMock();

		$this->wrongNode = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticPropertyFetch')
		                   ->disableOriginalConstructor()
		                   ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		
		$visitor = new StaticPropertyFetchVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNodeFetchingFromSelf()
	{
		$this->stack->expects($this->never())->method('addIssue');

		// visitor
		$visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods (array('isFetchingFromSelf'))
		                ->getMock();

		$visitor->method ('isFetchingFromSelf')->willReturn (true);

		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor::leaveNode
	 */
	public function testLeaveNode()
	{
		$this->stack->expects($this->once())->method('addIssue');
		
		// visitor
		$visitor = $this->getMockBuilder ('edsonmedina\php_testability\NodeVisitors\StaticPropertyFetchVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods (array('isFetchingFromSelf'))
		                ->getMock();

		$visitor->method ('isFetchingFromSelf')->willReturn (false);

		$visitor->leaveNode ($this->node);
	}
}