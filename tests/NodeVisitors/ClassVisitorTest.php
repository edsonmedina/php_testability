<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\ClassVisitor;
use edsonmedina\php_testability\NodeVisitors\TraitVisitor;
use edsonmedina\php_testability\NodeVisitors\InterfaceVisitor;

class ClassVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->stack = $this->getMockBuilder('edsonmedina\php_testability\ContextStack')
		                    ->disableOriginalConstructor()
		                    ->setMethods(array('start','addIssue','end'))
		                    ->getMock();

		$this->context = $this->getMockBuilder('edsonmedina\php_testability\Contexts\FileContext')
		                      ->disableOriginalConstructor()
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Function_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ClassVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array ('isClass'))
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(false);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->stack->expects($this->never())->method('end');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Function_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ClassVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array('isClass'))
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(false);

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		$this->stack->expects($this->once())->method('end');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ClassVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array('isClass'))
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNodeWithTrait ()
	{
		$this->stack->expects($this->once())->method('start');
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->setConstructorArgs(array('test'))
		             ->setMethods(array('isFinal'))
		             ->getMock();

		$node->expects($this->never())->method('isFinal');

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ClassVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array ('isClass'))
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNodeWithNonFinalClass ()
	{
		$this->stack->expects($this->once())->method('start');
		$this->stack->expects($this->never())->method('addIssue');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->setConstructorArgs(array('test'))
		             ->setMethods(array('isFinal'))
		             ->getMock();

		$node->expects($this->once())->method('isFinal')->willReturn(false);

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ClassVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array ('isClass'))
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::enterNode
	 */
	public function testEnterNode ()
	{
		$this->stack->expects($this->once())->method('start');
		$this->stack->expects($this->once())->method('addIssue');

		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		             ->setConstructorArgs(array('test'))
		             ->setMethods(array('isFinal'))
		             ->getMock();

		$node->expects($this->once())->method('isFinal')->willReturn(true);

		$visitor = $this->getMockBuilder('edsonmedina\php_testability\NodeVisitors\ClassVisitor')
		                ->setConstructorArgs(array($this->stack, $this->context))
		                ->setMethods(array ('isClass'))
		                ->getMock();

		$visitor->expects($this->once())->method('isClass')->willReturn(true);

		$visitor->enterNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::isClass
	 */
	public function testIsClassWithDifferentTypes ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Function_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ClassVisitor ($this->stack, $this->context);
		$this->assertFalse ($visitor->isClass ($node));
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassVisitor::isClass
	 */
	public function testIsClass ()
	{
		$node1 = $this->getMockBuilder ('PhpParser\Node\Stmt\Class_')
		              ->disableOriginalConstructor()
		              ->getMock();

		$node2 = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		              ->disableOriginalConstructor()
		              ->getMock();

		$node3 = $this->getMockBuilder ('PhpParser\Node\Stmt\Interface_')
		              ->disableOriginalConstructor()
		              ->getMock();

		$visitor = new ClassVisitor ($this->stack, $this->context);

		$this->assertTrue ($visitor->isClass ($node1));
		$this->assertTrue ($visitor->isClass ($node2));
		$this->assertTrue ($visitor->isClass ($node3));
	}
}