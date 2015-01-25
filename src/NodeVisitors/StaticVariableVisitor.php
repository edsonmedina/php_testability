<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\VisitorAbstract;
use PhpParser;
use PhpParser\Node\Stmt;

class StaticVariableVisitor extends VisitorAbstract
{
    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Static_) 
        {
            $obj = $this->factory->getNodeWrapper ($node);

            foreach ($obj->getVarList() as $var) {
                $this->data->addIssue ($node->getLine(), 'static_var', $this->scope->getScopeName(), '$'.$var->name);
            }
        }
    }
}
