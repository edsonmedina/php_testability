<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\NewInstanceIssue;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\Dictionary;
use edsonmedina\php_testability\Contexts\CollectionSpecification;
use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

class NewVisitor extends VisitorAbstract
{
    private $insideThrow = false;

    public function enterNode (PhpParser\Node $node) 
    {
        // mute inside throw statements
        if ($node instanceof Stmt\Throw_) 
        {
            $this->insideThrow = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for "new" statement (ie: $x = new Thing())
        if ($node instanceof Expr\New_ && !$this->inGlobalScope() && !$this->insideThrow) 
        {
            $parentClass = $this->stack->findContextOfType(new CollectionSpecification);

            if ($parentClass === false || stripos($parentClass->getName(), 'Factory') === FALSE) // do not report for factories
            {
                $dictionary = new Dictionary;

                $obj = new NodeWrapper ($node);

                // only report internal php classes if not safe for
                // instantiation (ie: with external resources)
                if (!$dictionary->isClassSafeForInstantiation($obj->getName()))
                {
                    $this->stack->addIssue (new NewInstanceIssue($node));
                }
            }
        }

        // unmute
        elseif ($node instanceof Stmt\Throw_) 
        {
            $this->insideThrow = false;
        }
    }
}
