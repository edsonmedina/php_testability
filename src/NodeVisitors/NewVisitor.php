<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node;

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
        $obj = new NodeWrapper ($node);

        if ($obj->isThrow()) 
        {
            $this->insideThrow = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        // check for "new" statement (ie: $x = new Thing())
        if ($obj->isNew() && !$this->scope->inGlobalSpace() && !$this->insideThrow) 
        {
            $this->data->addIssue ($obj->line, 'new', $this->scope->getScopeName(), $obj->getName());
        }

        elseif ($obj->isThrow()) 
        {
            $this->insideThrow = false;
        }
    }
}
