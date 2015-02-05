<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\EmptyCatchIssue;
use PhpParser;
use PhpParser\Node\Stmt;

class CatchVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for empty catch() statements
        if ($node instanceof Stmt\Catch_ && !$this->scope->inGlobalSpace()) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            if (!$obj->hasChildren())
            {
                $this->data->addIssue (new EmptyCatchIssue ($node), $this->scope);
            }
        }
    }
}
