<?php

use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use edsonmedina\php_testability\Contexts\FileContext;
use edsonmedina\php_testability\ContextStack;
use edsonmedina\php_testability\NodeVisitors\ClassVisitor;

require_once __DIR__.'/../../vendor/autoload.php';

class ClassVisitorTest extends PHPUnit\Framework\TestCase
{
    /** @var ContextStack|PHPUnit_Framework_MockObject_MockObject */
    private $stack;

    /** @var FileContext|PHPUnit_Framework_MockObject_MockObject */
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
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder (Function_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder(ClassVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['isClass'])
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(false);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('end');

		$node = $this->getMockBuilder (Function_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder(ClassVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['isClass'])
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(false);

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('end');

		$node = $this->getMockBuilder (Class_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder(ClassVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['isClass'])
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNodeWithTrait ()
	{
		$this->stack->expects($this->once())->method('start');
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder (Trait_::class)
		             ->setConstructorArgs(['test'])
		             ->setMethods(['isFinal'])
		             ->getMock();

		$node->expects($this->never())->method('isFinal');

		$visitor = $this->getMockBuilder(ClassVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['isClass'])
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNodeWithNonFinalClass ()
	{
		$this->stack->expects($this->once())->method('start');
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder (Class_::class)
		             ->setConstructorArgs(['test'])
		             ->setMethods(['isFinal'])
		             ->getMock();

		$node->expects($this->once())->method('isFinal')->willReturn(false);

		$visitor = $this->getMockBuilder(ClassVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['isClass'])
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->stack->expects($this->once())->method('start');
		$this->stack->expects($this->once())->method('addIssue');

		$node = $this->getMockBuilder (Class_::class)
		             ->setConstructorArgs(['test'])
		             ->setMethods(['isFinal'])
		             ->getMock();

		$node->expects($this->once())->method('isFinal')->willReturn(true);

		$visitor = $this->getMockBuilder(ClassVisitor::class)
		                ->setConstructorArgs([$this->stack, $this->context])
		                ->setMethods(['isClass'])
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::isClass
	 */
	public function testIsClassWithDifferentTypes ()
	{
		$node = $this->getMockBuilder (Function_::class)
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ClassVisitor ($this->stack, $this->context);
		$this->assertFalse ($visitor->isClass ($node));

		$node2 = $this->getMockBuilder (Interface_::class)
		              ->disableOriginalConstructor()
		              ->getMock();

		$this->assertFalse ($visitor->isClass ($node2));
	}

	/**
	 * @covers \edsonmedina\php_testability\NodeVisitors\ClassVisitor::isClass
	 */
	public function testIsClass ()
	{
		$node1 = $this->getMockBuilder (Class_::class)
		              ->disableOriginalConstructor()
		              ->getMock();

		$node2 = $this->getMockBuilder (Trait_::class)
		              ->disableOriginalConstructor()
		              ->getMock();

		$visitor = new ClassVisitor ($this->stack, $this->context);

		$this->assertTrue ($visitor->isClass ($node1));
		$this->assertTrue ($visitor->isClass ($node2));
	}
}