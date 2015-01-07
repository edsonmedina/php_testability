<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;
use PhpParser\Node\Expr;

class CodeInGlobalSpaceVisitor extends PhpParser\NodeVisitorAbstract
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

    public function enterNode (PhpParser\Node $node) 
    {
        $obj = $this->factory->getNodeWrapper ($node);

        // check for code outside of classes/functions
        if ($this->scope->inGlobalSpace() && !$obj->isAllowedOnGlobalSpace())
        {
            $this->data->addIssue ($obj->line, 'code_on_global_space');
        }
    }
}
