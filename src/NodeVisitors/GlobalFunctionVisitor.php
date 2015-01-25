<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node\Stmt;

class GlobalFunctionVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Function_) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->scope->startFunction ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $node->getLine());
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // end of method or global function
        if ($node instanceof Stmt\Function_) 
        {
            $this->scope->endFunction();
        }
    }
}
