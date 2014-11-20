<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Expr;

class MethodVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $hasReturn = false;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        if ($obj->isMethod()) 
        {
            $this->hasReturn = false;
            $this->scope->startMethod ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $obj->line);
        }
        elseif ($obj->isReturn()) 
        {
            $this->hasReturn = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        // end of method or global function
        if ($obj->isMethod()) 
        {
            // check for a lacking return statement in the method/function
            if ($obj->hasChildren() && !$this->hasReturn) 
            {
                $ignoreList = array ('__construct', '__clone', '__set', '__unset', '__wakeup', '__invoke');

                if (!in_array ($obj->getName(), $ignoreList)) 
                {
                    $this->data->addIssue ($obj->endLine, 'no_return', $this->scope->getScopeName(), '');
                }
            }
            
            $this->scope->endMethod();
            $this->hasReturn = false;
        }
    }
}
