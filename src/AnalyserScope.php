<?php
/**
 * AnalyserScope
 * This class holds the scope for the analyser
 * @author Edson Medina <edsonmedina@gmail.com>
 */
namespace edsonmedina\php_testability;

class AnalyserScope 
{
    private $currentClass    = null;
    private $currentTrait    = null;
    private $currentMethod   = null;
    private $currentFunction = null;
    private $insideThrow  = false;

    public function startClass ($name)
    {
    	if ($this->insideClassOrTrait()) 
    	{
    		throw new Exception ("Can't define class {$name} inside class/method");
    	}

    	$this->currentClass = $name;
    }

    public function endClass ()
    {
    	if (is_null($this->currentClass)) 
    	{
    		throw new Exception ("Class not started {$name}");
    	}

    	$this->currentClass = null;
    }

    public function startTrait ($name)
    {
    	if ($this->insideClassOrTrait()) 
    	{
    		throw new Exception ("Can't define trait {$name} inside class/method");
    	}

    	$this->currentTrait = $name;
    }

    public function endTrait ()
    {
    	if (is_null($this->currentTrait)) 
    	{
    		throw new Exception ("Trait not started {$name}");
    	}

    	$this->currentClass = null;
    }

    public function insideClassOrTrait ()
    {
    	return (!is_null($this->currentClass) || !is_null($this->currentTrait));
    }

    public function getBundleName ()
    {
    	if (!$this->insideClassOrTrait())
    	{
    		throw new Exception ("No class/trait started");
    	}

    	return is_null ($this->currentClass) ? $this->currentTrait : $this->currentClass;
    }

    public function startMethod ($name)
    {
    	if (!$this->insideClassOrTrait())
    	{
    		throw new Exception ("Declaring method {$name} outside of class/trait");
    	}

    	$this->currentMethod = $name;
    }

    public function endMethod ()
    {
    	if (is_null($this->currentMethod)) 
    	{
    		throw new Exception ("Method not started {$name}");
    	}

    	$this->currentMethod = null;
    }

    public function startFunction ($name)
    {
    	if ($this->insideClassOrTrait())
    	{
    		throw new Exception ("Declaring global function {$name} inside of class/trait");
    	}

    	$this->currentFunction = $name;
    }

    public function endFunction ()
    {
    	if (is_null($this->currentFunction)) 
    	{
    		throw new Exception ("Function not started {$name}");
    	}

    	$this->currentFunction = null;
    }

    /**
     * Returns the scope name
     * @return string 
     */
    public function getScopeName ()
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
            throw new Exception ('Analysys error: Invalid scope');
        }
    }

    /**
     * Are we outside of any class / global method
     * @return bool
     */
    public function inGlobalSpace()
    {
        return (is_null($this->currentClass) && is_null($this->currentTrait) && is_null($this->currentFunction));
    }
}