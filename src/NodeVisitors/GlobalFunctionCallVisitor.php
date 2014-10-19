<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\DictionaryInterface;

use PhpParser;
use PhpParser\Node\Expr;

class GlobalFunctionCallVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $dictionary;
    private $scope;

    public function __construct (ReportDataInterface $data, DictionaryInterface $dictionary, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
        $this->dictionary = $dictionary;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        // check for global function calls
        if ($obj->isFunctionCall() && !$this->scope->inGlobalSpace()) 
        {
            $functionName = $obj->getName();

            // skip internal php functions
            if ($this->dictionary->isInternalFunction ($functionName)) {
                return;
            }

            $this->data->addIssue ($obj->line, 'global_function_call', $this->scope->getScopeName(), $functionName);
        }
    }
}
