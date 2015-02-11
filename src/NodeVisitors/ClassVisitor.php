<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\FinalClassIssue;
use edsonmedina\php_testability\Contexts\ClassContext;
use PhpParser;
use PhpParser\Node\Stmt;

class ClassVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($this->isClass($node)) 
        {
            // create new context, keep parent
            $this->stack->start (new ClassContext ($node->name));

            // report final class
            if ($node instanceof Stmt\Class_ && $node->isFinal()) 
            {
                $this->context->addIssue (new FinalClassIssue($node));
            }
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($this->isClass($node)) 
        {
            // back to the previous context
            $this->stack->end();
        }
    }

    public function isClass ($node)
    {
        return (
            $node instanceof Stmt\Class_ || 
            $node instanceof Stmt\Interface_ || 
            $node instanceof Stmt\Trait_
        );
    }
}
