<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;

class CodeInGlobalSpaceVisitor extends PhpParser\NodeVisitorAbstract
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

    public function enterNode (PhpParser\Node $node) 
    {
        // check for code outside of classes/functions
        if ($this->scope->inGlobalSpace())
        {
            $obj = $this->factory->getNodeWrapper ($node);

            if (!$obj->isAllowedOnGlobalSpace())
            {
                $this->data->addIssue ($node->getLine(), 'code_on_global_space');
            }
        }
    }
}
