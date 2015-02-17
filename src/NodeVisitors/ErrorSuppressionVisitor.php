<?php
namespace edsonmedina\php_testability\NodeVisitors;

use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\ErrorSuppressionIssue;
use PhpParser;
use PhpParser\Node\Expr;

class ErrorSuppressionVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Expr\ErrorSuppress && !$this->inGlobalScope()) 
        {
            $this->stack->addIssue (new ErrorSuppressionIssue($node));
        }
    }
}
