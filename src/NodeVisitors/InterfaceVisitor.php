<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node\Stmt;

class InterfaceVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Interface_) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $this->scope->startClass ($obj->getName());
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Interface_) 
        {
            $this->scope->endClass();
        }
    }
}
