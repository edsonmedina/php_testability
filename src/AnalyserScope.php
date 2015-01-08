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

    /**
     * Set current class name
     * @param string $name
     */
    public function startClass ($name)
    {
    	if ($this->insideClass()) 
    	{
    		throw new \LogicException ("Can't define class `{$name}` inside class ".$this->currentClass);
    	}

    	$this->currentClass = $name;
    }

    /**
     * End current class 
     */
    public function endClass ()
    {
    	if (is_null($this->currentClass)) 
    	{
    		throw new \LogicException ("Class not started");
    	}

    	$this->currentClass = null;
    }

    /**
     * Are we currently inside a class?
     * @return bool
     */
    public function insideClass ()
    {
    	return !(is_null($this->currentClass));
    }

    /**
     * Get current class name
     * @return string $name
     */
    public function getClassName ()
    {
    	if (!$this->insideClass())
    	{
    		throw new \LogicException ("No class started");
    	}

    	return $this->currentClass;
    }

    /**
     * Set current method name
     * @param string $name
     */
    public function startMethod ($name)
    {
    	if (!$this->insideClass())
    	{
    		throw new \LogicException ("Declaring method `{$name}` outside of class");
    	}

    	$this->currentMethod = $name;
    }

    /**
     * End current method name
     */
    public function endMethod ()
    {
    	if (is_null($this->currentMethod)) 
    	{
    		throw new \LogicException ("Method not started");
    	}

    	$this->currentMethod = null;
    }

    /**
     * Set current function name
     * @param string $name
     */
    public function startFunction ($name)
    {
    	if ($this->insideClass())
    	{
    		throw new \LogicException ("Declaring global function `{$name}` inside of ".$this->currentClass."::".$this->currentMethod);
    	}

    	$this->currentFunction = $name;
    }

    /**
     * End current function name
     */
    public function endFunction ()
    {
    	if (is_null($this->currentFunction)) 
    	{
    		throw new \LogicException ("Function not started");
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