<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;

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
        if ($this->muted) {
            return;
        }

        if ($node instanceof Stmt\Class_) {
            $this->currentClass = $node->name;
        }

        if ($node instanceof Stmt\ClassMethod) {
            $this->currentMethod = $node->name;
        }

        if ($node instanceof Stmt\Function_) {
            $this->currentFunction = $node->name;
        }

        if ($node instanceof Stmt\Return_) {
            $this->hasReturn = true;
        }

        if ($node instanceof Stmt\Interface_) {
            $this->muted = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        // check for global variables
        if ($node instanceof Stmt\Global_) 
        {
            $scope = $this->getScope();

            foreach ($node->vars as $var) {
                $this->data->addIssue ($var->getLine(), 'global', $scope, $var->name);
            }
        }

        // end of class
        if ($node instanceof Stmt\Class_) {
            $this->currentClass = null;
        }

        // end of method or global function
        if ($node instanceof Stmt\ClassMethod || $node instanceof Stmt\Function_) 
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
        if ($node instanceof Stmt\Interface_) {
            $this->muted = false;
        }

        // check for "new" statement
        if ($node instanceof Expr\New_) 
        {
            $this->data->addIssue ($node->getLine(), 'new', $this->getScope(), join('\\', $node->class->parts));
        }

        // check for exit/die statements
        if ($node instanceof Expr\Exit_) 
        {
            $this->data->addIssue ($node->getLine(), 'exit', $this->getScope(), '');
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
}

// Expr\Closure
// Expr\Eval_
// Expr\ErrorSuppress  (@)
// Expr\FuncCall
// Expr\StaticCall
// Expr\StaticPropertyFetch
// Stmt\InlineHTML
// 
// conditions:
// Stmt\If_ 
// Stmt\Else_
// Stmt\Case
// Stmt\ElseIf_

// look for New instances, static method calls, global function calls, code outside of functions/methods
//
// number of conditions (case, if, elseif, else, ?:, )
// 
// exit / die
// require/include/require_once/include_once, 
// echo, print, print_r/var_dump,
// mail, file_get_contents, file_put_contents,
// soap, 
//   
// these can only happen in a method with no user-code dependencies

