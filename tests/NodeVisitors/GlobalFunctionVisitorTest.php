<?php
require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor;

class GlobalFunctionVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		                    ->disableOriginalConstructor()
		                    ->setMethods(['start','addIssue','end'])
		                    ->getMock();

		$this->context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		                      ->disableOriginalConstructor()
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\ClassMethod')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->enterNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('end');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\ClassMethod')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('end');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Function_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->stack->expects($this->once())->method('start');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Function_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->enterNode ($node);
	}
}