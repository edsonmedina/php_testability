<?php
namespace edsonmedina\php_testability;
use edsonmedina\php_testability\ReportData;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\AnalyserAbstractFactory;
use PhpParser;

abstract class VisitorAbstract extends PhpParser\NodeVisitorAbstract
{
    protected $data;
    protected $scope;
    protected $factory;

    public function __construct (ReportData $data, AnalyserScope $scope, AnalyserAbstractFactory $factory)
    {
        $this->data    = $data;
        $this->scope   = $scope;
        $this->factory = $factory;
    }
}
