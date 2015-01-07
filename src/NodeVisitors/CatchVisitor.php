<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;

use PhpParser;
use PhpParser\Node\Stmt;

class CatchVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for empty catch() statements
        if ($node instanceof Stmt\Catch_ && !$this->scope->inGlobalSpace() && empty($node->stmts)) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->data->addIssue ($obj->line, 'empty_catch', $this->scope->getScopeName(), '');
        }
    }
}
