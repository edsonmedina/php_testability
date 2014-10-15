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
    private $currentTrait    = null;
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

        if ($this->muted) 
        {
            return;
        }
        elseif ($obj->isClass()) 
        {
            $this->currentClass = $obj->getName();
        }
        elseif ($obj->isTrait()) 
        {
            $this->currentTrait = $obj->getName();
        }
        elseif ($obj->isMethod()) 
        {
            $this->currentMethod = $obj->getName();
            $this->data->saveScopePosition ($this->getScope('start of method '.$this->currentMethod), $obj->line);
        }
        elseif ($obj->isFunction()) 
        {
            $this->currentFunction = $obj->getName();
            $this->data->saveScopePosition ($this->getScope('start of function '.$this->currentFunction), $obj->line);
        }
        elseif ($obj->isReturn()) 
        {
            $this->hasReturn = true;
        }
        elseif ($obj->isInterface()) 
        {
            $this->muted = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        // check for code outside of classes/functions
        if ($this->inGlobalSpace() && !$obj->isAllowedOnGlobalSpace())
        {
                $this->data->addIssue ($obj->line, 'code_on_global_space');
                return;
        }

        // check for global variables
        elseif ($obj->isGlobal()) 
        {
            $scope = $this->getScope('global');

            foreach ($obj->getVarList() as $var) {
                $this->data->addIssue ($var->getLine(), 'global', $scope, $var->name);
            }
        }

        // end of class
        elseif ($obj->isClass()) {
            $this->currentClass = null;
        }

        // end of trait
        elseif ($obj->isTrait()) {
            $this->currentTrait = null;
        }

        // end of method or global function
        elseif ($obj->isMethod() || $obj->isFunction()) 
        {
            // check for a lacking return statement in the method/function
            if ($obj->hasChildren() && !$this->hasReturn && !$this->muted) 
            {
                if ($obj->getName() !== '__construct') {
                    $this->data->addIssue ($obj->endLine, 'no_return', $this->getScope('end of method/function'), '');
                }
            }
            
            if ($obj->isFunction()) {
                $this->currentFunction = null;
            } else {
                $this->currentMethod = null;
            }
            
            $this->hasReturn = false;
        }

        // end of interface
        elseif ($obj->isInterface()) {
            $this->muted = false;
        }

        // check for "new" statement (ie: $x = new Thing())
        elseif ($obj->isNew()) {
            $this->data->addIssue ($obj->line, 'new', $this->getScope('new'), $obj->getName());
        }

        // check for exit/die statements
        elseif ($obj->isExit()) {
            $this->data->addIssue ($obj->line, 'exit', $this->getScope('exit'), '');
        }

        // check for static method calls (ie: Things::doStuff())
        elseif ($obj->isStaticCall()) {
            $this->data->addIssue ($obj->line, 'static_call', $this->getScope('static call'), $obj->getName());
        }

        // check for class constant fetch from different class ($x = OtherClass::thing)
        elseif ($obj->isClassConstantFetch())
        {
            if (!($this->currentClass && $obj->isSameClassAs($this->currentClass))) {
                $this->data->addIssue ($obj->line, 'external_class_constant_fetch', $this->getScope('external class constant'), $obj->getName());
            } 
        }

        // check for static property fetch from different class ($x = OtherClass::$nameOfThing)
        elseif ($obj->isStaticPropertyFetch()) 
        {
            if (!($this->currentClass && $obj->isSameClassAs($this->currentClass))) {
                $this->data->addIssue ($obj->line, 'static_property_fetch', $this->getScope('static property'), $obj->getName());
            } 
        }

        // check for global function calls
        elseif ($obj->isFunctionCall()) 
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
        elseif (!is_null($this->currentClass) || !is_null($this->currentTrait))
        {
            $scope = is_null($this->currentClass) ? $this->currentTrait : $this->currentClass;

            if (!is_null($this->currentMethod)) {
                return $scope."::".$this->currentMethod;
            }

            return $scope;
        }
        else 
        {
            if (!empty($reference)) {
                $reference = '('.$reference.')';
            } 

            throw new \Exception ('Analysys error: Invalid scope '.$reference);
        }
    }

    /**
     * Are we outside of any class / global method
     * @return bool
     */
    private function inGlobalSpace()
    {
        return (is_null($this->currentClass) && is_null($this->currentTrait) && is_null($this->currentFunction));
    }
}

// Notes:
// 
// Expr\Closure
// Expr\Eval_
// Expr\ErrorSuppress  (@)
// Stmt\InlineHTML
