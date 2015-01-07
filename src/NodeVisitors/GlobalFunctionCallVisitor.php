<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;

use PhpParser;
use PhpParser\Node\Expr;

class GlobalFunctionCallVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $dictionary;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
        $this->dictionary = $factory->getDictionary();
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for global function calls
        if ($node instanceof Expr\FuncCall && !$this->scope->inGlobalSpace()) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $functionName = $obj->getName();

            // skip internal php functions
            if ($this->dictionary->isInternalFunction ($functionName)) {
                return;
            }

            $this->data->addIssue ($obj->line, 'global_function_call', $this->scope->getScopeName(), $functionName);
        }
    }
}
