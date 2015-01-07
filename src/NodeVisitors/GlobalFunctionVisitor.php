<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;

use PhpParser;
use PhpParser\Node\Stmt;

class GlobalFunctionVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Function_) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->scope->startFunction ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $obj->line);
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // end of method or global function
        if ($node instanceof Stmt\Function_) 
        {
            $this->scope->endFunction();
        }
    }
}
