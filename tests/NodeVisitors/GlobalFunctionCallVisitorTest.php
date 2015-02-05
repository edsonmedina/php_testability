<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor;

class GlobalFunctionCallVisitorTest extends PHPUnit_Framework_TestCase
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

		$this->factory = $this->getMockBuilder ('edsonmedina\php_testability\AnalyserAbstractFactory')
		                      ->setMethods(array('getDictionary','getNodeWrapper'))
		                      ->getMock();
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$this->factory->method ('getDictionary')->willReturn (false);

		// data
		$this->data->expects($this->never())
		     ->method('addIssue');
		     
		$visitor = new GlobalFunctionCallVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$this->factory->method ('getDictionary')->willReturn (false);

		$this->scope->method ('inGlobalSpace')->willReturn (true);

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\FuncCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		// data
		$this->data->expects($this->never())
		     ->method('addIssue');

		$visitor = new GlobalFunctionCallVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($this->wrongNode);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNodeWithInternalFunction ()
	{
		// dictionary
		$dictionary = $this->getMockBuilder ('edsonmedina\php_testability\Dictionary')
		                   ->setMethods(array('isInternalFunction'))
		                   ->getMock();

		$dictionary->method ('isInternalFunction')->willReturn (true);

		// factory
		$this->factory->method ('getDictionary')->willReturn ($dictionary);

		// scope
		$this->scope->method ('inGlobalSpace')->willReturn (false);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\FuncCall')
		             ->disableOriginalConstructor()
		             ->getMock();

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$nodewrapper->method ('getName')->willReturn ('Test::foo');

		// data
		$this->data->expects($this->never())
		     ->method('addIssue');

		// factory
		$this->factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		$visitor = new GlobalFunctionCallVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\GlobalFunctionCallVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		// dictionary
		$dictionary = $this->getMockBuilder ('edsonmedina\php_testability\Dictionary')
		                   ->setMethods(array('isInternalFunction'))
		                   ->getMock();

		$dictionary->method ('isInternalFunction')->willReturn (false);

		// factory
		$this->factory->method ('getDictionary')->willReturn ($dictionary);

		// scope
		$this->scope->method ('inGlobalSpace')->willReturn (false);
		$this->scope->method ('getScopeName')->willReturn ('someScopeName');

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\FuncCall')
		             ->disableOriginalConstructor()
		             ->getMock();

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		                    ->disableOriginalConstructor()
		                    ->getMock();

		$nodewrapper->method ('getName')->willReturn ('Test::foo');

		// factory
		$this->factory->method ('getNodeWrapper')->willReturn ($nodewrapper);

		// data
		$this->data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->anything(),
		           $this->equalTo('someScopeName')
		       );

		$visitor = new GlobalFunctionCallVisitor ($this->data, $this->scope, $this->factory);
		$visitor->leaveNode ($node);
	}
}
