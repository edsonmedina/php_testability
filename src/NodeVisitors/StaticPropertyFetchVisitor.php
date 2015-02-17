<?php
namespace edsonmedina\php_testability\NodeVisitors;

use edsonmedina\php_testability\Contexts\CollectionSpecification;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\StaticPropertyFetchIssue;
use PhpParser;
use PhpParser\Node\Expr;

class StaticPropertyFetchVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for static property fetch from different
        // class (ie OtherClass::$propertyName)
        if ($node instanceof Expr\StaticPropertyFetch) 
        {
            if (!$this->isFetchingFromSelf ($node)) 
            {
                $this->stack->addIssue (new StaticPropertyFetchIssue($node));
            } 
        }
    }

    public function isFetchingFromSelf (Expr\StaticPropertyFetch $node) 
    {
        $parentClass = $this->stack->findContextOfType(new CollectionSpecification);

        if ($parentClass === false) 
        {
            return false;
        }

        $name = end ($node->class->parts);

        return ($name === $parentClass->getName() || $name === 'self');
    }
}
