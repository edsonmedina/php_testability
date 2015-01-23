<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;
use PhpParser\Node\Expr;

class StaticCallVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;
    private $factory;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
        $this->dictionary = $factory->getDictionary();
    }

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
            if (!$this->dictionary->isClassSafeForInstantiation($className))
            {
                $this->data->addIssue ($node->getLine(), 'static_call', $this->scope->getScopeName(), $obj->getName());
            }
        }
    }
}
