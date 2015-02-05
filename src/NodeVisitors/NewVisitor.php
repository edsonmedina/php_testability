<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\NewInstanceIssue;
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
        if ($node instanceof Expr\New_ && !$this->scope->inGlobalSpace() && !$this->insideThrow) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $scopeName = $this->scope->getScopeName();

            if (stripos($scopeName, 'Factory') === FALSE) // do not report for factories
            {
                $name = $obj->getName();

                $dictionary = $this->factory->getDictionary();

                // only report internal php classes if not safe for
                // instantiation (ie: with external resources)
                if (!$dictionary->isClassSafeForInstantiation($name))
                {
                    $this->data->addIssue (new NewInstanceIssue($node), $this->scope);
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
