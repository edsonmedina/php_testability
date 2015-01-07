<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;
use PhpParser\Node\Expr;

class IncludeVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;
    private $factory;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Expr\Include_ && !$this->scope->inGlobalSpace()) 
        {
            if ($this->scope->getScopeName() !== '__autoload') 
            {
                $obj = $this->factory->getNodeWrapper ($node);
                $this->data->addIssue ($obj->line, 'include', $this->scope->getScopeName(), $node->expr->value);
            }
        }
    }
}
