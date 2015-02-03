<?php

require_once __DIR__.'/../../vendor/autoload.php';
use edsonmedina\php_testability\NodeVisitors\IncludeVisitor;
use edsonmedina\php_testability\AnalyserAbstractFactory;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Variable;

class IncludeVisitorTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNodeWithDifferentType ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();
		              
		$factory = $this->getMock ('edsonmedina\php_testability\AnalyserAbstractFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Exit_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new IncludeVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNodeInGlobalSpace ()
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

		$factory = $this->getMock ('edsonmedina\php_testability\AnalyserAbstractFactory');

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Include_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new IncludeVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNodeWithAutoloader ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->never())->method('addIssue');

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (false);
		$scope->method ('getScopeName')->willReturn ('__autoload');

		$factory = $this->getMockBuilder ('edsonmedina\php_testability\AnalyserAbstractFactory')
		                ->getMock();

		$node = $this->getMockBuilder ('PhpParser\Node\Expr\Include_')
		             ->disableOriginalConstructor()
		             ->getMock();

		$visitor = new IncludeVisitor ($data, $scope, $factory);
		$visitor->leaveNode ($node);
	}	

	/**
	 * @covers edsonmedina\php_testability\NodeVisitors\IncludeVisitor::leaveNode
	 */
	public function testleaveNode ()
	{
		$data = $this->getMockBuilder('edsonmedina\php_testability\ReportData')
		             ->disableOriginalConstructor()
		             ->setMethods(array('addIssue'))
		             ->getMock();

		$data->expects($this->once())
		     ->method('addIssue')
		     ->with(
		           $this->equalTo(7),
		           $this->equalTo('include'),
		           $this->equalTo('someScopeName'),
		           $this->anything()
		       );

		$scope = $this->getMockBuilder('edsonmedina\php_testability\AnalyserScope')
		              ->disableOriginalConstructor()
		              ->getMock();

		$scope->method ('inGlobalSpace')->willReturn (false);
		$scope->method ('getScopeName')->willReturn ('someScopeName');

		$node = new Include_ (
			new Variable('test'), 
			PhpParser\Node\Expr\Include_::TYPE_REQUIRE, 
			array ('startLine' => 7)
		);

		$visitor = new IncludeVisitor ($data, $scope, new AnalyserAbstractFactory);
		$visitor->leaveNode ($node);
	}	
}