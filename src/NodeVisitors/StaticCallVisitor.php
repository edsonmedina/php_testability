<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Expr;

class StaticCallVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for static method calls (ie: Things::doStuff())
        if ($node instanceof Expr\StaticCall && !$this->scope->inGlobalSpace()) 
        {
            $obj = new NodeWrapper ($node);
            $this->data->addIssue ($obj->line, 'static_call', $this->scope->getScopeName(), $obj->getName());
        }
    }
}
