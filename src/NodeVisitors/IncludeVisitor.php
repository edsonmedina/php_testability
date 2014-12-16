<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Expr;

class IncludeVisitor extends PhpParser\NodeVisitorAbstract
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
        if ($node instanceof Expr\Include_ && !$this->scope->inGlobalSpace()) 
        {
            if ($this->scope->getScopeName() !== '__autoload') 
            {
                $obj = new NodeWrapper ($node);
                $this->data->addIssue ($obj->line, 'include', $this->scope->getScopeName(), $node->expr->value);
            }
        }
    }
}
