<?php

namespace edsonmedina\php_testability;

use PhpParser;
use edsonmedina\php_testability\NodeVisitors;
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
	 * @param ContextInterface $context
	 * @return PhpParser\NodeTraverser
	 */
	public function createTraverser (ContextInterface $context)
	{
		$traverser = new PhpParser\NodeTraverser;

		$stack = new ContextStack ($context);
		
		// scope visitors
		$traverser->addVisitor (new NodeVisitors\ClassVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\MethodVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\GlobalFunctionVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\CodeCoverageIgnoreVisitor ($stack, $context));

		// issue visitors
		$traverser->addVisitor (new NodeVisitors\CatchVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\ExitVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\GlobalVarVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\StaticVariableVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\SuperGlobalVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\StaticCallVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\CodeInGlobalSpaceVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\ErrorSuppressionVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\NewVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\IncludeVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\GlobalFunctionCallVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\ClassConstantFetchVisitor ($stack, $context));
		$traverser->addVisitor (new NodeVisitors\StaticPropertyFetchVisitor ($stack, $context));

		return $traverser;
	}
}
