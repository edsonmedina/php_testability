<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\DictionaryInterface;

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
    private $phpInternalFunctions = array ();
    private $dictionary;

    public function __construct (ReportDataInterface $data, DictionaryInterface $dictionary)
    {
        $this->data = $data;
        $this->dictionary = $dictionary;
    }

    public function enterNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        if ($this->muted) {
            return;
        }

        if ($obj->isClass()) {
            $this->currentClass = $obj->getName();
        }

        if ($obj->isMethod()) {
            $this->currentMethod = $obj->getName();
        }

        if ($obj->isFunction()) {
            $this->currentFunction = $obj->getName();
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
                $this->data->addFileIssue ($obj->line, 'code_on_global_space');
                return;
        }

        // check for global variables
        if ($obj->isGlobal()) 
        {
            $scope = $this->getScope('global');

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
            if (!$this->hasReturn && !$this->muted && $obj->hasNoChildren()) {
                $this->data->addIssue ($obj->endLine, 'no_return', $this->getScope('end of method/function'), '');
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
            $this->data->addIssue ($obj->line, 'new', $this->getScope('new'), $obj->getName());
        }

        // check for exit/die statements
        if ($obj->isExit()) {
            $this->data->addIssue ($obj->line, 'exit', $this->getScope('exit'), '');
        }

        // check for static method calls (ie: Things::doStuff())
        if ($obj->isStaticCall()) {
            $this->data->addIssue ($obj->line, 'static_call', $this->getScope('static call'), $obj->getName());
        }

        // check for class constant fetch from different class ($x = OtherClass::thing)
        if ($obj->isClassConstantFetch())
        {
            if (!($this->currentClass && $obj->isSameClassAs($this->currentClass))) {
                $this->data->addIssue ($obj->line, 'external_class_constant_fetch', $this->getScope('external class constant'), $obj->getName());
            } 
        }

        // check for static property fetch from different class ($x = OtherClass::$nameOfThing)
        if ($obj->isStaticPropertyFetch()) 
        {
            if (!($this->currentClass && $obj->isSameClassAs($this->currentClass))) {
                $this->data->addIssue ($obj->line, 'static_property_fetch', $this->getScope('static property'), $obj->getName());
            } 
        }

        // check for global function calls
        if ($obj->isFunctionCall()) 
        {
            $functionName = $obj->getName();

            // skip internal php functions
            if ($this->dictionary->isInternalFunction ($functionName)) {
                return;
            }

            $this->data->addIssue ($obj->line, 'global_function_call', $this->getScope('global function call'), $functionName);
        }
    }

    /**
     * Returns the scope name
     * @param  string $reference optional, is added to error message
     * @return string 
     */
    private function getScope ($reference = '')
    {
        if (!is_null($this->currentFunction)) 
        {
            return $this->currentFunction;
        }
        elseif (!is_null($this->currentClass))
        {
            if (!is_null($this->currentMethod)) {
                return $this->currentClass."::".$this->currentMethod;
            }

            return $this->currentClass;
        }
        else 
        {
            if (!empty($reference)) {
                $reference = '('.$reference.')';
            } 

            throw new \Exception ('Analysys error: Invalid scope '.$reference);
        }
    }

    private function inGlobalSpace()
    {
        return (is_null($this->currentClass) && is_null($this->currentFunction));
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

