<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node\Expr;

class GlobalFunctionCallVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for global function calls
        if ($node instanceof Expr\FuncCall && !$this->scope->inGlobalSpace()) 
        {
            $dictionary = $this->factory->getDictionary();

            $obj = $this->factory->getNodeWrapper ($node);
            $functionName = $obj->getName();

            // skip internal php functions
            if ($dictionary->isInternalFunction ($functionName)) {
                return;
            }

            $this->data->addIssue ($node->getLine(), 'global_function_call', $this->scope->getScopeName(), $functionName);
        }
    }
}
