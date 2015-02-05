<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use edsonmedina\php_testability\Issues\StaticMethodCallIssue;
use PhpParser;
use PhpParser\Node\Expr;

class StaticCallVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        // check for static method calls (ie: Things::doStuff())
        if ($node instanceof Expr\StaticCall && !$this->scope->inGlobalSpace()) 
        {
            $obj = $this->factory->getNodeWrapper ($node);

            $name = $obj->getName();
            list ($className) = explode('::', $name);

            // only report static method calls for php classes that are 
            // not safe for instantiation (ie: with external resources)
            $dictionary = $this->factory->getDictionary();
            
            if (!$dictionary->isClassSafeForInstantiation($className))
            {
                $this->data->addIssue (new StaticMethodCallIssue($node), $this->scope);
            }
        }
    }
}
