<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node\Expr;

class IncludeVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Expr\Include_ && !$this->scope->inGlobalSpace()) 
        {
            if ($this->scope->getScopeName() !== '__autoload') 
            {
                $includeName = empty($node->expr->value) ? '<expression>' : $node->expr->value;
                $this->data->addIssue ($node->getLine(), 'include', $this->scope->getScopeName(), $includeName);
            }
        }
    }
}
