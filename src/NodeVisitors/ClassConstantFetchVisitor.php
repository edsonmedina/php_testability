<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\ExternalClassConstantFetchIssue;
use PhpParser;
use PhpParser\Node\Expr;

class ClassConstantFetchVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Expr\ClassConstFetch && !$this->inGlobalScope())
        {
            $obj = $this->factory->getNodeWrapper ($node);
            
            // check for class constant fetch from different class ($x = OtherClass::thing)
            if ($this->scope->insideClass())
            {
                if (!$obj->isSameClassAs($this->scope->getClassName()))
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
