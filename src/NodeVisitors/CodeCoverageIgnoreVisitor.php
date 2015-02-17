<?php
namespace edsonmedina\php_testability\NodeVisitors;

use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;

class CodeCoverageIgnoreVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        if (stripos ($node->getDocComment(), '@codeCoverageIgnore') !== FALSE)
        {
            return PhpParser\NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }
    }
}
