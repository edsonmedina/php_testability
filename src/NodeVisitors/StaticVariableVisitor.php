<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Stmt;

class StaticVariableVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $insideThrow = false;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Static_) 
        {
            $obj = new NodeWrapper ($node);
            $this->data->addIssue ($obj->line, 'static_var', $this->scope->getScopeName(), $obj->getName());
        }
    }
}
