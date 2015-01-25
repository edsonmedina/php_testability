<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node\Stmt;

class TraitVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Trait_) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->scope->startClass ($obj->getName());
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Trait_) 
        {
            $this->scope->endClass();
        }
    }
}
