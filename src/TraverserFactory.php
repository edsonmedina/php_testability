<?php

namespace edsonmedina\php_testability;

use PhpParser;
use edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\Dictionary;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;

/**
 * TraverserFactory
 * This class creates a node traverser
 * @Factory
 * @author Edson Medina <edsonmedina@gmail.com>
 */
class TraverserFactory
{
	/**
	 * Create a node traverser object 
	 * @param ReportDataInterface $data
	 * @param AnalyserScope $scope
	 * @return PhpParser\NodeTraverser
	 */
	public function createTraverser (ReportDataInterface $data, AnalyserScope $scope)
	{
		$traverser = new PhpParser\NodeTraverser;
		
		$traverser->addVisitor (new NodeVisitors\CodeCoverageIgnoreVisitor  ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\CodeInGlobalSpaceVisitor   ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\ClassConstantFetchVisitor  ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\StaticPropertyFetchVisitor ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\GlobalFunctionVisitor      ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\GlobalFunctionCallVisitor  ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\SuperGlobalVisitor         ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\StaticVariableVisitor      ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\ClassVisitor      ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\TraitVisitor      ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\InterfaceVisitor  ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\NewVisitor        ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\ExitVisitor       ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\GlobalVarVisitor  ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\StaticCallVisitor ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\MethodVisitor     ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\IncludeVisitor    ($data, $scope, $this));
		$traverser->addVisitor (new NodeVisitors\CatchVisitor      ($data, $scope, $this));

		return $traverser;
	}

	/**
	 * Create a dictionary object 
	 * @return Dictionary
	 */
	public function getDictionary ()
	{
		return new Dictionary();
	}

	/**
	 * Create a node wrapper object
	 * @param PhpParser\Node $node
	 * @return NodeWrapper
	 */
	public function getNodeWrapper (PhpParser\Node $node)
	{
		return new NodeWrapper ($node);
	}
}
