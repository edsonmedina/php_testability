<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\PrivateMethodIssue;
use edsonmedina\php_testability\Issues\ProtectedMethodIssue;
use edsonmedina\php_testability\Issues\FinalMethodIssue;
use PhpParser;
use PhpParser\Node\Stmt;

class MethodVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\ClassMethod) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->scope->startMethod ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $node->getLine());

            // report non public methods
            if ($node->isPrivate()) 
            {
                $this->data->addIssue (new PrivateMethodIssue($node), $this->scope->getScopeName());
            }
            elseif ($node->isProtected()) 
            {
                $this->data->addIssue (new ProtectedMethodIssue($node), $this->scope->getScopeName());
            }

            // report final methods
            if ($node->isFinal()) 
            {
                $this->data->addIssue (new FinalMethodIssue($node), $this->scope->getScopeName());
            }
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // end of method or global function
        if ($node instanceof Stmt\ClassMethod) 
        {
            $this->scope->endMethod();
        }
    }
}
