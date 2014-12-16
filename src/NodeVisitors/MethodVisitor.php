<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Stmt;

class MethodVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\ClassMethod) 
        {
            $obj = new NodeWrapper ($node);
            $this->scope->startMethod ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $obj->line);

            // report non public methods
            if ($node->isPrivate()) 
            {
                $this->data->addIssue ($obj->line, 'private_method', $this->scope->getScopeName(), $obj->getName());
            }
            elseif ($node->isProtected()) 
            {
                $this->data->addIssue ($obj->line, 'protected_method', $this->scope->getScopeName(), $obj->getName());
            }
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // end of method or global function
        if ($node instanceof Stmt\ClassMethod) 
        {
            $this->scope->endMethod();
        }
    }
}
