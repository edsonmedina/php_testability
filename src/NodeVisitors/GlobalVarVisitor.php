<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Stmt;

class GlobalVarVisitor extends PhpParser\NodeVisitorAbstract
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
        // check for global variables
        if ($node instanceof Stmt\Global_ && !$this->scope->inGlobalSpace()) 
        {
            $obj = new NodeWrapper ($node);
            $scope = $this->scope->getScopeName();

            foreach ($obj->getVarList() as $var) {
                $this->data->addIssue ($var->getLine(), 'global', $scope, '$'.$var->name);
            }
        }
    }
}
