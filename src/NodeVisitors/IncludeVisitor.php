<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\IncludeIssue;
use edsonmedina\php_testability\Contexts\ProcedureSpecification;
use PhpParser;
use PhpParser\Node\Expr;

class IncludeVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Expr\Include_ && !$this->inGlobalScope()) 
        {
            $parentClass = $this->stack->findContextOfType(new ProcedureSpecification);

            if ($parentClass->getName() !== '__autoload') 
            {
                $this->stack->addIssue (new IncludeIssue($node));
            }
        }
    }
}
