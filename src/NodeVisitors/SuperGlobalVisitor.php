<?php
namespace edsonmedina\php_testability\NodeVisitors;

use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\SuperGlobalAccessIssue;
use PhpParser;
use PhpParser\Node\Expr;

class SuperGlobalVisitor extends VisitorAbstract
{
    private $superGlobalNames = array ('GLOBALS','_SERVER','_GET','_POST','_FILES','_COOKIE','_SESSION','_REQUEST','_ENV');

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for super globals
        if ($node instanceof Expr\ArrayDimFetch && !$this->inGlobalScope())
        {
            if (isset($node->var->name) && in_array ($node->var->name, $this->superGlobalNames))
            {
                $this->stack->addIssue (new SuperGlobalAccessIssue($node));
            }
        }
    }
}
