<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\FinalClassIssue;
use PhpParser;
use PhpParser\Node\Stmt;

class ClassVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($this->isClass($node)) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->scope->startClass ($obj->getName());

            // report final class
            if ($node instanceof Stmt\Class_ && $node->isFinal()) 
            {
                $this->data->addIssue (new FinalClassIssue($node), $this->scope->getScopeName());
            }
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($this->isClass($node)) 
        {
            $this->scope->endClass();
        }
    }

    public function isClass ($node)
    {
        return (
            $node instanceof Stmt\Class_ || 
            $node instanceof Stmt\Interface_ || 
            $node instanceof Stmt\Trait_
        );
    }
}
