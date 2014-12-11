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
    private $currentMethod   = null;
    private $currentFunction = null;

    public function reset ()
    {
        $this->currentClass    = null;
        $this->currentMethod   = null;
        $this->currentFunction = null;
    }

    public function startClass ($name)
    {
    	if ($this->insideClass()) 
    	{
    		throw new \Exception ("Can't define class `{$name}` inside class ".$this->currentClass);
    	}

    	$this->currentClass = $name;
    }

    public function endClass ()
    {
    	if (is_null($this->currentClass)) 
    	{
    		throw new \Exception ("Class not started");
    	}

    	$this->currentClass = null;
    }

    public function insideClass ()
    {
    	return !(is_null($this->currentClass));
    }

    public function getClassName ()
    {
    	if (!$this->insideClass())
    	{
    		throw new \Exception ("No class started");
    	}

    	return $this->currentClass;
    }

    public function startMethod ($name)
    {
    	if (!$this->insideClass())
    	{
    		throw new \Exception ("Declaring method `{$name}` outside of class");
    	}

    	$this->currentMethod = $name;
    }

    public function endMethod ()
    {
    	if (is_null($this->currentMethod)) 
    	{
    		throw new \Exception ("Method not started `{$name}`");
    	}

    	$this->currentMethod = null;
    }

    public function startFunction ($name)
    {
    	if ($this->insideClass())
    	{
    		throw new \Exception ("Declaring global function `{$name}` inside of ".$this->currentClass."::".$this->currentMethod);
    	}

    	$this->currentFunction = $name;
    }

    public function endFunction ()
    {
    	if (is_null($this->currentFunction)) 
    	{
    		throw new \Exception ("Function not started `{$name}`");
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
        elseif (!is_null($this->currentClass))
        {
            $scope = $this->currentClass;

            if (!is_null($this->currentMethod)) {
                return $scope."::".$this->currentMethod;
            }

            return $scope;
        }
        else 
        {
        	throw new \Exception ("Invalid scope");
        }
    }

    /**
     * Are we outside of any class / global method
     * @return bool
     */
    public function inGlobalSpace()
    {
        return (is_null($this->currentClass) && is_null($this->currentFunction));
    }
}