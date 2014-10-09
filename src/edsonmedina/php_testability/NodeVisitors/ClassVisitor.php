<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;

use PhpParser;
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

        // end of method
        if ($node instanceof Stmt\ClassMethod) 
        {
            if (!$this->hasReturn && !$this->muted && $node->stmts) {
                $this->data->addIssue ($node->getLine(), 'no_return', $this->getScope(), '');
            }

            $this->currentMethod = null;
            $this->hasReturn = false;
        }

        // end of global function
        if ($node instanceof Stmt\Function_) 
        {
            if (!$this->hasReturn && !$this->muted) {
                $this->data->addIssue ($node->getLine(), 'no_return', $this->getScope(), '');
            }

            $this->currentFunction = null;
            $this->hasReturn = false;
        }

        // end of interface
        if ($node instanceof Stmt\Interface_) {
            $this->muted = false;
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

// Stmt\Class_
// Stmt\ClassMethod
// Stmt\Return_
// Stmt\Global_ 

// look for New instances, globals, return, static method calls, global function calls
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

