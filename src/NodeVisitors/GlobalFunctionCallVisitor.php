<?php
namespace edsonmedina\php_testability\NodeVisitors;

use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\Dictionary;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\GlobalFunctionCallIssue;
use PhpParser;
use PhpParser\Node\Expr;

class GlobalFunctionCallVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for global function calls
        if ($node instanceof Expr\FuncCall && !$this->inGlobalScope()) 
        {
            $dictionary = new Dictionary;

            $obj = new NodeWrapper ($node);
            $functionName = $obj->getName();

            // skip internal php functions
            if ($dictionary->isInternalFunction ($functionName)) {
                return;
            }

            $this->stack->addIssue (new GlobalFunctionCallIssue($node));
        }
    }
}
