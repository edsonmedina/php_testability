<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\StaticCallVisitor;

class StaticCallVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
	 */
	public function testLeaveNodeWithDifferentType ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$factory = $this->getMock('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder('PhpParser\Node\Expr\Eval_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticCallVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
	 */
	public function testLeaveNodeInGlobalSpace ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (true);

		$factory = $this->getMock ('edsonmedina\php_testability\TraverserFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new StaticCallVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
	 */
	public function testLeaveNodeWithSafeClass ()
	{
		// data
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (false);
		$scope->method ('getScopeName')->willReturn ('someScopeName');

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		             ->disableOriginalConstructor()
		             ->getMock();

		$nodewrapper->method ('getName')->willReturn ('foo');

		// dictionary
		$dictionary = $this->getMockBuilder ('edsonmedina\php_testability\Dictionary')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$dictionary->method ('isClassSafeForInstantiation')->willReturn (true);

		// factory
		$factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                ->getMock();

		$factory->method ('getNodeWrapper')->willReturn ($nodewrapper);
		$factory->method ('getDictionary')->willReturn ($dictionary);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method ('getLine')->willReturn (7);

		$visitor = new StaticCallVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\StaticCallVisitor::leaveNode
	 */
	public function testLeaveNode ()
	{
		// data
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->equalTo(7),
		           $this->equalTo('static_call'),
		           $this->equalTo('someScopeName'),
		           $this->equalTo('foo')
		       );

		// scope
		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (false);
		$scope->method ('getScopeName')->willReturn ('someScopeName');

        // node wrapper
		$nodewrapper = $this->getMockBuilder ('edsonmedina\php_testability\NodeWrapper')
		             ->disableOriginalConstructor()
		             ->getMock();

		$nodewrapper->method ('getName')->willReturn ('foo');

		// dictionary
		$dictionary = $this->getMockBuilder ('edsonmedina\php_testability\Dictionary')
		                   ->disableOriginalConstructor()
		                   ->getMock();

		$dictionary->method ('isClassSafeForInstantiation')->willReturn (false);

		// factory
		$factory = $this->getMockBuilder ('edsonmedina\php_testability\TraverserFactory')
		                ->getMock();

		$factory->method ('getNodeWrapper')->willReturn ($nodewrapper);
		$factory->method ('getDictionary')->willReturn ($dictionary);

		// node
		$node = $this->getMockBuilder ('PhpParser\Node\Expr\StaticCall')
		             ->disableOriginalConstructor()
		             ->getMock();

		$node->method ('getLine')->willReturn (7);

		$visitor = new StaticCallVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	
}