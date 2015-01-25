<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Stmt;

class CodeInGlobalSpaceVisitor extends VisitorAbstract
{
    public function enterNode (PhpParser\Node $node) 
    {
        // check for code outside of classes/functions
        if ($this->scope->inGlobalSpace())
        {
            if (!$this->isAllowedOnGlobalSpace($node))
            {
                $this->data->addIssue ($node->getLine(), 'code_on_global_space');
            }
        }
    }

    /**
     * Is node allowed on global space?
     * @param PhpParser\Node $node
     * @return bool
     */
    public function isAllowedOnGlobalSpace (PhpParser\Node $node) 
    {
        return (
                $node instanceof Stmt\Class_
                || $node instanceof Stmt\Trait_ 
                || $node instanceof Stmt\Function_
                || ($node instanceof Stmt\UseUse || $node instanceof Stmt\Use_)
                || ($node instanceof Stmt\Namespace_ || $node instanceof Node\Name)
                || $node instanceof Stmt\Interface_
            );
    }
}
