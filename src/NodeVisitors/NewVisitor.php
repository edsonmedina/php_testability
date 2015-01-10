<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\TraverserFactory;
use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

class NewVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $insideThrow = false;
    private $scope;
    private $factory;
    private $dictionary;

    public function __construct (ReportDataInterface $data, AnalyserScope $scope, TraverserFactory $factory)
    {
        $this->data       = $data;
        $this->scope      = $scope;
        $this->factory    = $factory;
        $this->dictionary = $factory->getDictionary();
    }

    public function enterNode (PhpParser\Node $node) 
    {
        // mute inside throw statements
        if ($node instanceof Stmt\Throw_) 
        {
            $this->insideThrow = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for "new" statement (ie: $x = new Thing())
        if ($node instanceof Expr\New_ && !$this->scope->inGlobalSpace() && !$this->insideThrow) 
        {
            $obj = $this->factory->getNodeWrapper ($node);
            $scopeName = $this->scope->getScopeName();

            if (stripos($scopeName, 'Factory') === FALSE) // do not report for factories
            {
                $name = $obj->getName();

                // only report internal php classes if not safe for
                // instantiation (ie: with external resources)
                if (!$this->dictionary->isClassSafeForInstantiation($name))
                {
                    $this->data->addIssue ($node->getLine(), 'new', $scopeName, $name);
                }
            }
        }

        // unmute
        elseif ($node instanceof Stmt\Throw_) 
        {
            $this->insideThrow = false;
        }
    }
}
