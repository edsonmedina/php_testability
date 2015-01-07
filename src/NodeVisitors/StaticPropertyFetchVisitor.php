<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;

use PhpParser;
use PhpParser\Node\Expr;

class StaticPropertyFetchVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;
    private $factory;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for static property fetch from different class ($x = OtherClass::$nameOfThing)
        if ($node instanceof Expr\StaticPropertyFetch) 
        {
            $obj = $this->factory->getNodeWrapper ($node);

            if (!($this->scope->insideClass() && $obj->isSameClassAs($this->scope->getClassName()))) 
            {
                $this->data->addIssue ($obj->line, 'static_property_fetch', $this->scope->getScopeName(), $obj->getName());
            } 
        }
    }
}
