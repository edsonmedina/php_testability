<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;
use PhpParser\Node\Expr;

class SuperGlobalVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $scope;
    private $factory;
    private $_list = array ('GLOBALS','_SERVER','_GET','_POST','_FILES','_COOKIE','_SESSION','_REQUEST','_ENV');

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for super globals
        if ($node instanceof Expr\ArrayDimFetch && !$this->scope->inGlobalSpace())
        {
            $scope = $this->scope->getScopeName();

            if ($node->var->name)
            {
                if (in_array ($node->var->name, $this->_list))
                {
                    $obj = $this->factory->getNodeWrapper ($node);
                    $this->data->addIssue ($obj->line, 'super_global', $scope, '$'.$node->var->name);
                }
            }
        }
    }
}
