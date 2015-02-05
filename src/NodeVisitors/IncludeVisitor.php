<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\IncludeIssue;
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
                $this->data->addIssue (new IncludeIssue($node), $this->scope);
            }
        }
    }
}
