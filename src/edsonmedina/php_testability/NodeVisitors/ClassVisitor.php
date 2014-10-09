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

    public function __construct (ReportDataInterface $data)
    {
        $this->data = $data;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Class_) {
            $this->currentClass = $node->name;
        }

        if ($node instanceof Stmt\ClassMethod) {
            $this->currentMethod = $node->name;
        }

        if ($node instanceof Stmt\Function_) {
            $this->currentFunction = $node->name;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        if ($node instanceof Stmt\Global_) {
            // print_r ($node->vars);
            echo $this->currentClass."::".$this->currentMethod."\n";
            print_r ($node->vars);
        }

        if ($node instanceof Stmt\Class_) {
            $this->currentClass = null;
        }

        if ($node instanceof Stmt\ClassMethod) {
            $this->currentMethod = null;
        }

        if ($node instanceof Stmt\Function_) {
            $this->currentFunction = null;
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

