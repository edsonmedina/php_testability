<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Expr;

class ClassConstantFetchVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        if ($obj->isClassConstantFetch() && !$this->scope->inGlobalSpace())
        {
            // check for class constant fetch from different class ($x = OtherClass::thing)
            if ($this->scope->insideClass())
            {
                if (!$obj->isSameClassAs($this->scope->getClassName()))
                {
                    $this->data->addIssue ($obj->line, 'external_class_constant_fetch', $this->scope->getScopeName(), $obj->getName());
                } 
            }
            else
            {
                $this->data->addIssue ($obj->line, 'external_class_constant_fetch', $this->scope->getScopeName(), $obj->getName());
            }
        }
    }
}
