<?php

namespace edsonmedina\php_testability;

use PhpParser;
use edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\Dictionary;
use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\ContextStack;

/**
 * AnalyserAbstractFactory
 * This class creates a node traverser
 * @Factory
 * @author Edson Medina <edsonmedina@gmail.com>
 */
class AnalyserAbstractFactory
{
	/**
	 * Create a node traverser object 
	 * @param ReportData $data
	 * @param AnalyserScope $scope
	 * @return PhpParser\NodeTraverser
	 */
	public function createTraverser (ContextInterface $context)
	{
		$traverser = new PhpParser\NodeTraverser;

		$stack = new ContextStack ($context);
		
		$traverser->addVisitor (new NodeVisitors\ClassVisitor  ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\MethodVisitor ($stack, $context));

		// $traverser->addVisitor (new NodeVisitors\CodeCoverageIgnoreVisitor  ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\CodeInGlobalSpaceVisitor   ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\ClassConstantFetchVisitor  ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\StaticPropertyFetchVisitor ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\GlobalFunctionVisitor      ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\GlobalFunctionCallVisitor  ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\SuperGlobalVisitor         ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\StaticVariableVisitor      ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\ErrorSuppressionVisitor    ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\NewVisitor        ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\ExitVisitor       ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\GlobalVarVisitor  ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\StaticCallVisitor ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\IncludeVisitor    ($data, $scope, $this));
		// $traverser->addVisitor (new NodeVisitors\CatchVisitor      ($data, $scope, $this));

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
