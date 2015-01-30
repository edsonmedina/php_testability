<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
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
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Class_) 
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
