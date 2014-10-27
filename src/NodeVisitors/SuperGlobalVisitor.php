<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Expr;

class SuperGlobalVisitor extends PhpParser\NodeVisitorAbstract
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
        $obj = new NodeWrapper ($node);

        // check for super globals
        if ($obj->isArrayDimFetch() && !$this->scope->inGlobalSpace())
        {
            $scope = $this->scope->getScopeName();

            $this->data->addIssue ($obj->line, 'super_global', $scope, '$'.$obj->getName());
        }
    }
}
