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
        if ($node instanceof Expr\ClassConstFetch && !$this->scope->inGlobalSpace())
        {
            $obj = $this->factory->getNodeWrapper ($node);
            
            // check for class constant fetch from different class ($x = OtherClass::thing)
            if ($this->scope->insideClass())
            {
                if (!$obj->isSameClassAs($this->scope->getClassName()))
                {
                    $this->data->addIssue (new ExternalClassConstantFetchIssue($node), $this->scope);
                } 
            }
            else
            {
                $this->data->addIssue (new ExternalClassConstantFetchIssue($node), $this->scope);
            }
        }
    }
}
