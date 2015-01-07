<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;
use PhpParser\Node\Stmt;

class GlobalVarVisitor extends PhpParser\NodeVisitorAbstract
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
        // check for global variables
        if ($node instanceof Stmt\Global_ && !$this->scope->inGlobalSpace()) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $scope = $this->scope->getScopeName();

            foreach ($obj->getVarList() as $var) {
                $this->data->addIssue ($var->getLine(), 'global', $scope, '$'.$var->name);
            }
        }
    }
}
