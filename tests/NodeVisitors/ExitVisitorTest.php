<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\ExitVisitor;
use edsonmedina\php_testability\Contexts\RootContext;
use edsonmedina\php_testability\ContextStack;

class ExitVisitorTest extends PHPUnit_Framework_TestCase
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

		$this->node = $this->getMockBuilder ('PhpParser\Node\Expr\Exit_')
		                   ->disableOriginalConstructor()
		                   ->getMock();
	}
	
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');
		     
		$visitor = new ExitVisitor ($this->stack, $this->context);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\FuncCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		$this->stack->expects($this->never())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ExitVisitor')
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (true);

		$visitor->leaveNode ($this->node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ExitVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\FuncCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		$this->stack->expects($this->once())->method('addIssue');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ExitVisitor')
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['inGlobalScope'])
		                ->getMock();

		$visitor->expects($this->once())->method('inGlobalScope')->willReturn (false);

		$visitor->leaveNode ($this->node);
	}	
}