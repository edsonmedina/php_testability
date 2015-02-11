<?php
namespace edsonmedina\php_testability;
use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\AnalyserAbstractFactory;
use edsonmedina\php_testability\ContextInterface;
use edsonmedina\php_testability\ContextStack;
use PhpParser;

abstract class VisitorAbstract extends PhpParser\NodeVisitorAbstract
{
    protected $stack;
    protected $context;

    public function __construct (ContextStack $stack, ContextInterface $context)
    {
        $this->stack    = $stack;
        $this->context  = $context;
    }
}
