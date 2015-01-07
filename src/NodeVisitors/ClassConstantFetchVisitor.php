<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;

use PhpParser;
use PhpParser\Node\Expr;

class ClassConstantFetchVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Expr\ClassConstFetch && !$this->scope->inGlobalSpace())
        {
            $obj = $this->factory->getNodeWrapper ($node);
            
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
