<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Contexts\FunctionContext;
use PhpParser;
use PhpParser\Node\Stmt;

class GlobalFunctionVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Function_) 
        {
            // create new context, keep parent
            $this->stack->start (new FunctionContext ($node->name, $node->getLine(), $node->getAttribute('endLine')));
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Function_) 
        {
            // back to the previous context
            $this->stack->end();
        }
    }
}
