<?php
namespace edsonmedina\php_testability\NodeVisitors;

use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\FinalClassIssue;
use edsonmedina\php_testability\Issues\ExtendedClassIssue;
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
            $this->stack->start (new ClassContext ($node->name, $node->getLine(), $node->getAttribute('endLine')));

            // report final class
            if ($node instanceof Stmt\Class_ && $node->isFinal()) 
            {
                $this->stack->addIssue (new FinalClassIssue($node));
            }

            if (
                !empty($node->extends)
                && !in_array($node->extends->toString(), ['TestCase', 'PHPUnit\\Framework\\TestCase', 'PHPUnit_Framework_TestCase'])
            ) {
                $this->stack->addIssue (new ExtendedClassIssue($node));
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
            $node instanceof Stmt\Trait_
        );
    }
}
