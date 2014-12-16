<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

class NewVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $insideThrow = false;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Throw_) 
        {
            $this->insideThrow = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for "new" statement (ie: $x = new Thing())
        if ($node instanceof Expr\New_ && !$this->scope->inGlobalSpace() && !$this->insideThrow) 
        {
            $obj = new NodeWrapper ($node);
            $this->data->addIssue ($obj->line, 'new', $this->scope->getScopeName(), $obj->getName());
        }

        elseif ($node instanceof Stmt\Throw_) 
        {
            $this->insideThrow = false;
        }
    }
}
