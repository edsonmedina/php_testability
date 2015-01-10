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
            if (!$this->isAllowedOnGlobalSpace($node))
            {
                $this->data->addIssue ($node->getLine(), 'code_on_global_space');
            }
        }
    }

    /**
     * Is node allowed on global space?
     * @param PhpParser\Node $node
     * @return bool
     */
    public function isAllowedOnGlobalSpace ($node) 
    {
        return (
                $node instanceof Stmt\Class_
                || $node instanceof Stmt\Trait_ 
                || $node instanceof Stmt\Function_
                || ($node instanceof Stmt\UseUse || $node instanceof Stmt\Use_)
                || ($node instanceof Stmt\Namespace_ || $node instanceof Node\Name)
                || $node instanceof Stmt\Interface_
            );
    }
}
