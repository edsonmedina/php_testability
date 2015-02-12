<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\PrivateMethodIssue;
use edsonmedina\php_testability\Issues\ProtectedMethodIssue;
use edsonmedina\php_testability\Issues\FinalMethodIssue;
use edsonmedina\php_testability\Contexts\MethodContext;
use PhpParser;
use PhpParser\Node\Stmt;

class MethodVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\ClassMethod) 
        {
            // create new context, keep parent
            $this->stack->start (new MethodContext ($node->name, $node->getLine(), $node->getAttribute('endLine')));

            // report non public methods
            if ($node->isPrivate()) 
            {
                $this->stack->addIssue (new PrivateMethodIssue($node));
            }
            elseif ($node->isProtected()) 
            {
                $this->stack->addIssue (new ProtectedMethodIssue($node));
            }

            // report final methods
            if ($node->isFinal()) 
            {
                $this->stack->addIssue (new FinalMethodIssue($node));
            }
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\ClassMethod) 
        {
            // back to the previous context
            $this->stack->end();
        }
    }
}
