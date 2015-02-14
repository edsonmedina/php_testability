<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\Contexts\CollectionSpecification;
use edsonmedina\php_testability\Issues\ExternalClassConstantFetchIssue;
use PhpParser;
use PhpParser\Node\Expr;

class ClassConstantFetchVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Expr\ClassConstFetch && !$this->inGlobalScope())
        {
            $parentClass = $this->stack->findContextOfType(new CollectionSpecification);

            $obj = new NodeWrapper ($node);
            
            // check for class constant fetch from different class ($x = OtherClass::thing)
            if ($parentClass !== false)
            {
                if (!$obj->isSameClassAs($parentClass->getName()))
                {
                    $this->stack->addIssue (new ExternalClassConstantFetchIssue($node));
                } 
            }
            else
            {
                $this->stack->addIssue (new ExternalClassConstantFetchIssue($node));
            }
        }
    }
}
