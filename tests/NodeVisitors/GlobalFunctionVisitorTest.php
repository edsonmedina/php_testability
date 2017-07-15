<?php
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\ClassMethod;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor;

require_once __DIR__.'/../../vendor/autoload.php';

class GlobalFunctionVisitorTest extends PHPUnit\Framework\TestCase
{
    private $stack;
    private $context;

    public function setup ()
	{
		$this->stack = $this->getMockBuilder(ContextStack::class)
		                    ->disableOriginalConstructor()
		                    ->setMethods(['start','addIssue','end'])
		                    ->getMock();

		$this->context = $this->getMockBuilder(FileContext::class)
		                      ->disableOriginalConstructor()
		                      ->getMock();
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder (ClassMethod::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->enterNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('end');

		$node = $this->getMockBuilder (ClassMethod::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('end');

		$node = $this->getMockBuilder (Function_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\GlobalFunctionVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->stack->expects($this->once())->method('start');

		$node = $this->getMockBuilder (Function_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new GlobalFunctionVisitor($this->stack, $this->context);
		$visitor->enterNode ($node);
	}
}