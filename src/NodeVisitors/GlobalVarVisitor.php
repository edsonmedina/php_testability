<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\GlobalVariableIssue;
use PhpParser;
use PhpParser\Node\Stmt;

class GlobalVarVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for global variables
        if ($node instanceof Stmt\Global_ && !$this->scope->inGlobalSpace()) 
        {
            $this->data->addIssue (new GlobalVariableIssue($node), $this->scope);
        }
    }
}
