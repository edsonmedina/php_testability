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
        $this->data    = $data;
        $this->scope   = $scope;
        $this->factory = $factory;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for static property fetch from different class ($x = OtherClass::$nameOfThing)
        if ($node instanceof Expr\StaticPropertyFetch) 
        {
            if (!$this->isFetchingFromSelf ($node)) 
            {
                $obj = $this->factory->getNodeWrapper ($node);
                $this->data->addIssue ($node->getLine(), 'static_property_fetch', $this->scope->getScopeName(), $obj->getName());
            } 
        }
    }

    public function isFetchingFromSelf ($node) 
    {
        if (!$this->scope->insideClass()) 
        {
            return false;
        }

        $name = end ($node->class->parts);

        return ($name === $this->scope->getClassName() || $name === 'self');
    }
}
