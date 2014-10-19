<?php
namespace edsonmedina\php_testability\NodeVisitors;
use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\NodeWrapper;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\DictionaryInterface;

use PhpParser;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

class ClassVisitor extends PhpParser\NodeVisitorAbstract
{
    private $data;
    private $insideThrow = false;
    private $hasReturn   = false;
    private $muted       = false;
    private $phpInternalFunctions = array ();
    private $dictionary;
    private $scope;

    public function __construct (ReportDataInterface $data, DictionaryInterface $dictionary, AnalyserScope $scope)
    {
        $this->data  = $data;
        $this->scope = $scope;
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
            $this->scope->startClass ($obj->getName());
        }
        elseif ($obj->isTrait()) 
        {
            $this->scope->startTrait ($obj->getName());
        }
        elseif ($obj->isMethod()) 
        {
            $this->scope->startMethod ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $obj->line);
        }
        elseif ($obj->isFunction()) 
        {
            $this->scope->startFunction ($obj->getName());
            $this->data->saveScopePosition ($this->scope->getScopeName(), $obj->line);
        }
        elseif ($obj->isReturn()) 
        {
            $this->hasReturn = true;
        }
        elseif ($obj->isInterface()) 
        {
            $this->muted = true;
        }
        elseif ($obj->isThrow()) 
        {
            $this->insideThrow = true;
        }
    }

    public function leaveNode (PhpParser\Node $node) 
    {
        $obj = new NodeWrapper ($node);

        if ($this->muted) {
            return;
        }

        // end of interface
        elseif ($obj->isInterface()) {
            $this->muted = false;
        }

        // check for code outside of classes/functions
        elseif ($this->scope->inGlobalSpace() && !$obj->isAllowedOnGlobalSpace())
        {
                $this->data->addIssue ($obj->line, 'code_on_global_space');
                return;
        }

        // check for global variables
        elseif ($obj->isGlobal()) 
        {
            $scope = $this->scope->getScopeName();

            foreach ($obj->getVarList() as $var) {
                $this->data->addIssue ($var->getLine(), 'global', $scope, $var->name);
            }
        }

        // end of class
        elseif ($obj->isClass()) {
            $this->scope->endClass();
        }

        // end of trait
        elseif ($obj->isTrait()) {
            $this->scope->endTrait();
        }

        // end of method or global function
        elseif ($obj->isMethod() || $obj->isFunction()) 
        {
            // check for a lacking return statement in the method/function
            if ($obj->hasChildren() && !$this->hasReturn) 
            {
                if ($obj->getName() !== '__construct') {
                    $this->data->addIssue ($obj->endLine, 'no_return', $this->scope->getScopeName(), '');
                }
            }
            
            if ($obj->isFunction()) {
                $this->scope->endFunction();
            } else {
                $this->scope->endMethod();
            }
            
            $this->hasReturn = false;
        }

        // check for "new" statement (ie: $x = new Thing())
        elseif ($obj->isNew() && !$this->insideThrow) {
            $this->data->addIssue ($obj->line, 'new', $this->scope->getScopeName(), $obj->getName());
        }

        // check for exit/die statements
        elseif ($obj->isExit()) {
            $this->data->addIssue ($obj->line, 'exit', $this->scope->getScopeName(), '');
        }

        // check for static method calls (ie: Things::doStuff())
        elseif ($obj->isStaticCall()) {
            $this->data->addIssue ($obj->line, 'static_call', $this->scope->getScopeName(), $obj->getName());
        }

        // check for class constant fetch from different class ($x = OtherClass::thing)
        elseif ($obj->isClassConstantFetch())
        {
            if (!($this->scope->insideClassOrTrait() && $obj->isSameClassAs($this->scope->getBundleName()))) {
                $this->data->addIssue ($obj->line, 'external_class_constant_fetch', $this->scope->getScopeName(), $obj->getName());
            } 
        }

        // check for static property fetch from different class ($x = OtherClass::$nameOfThing)
        elseif ($obj->isStaticPropertyFetch()) 
        {
            if (!($this->scope->insideClassOrTrait() && $obj->isSameClassAs($this->scope->getBundleName()))) {
                $this->data->addIssue ($obj->line, 'static_property_fetch', $this->scope->getScopeName(), $obj->getName());
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

            $this->data->addIssue ($obj->line, 'global_function_call', $this->scope->getScopeName(), $functionName);
        }

        elseif ($obj->isThrow()) 
        {
            $this->insideThrow = false;
        }
    }
}
