<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class ErrorSuppressionVisitorTest extends PHPUnit_Framework_TestCase
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
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		     
		$visitor = new ErrorSuppressionVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor')
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ErrorSuppress')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ErrorSuppressionVisitor')
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ErrorSuppress')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor->leaveNode ($node);
	}
}
