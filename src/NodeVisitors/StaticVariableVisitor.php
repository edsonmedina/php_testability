<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\StaticVariableDeclarationIssue;
use PhpParser;
use PhpParser\Node\Stmt;

class StaticVariableVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Static_) 
        {
            $this->stack->addIssue (new StaticVariableDeclarationIssue($node));
        }
    }
}
