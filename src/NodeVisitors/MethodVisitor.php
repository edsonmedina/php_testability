<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\PrivateMethodIssue;
use edsonmedina\php_testability\Issues\ProtectedMethodIssue;
use edsonmedina\php_testability\Issues\FinalMethodIssue;
use edsonmedina\php_testability\Contexts\MethodContext;
use PhpParser;
use PhpParser\Node\Stmt;

class MethodVisitor extends VisitorAbstract
{
    protected $class;

    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\ClassMethod) 
        {
            // create new context, keep parent
            $this->stack->start (new MethodContext ($node->name, 1, 2));

            // $obj = $this->factory->getNodeWrapper ($node);
            // $this->scope->startMethod ($obj->getName());
            // $this->data->saveScopePosition ($this->scope->getScopeName(), $node->getLine());

            // // report non public methods
            // if ($node->isPrivate()) 
            // {
            //     $this->data->addIssue (new PrivateMethodIssue($node), $this->scope);
            // }
            // elseif ($node->isProtected()) 
            // {
            //     $this->data->addIssue (new ProtectedMethodIssue($node), $this->scope);
            // }

            // // report final methods
            // if ($node->isFinal()) 
            // {
            //     $this->data->addIssue (new FinalMethodIssue($node), $this->scope);
            // }
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // end of method or global function
        if ($node instanceof Stmt\ClassMethod) 
        {
            // back to the previous context
            $this->stack->end();

        //     $this->scope->endMethod();
        }
    }
}
