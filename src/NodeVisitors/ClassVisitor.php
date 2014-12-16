<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;

use PhpParser;
use PhpParser\Node\Stmt;

class ClassVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Class_) 
        {
            $obj = new NodeWrapper ($node);
            $this->scope->startClass ($obj->getName());
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Class_) 
        {
            $this->scope->endClass();
        }
    }
}
