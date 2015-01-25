<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node\Stmt;

class CatchVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for empty catch() statements
        if ($node instanceof Stmt\Catch_ && !$this->scope->inGlobalSpace()) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            if (!$obj->hasChildren())
            {
                $this->data->addIssue ($node->getLine(), 'empty_catch', $this->scope->getScopeName(), '');
            }
        }
    }
}
