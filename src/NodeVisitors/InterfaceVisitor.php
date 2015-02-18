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
            return PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
    }
}
