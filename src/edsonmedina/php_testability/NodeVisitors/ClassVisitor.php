<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;

use PhpParser;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

class ClassVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $currentClass    = null;
    private $currentMethod   = null;
    private $currentFunction = null;
    private $hasReturn = false;
    private $muted = false;

    public function __construct (ReportDataInterface $data)
    {
        $this->data = $data;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        if ($this->muted) {
            return;
        }

        if ($obj->isClass()) {
            $this->currentClass = $node->name;
        }

        if ($obj->isMethod()) {
            $this->currentMethod = $node->name;
        }

        if ($obj->isFunction()) {
            $this->currentFunction = $node->name;
        }

        if ($obj->isReturn()) {
            $this->hasReturn = true;
        }

        if ($obj->isInterface()) {
            $this->muted = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        // check for code outside of classes/functions
        if (!($obj->isClass() || $obj->isFunction()) && $this->inGlobalSpace())
        {
                $this->data->addIssue ($obj->line, 'code_on_global_space', '__main', '');
                return;
        }

        // check for global variables
        if ($obj->isGlobal()) 
        {
            $scope = $this->getScope();

            foreach ($obj->getVarList() as $var) {
                $this->data->addIssue ($var->getLine(), 'global', $scope, $var->name);
            }
        }

        // end of class
        if ($obj->isClass()) {
            $this->currentClass = null;
        }

        // end of method or global function
        if ($obj->isMethod() || $obj->isFunction()) 
        {
            // check for a lacking return statement in the method/function
            if (!$this->hasReturn && !$this->muted && $node->stmts) {
                $this->data->addIssue ($node->getAttribute('endLine'), 'no_return', $this->getScope(), '');
            }

            $this->currentMethod = null;
            $this->currentFunction = null;
            $this->hasReturn = false;
        }

        // end of interface
        if ($obj->isInterface()) {
            $this->muted = false;
        }

        // check for "new" statement (ie: $x = new Thing())
        if ($obj->isNew()) {
            $this->data->addIssue ($node->getLine(), 'new', $this->getScope(), join('\\', $node->class->parts));
        }

        // check for exit/die statements
        if ($node instanceof Expr\Exit_) {
            $this->data->addIssue ($node->getLine(), 'exit', $this->getScope(), '');
        }

        // check for static method calls (ie: Things::doStuff())
        if ($node instanceof Expr\StaticCall) {
            $this->data->addIssue ($node->getLine(), 'static_call', $this->getScope(), join('\\', $node->class->parts).'::'.$node->name);
        }

        // check for class constant fetch from different class ($x = OtherClass::thing)
        if ($node instanceof Expr\ClassConstFetch) 
        {
            if (!($this->currentClass && end($node->class->parts) == $this->currentClass)) {
                $this->data->addIssue ($node->getLine(), 'external_class_constant_fetch', $this->getScope(), join('\\', $node->class->parts).'::'.$node->name);
            } 
        }

        // check for static property fetch from different class ($x = OtherClass::$nameOfThing)
        if ($node instanceof Expr\StaticPropertyFetch) 
        {
            if (!($this->currentClass && end($node->class->parts) == $this->currentClass)) {
                $this->data->addIssue ($node->getLine(), 'static_property_fetch', $this->getScope(), join('\\', $node->class->parts).'::'.$node->name);
            } 
        }

        // check for global function calls
        if ($node instanceof Expr\FuncCall) {
            $this->data->addIssue ($node->getLine(), 'global_function_call', $this->getScope(), join('\\', $node->name->parts));
        }
    }

    private function getScope ()
    {
        if (!is_null($this->currentFunction)) 
        {
            return $this->currentFunction;
        }
        elseif (!is_null($this->currentClass) && !is_null($this->currentMethod)) 
        {
            return $this->currentClass."::".$this->currentMethod;
        }
        else 
        {
            throw new \Exception ('Invalid scope');
        }
    }

    private function inGlobalSpace()
    {
        return !($this->currentClass || $this->currentFunction);
    }
}

// Expr\Closure
// Expr\Eval_
// Expr\ErrorSuppress  (@)
// Expr\StaticPropertyFetch
// Stmt\InlineHTML
// 
// conditions:
// Stmt\If_   
// Stmt\Else_
// Stmt\Case
// Stmt\ElseIf_
// (also test for OR and ||)

// number of conditions (case, if, elseif, else, ?:, )
// 
// require/include/require_once/include_once, 
// mail, file_get_contents, file_put_contents, fopen, fgets, sockets 
// soap, 
//   
// these can only happen in a method with no user-code dependencies

