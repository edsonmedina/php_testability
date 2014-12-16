<?php

namespace edsonmedina\php_testability;

use PhpParser;
use edsonmedina\php_testability\NodeVisitors;
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
	public function getInstance (ReportDataInterface $data, AnalyserScope $scope)
	{
		$traverser = new PhpParser\NodeTraverser;
		$traverser->addVisitor (new NodeVisitors\CodeInGlobalSpaceVisitor   ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\ClassConstantFetchVisitor  ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\StaticPropertyFetchVisitor ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\GlobalFunctionVisitor      ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\GlobalFunctionCallVisitor  ($data, new Dictionary(), $scope));
		$traverser->addVisitor (new NodeVisitors\SuperGlobalVisitor         ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\StaticVariableVisitor      ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\ClassVisitor      ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\TraitVisitor      ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\InterfaceVisitor  ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\NewVisitor        ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\ExitVisitor       ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\GlobalVarVisitor  ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\StaticCallVisitor ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\MethodVisitor     ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\IncludeVisitor    ($data, $scope));
		$traverser->addVisitor (new NodeVisitors\CatchVisitor      ($data, $scope));

		return $traverser;
	}
}