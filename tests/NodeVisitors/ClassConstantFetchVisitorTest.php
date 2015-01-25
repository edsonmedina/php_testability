<?php

require_once __DIR__.'/../../vendor/autoload.php';

use edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor;
use Prophecy\Argument;

class ClassConstantFetchVisitorTest extends PHPUnit_Framework_TestCase
{
	private $prophet;
	private $data;
	private $scope;
	private $factory;
	private $nodeWrapper;

	public function setup ()
	{
		$this->prophet     = new Prophecy\Prophet;
		$this->data        = $this->prophet->prophesize('edsonmedina\php_testability\ReportData');
		$this->scope       = $this->prophet->prophesize('edsonmedina\php_testability\AnalyserScope');
		$this->factory     = $this->prophet->prophesize('edsonmedina\php_testability\TraverserFactory');
		$this->nodeWrapper = $this->prophet->prophesize('edsonmedina\php_testability\NodeWrapper');
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Trait_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$this->data->addIssue(Argument::any())->shouldNotBeCalled();	

		$visitor = new ClassConstantFetchVisitor ($this->data->reveal(), $this->scope->reveal(), $this->factory->reveal());
		$visitor->leaveNode ($node);

		$this->prophet->checkPredictions();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->data->addIssue(Argument::any())->shouldNotBeCalled();	

		$this->scope->inGlobalSpace()->willReturn (true);
		              
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ClassConstFetch')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new ClassConstantFetchVisitor ($this->data->reveal(), $this->scope->reveal(), $this->factory->reveal());
		$visitor->leaveNode ($node);

		$this->prophet->checkPredictions();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeOutsideClass ()
	{
		$this->data->addIssue(123, 'external_class_constant_fetch', 'foo::bar', 'foo')->shouldBeCalled();	

		$this->scope->inGlobalSpace()->willReturn (false);
		$this->scope->insideClass()->willReturn (false);
		$this->scope->getScopeName()->willReturn ('foo::bar');
		              
		$this->nodeWrapper->getName()->willReturn ('foo');

		$this->factory->getNodeWrapper(Argument::any())->willReturn ($this->nodeWrapper);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ClassConstFetch')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method ('getLine')->willReturn (123);

		$visitor = new ClassConstantFetchVisitor ($this->data->reveal(), $this->scope->reveal(), $this->factory->reveal());
		$visitor->leaveNode ($node);

		$this->prophet->checkPredictions();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeSameClass ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ClassConstFetch')
		             ->disableOriginalConstructor()
		             ->getMock();

		$this->scope->inGlobalSpace()->willReturn (false);

		$this->nodeWrapper->isSameClassAs('whatever')->willReturn (true);

		$this->factory->getNodeWrapper(Argument::any())->willReturn ($this->nodeWrapper);

		$this->data->addIssue(Argument::any())->shouldNotBeCalled();	

		$this->scope->insideClass()->willReturn (true);
		$this->scope->getClassName()->willReturn ('whatever');

		$visitor = new ClassConstantFetchVisitor ($this->data->reveal(), $this->scope->reveal(), $this->factory->reveal());
		$visitor->leaveNode ($node);

		$this->prophet->checkPredictions();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\ClassConstantFetchVisitor::leaveNode
	 */
	public function testLeaveNodeDifferentClass ()
	{
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ClassConstFetch')
		             ->disableOriginalConstructor()
		             ->getMock();
		             
		$node->method ('getLine')->willReturn (123);

		$this->scope->inGlobalSpace()->willReturn (false);
		$this->scope->getScopeName()->willReturn ('foo::bar');

		$this->nodeWrapper->isSameClassAs('whatever')->willReturn (false);
		$this->nodeWrapper->getName()->willReturn ('foo');

		$this->factory->getNodeWrapper(Argument::any())->willReturn ($this->nodeWrapper);

		$this->data->addIssue(123, 'external_class_constant_fetch', 'foo::bar', 'foo')->shouldBeCalled();	

		$this->scope->insideClass()->willReturn (true);
		$this->scope->getClassName()->willReturn ('whatever');

		$visitor = new ClassConstantFetchVisitor ($this->data->reveal(), $this->scope->reveal(), $this->factory->reveal());
		$visitor->leaveNode ($node);

		$this->prophet->checkPredictions();
	}
}