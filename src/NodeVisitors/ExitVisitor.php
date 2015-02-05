<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\ExitIssue;
use PhpParser;
use PhpParser\Node\Expr;

class ExitVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for exit/die statements
        if ($node instanceof Expr\Exit_ && !$this->scope->inGlobalSpace()) 
        {
            $this->data->addIssue (new ExitIssue($node), $this->scope);
        }
    }
}
