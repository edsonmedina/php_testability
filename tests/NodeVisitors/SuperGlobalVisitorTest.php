<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor;

class SuperGlobalVisitorTest extends PHPUnit_Framework_TestCase
{
	public function setup ()
	{
		$this->data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		                   ->disableOriginalConstructor()
		                   ->setMethods(array('addIssue'))
		                   ->getMock();	

		$this->scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		                    ->disableOriginalConstructor()
		                    ->getMock();	

		$this->wrongNode = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		                        ->disableOriginalConstructor()
		                        ->getMock();

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$visitor = new SuperGlobalVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ArrayDimFetch')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new SuperGlobalVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	/*
	public function testLeaveNodeWithArrayNameNotInList ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (false);

		$var = $this->getMockBuilder ('PhpParser\Node\Expr\Variable')
		            ->setConstructorArgs(array('_GET'))
		            ->getMock();

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\ArrayDimFetch')
		             ->setConstructorArgs(array($var))
		             ->getMock();

		$visitor = new SuperGlobalVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}
	*/

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\SuperGlobalVisitor::leaveNode
	 */
	/*
	public function testLeaveNode ()
	{
		$this->scope->method ('inGlobalSpace')->willReturn (false);
		$this->scope->method ('getScopeName')->willReturn ('foo');

		$this->node->test = 1;
		$this->node->var = new stdClass();
		$this->node->var->name = '_GET';
		$this->node->method ('getLine')->willReturn (7);

var_dump ($this->node);die();

		$this->data->expects($this->once())->method('addIssue')
		     ->with(
		           $this->equalTo(7),
		           $this->equalTo('super_global'),
		           $this->equalTo('foo'),
		           $this->equalTo('$_GET')
		       );

		$visitor = new SuperGlobalVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->node);
	}
	*/
}