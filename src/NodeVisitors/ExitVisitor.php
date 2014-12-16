<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Expr;

class ExitVisitor extends PhpParser\NodeVisitorAbstract
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
        // check for exit/die statements
        if ($node instanceof Expr\Exit_ && !$this->scope->inGlobalSpace()) 
        {
            $obj = new NodeWrapper ($node);
            $this->data->addIssue ($obj->line, 'exit', $this->scope->getScopeName(), '');
        }
    }
}
