<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor;

class StaticVariableVisitorTest extends PHPUnit_Framework_TestCase
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

		$this->factory = $this->getMock('edsonmedina\php_testability\TraverserFactory');
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$node = $this->getMockBuilder('PhpParser\Node\Expr\Eval_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticVariableVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::__construct
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticVariableVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		// data
		$this->data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->equalTo(7),
		           $this->equalTo('static_var'),
		           $this->equalTo('someScopeName'),
		           $this->equalTo('$foo')
		       );

		// scope
		$this->scope->method ('getScopeName')->willReturn ('someScopeName');

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$nodewrapper->method ('getVarList')->willReturn (array (
			(object) array ('name' => 'foo')
		));

		// factory
		$this->factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Stmt\Static_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method ('getLine')->willReturn (7);

		$visitor = new StaticVariableVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}	
}