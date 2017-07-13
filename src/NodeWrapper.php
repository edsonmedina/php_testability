<?php
namespace edsonmedina\php_testability;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Expr;

class NodeWrapper
{
	private $node;
	public  $line;
	public  $endLine;

	public function __construct (PhpParser\Node $node) 
	{
		$this->node    = $node;
		$this->line    = $node->getLine();
		$this->endLine = $node->getAttribute('endLine');
	}

	public function getName() 
	{	
		$name      = '';
		$separator = '';

		// deal with list of variable names
		if (isset($this->node->vars))
		{
			$names = [];
			foreach ($this->node->vars as $var) 
			{
				if ($var instanceof Expr\ArrayDimFetch)
				{
					$names[] = '$'.$this->getArrayDimFetchName ($var);
				}
				else
				{
				    if ($var->name instanceof Expr\Variable)
                    {
                        $names[] = '$'.$var->name->name;
                    } else {
                        $names[] = '$'.$var->name;
                    }
				}
			}

			return join (', ', $names);
		}

		if (isset($this->node->class)) 
		{
			if (isset($this->node->class->parts)) {
				$name .= $this->node->class->toString ('\\');
			} else {
				$name .= '<variable>';
			}

			$separator = '::';
		}

		if (isset($this->node->name))
		{
			if ($this->node->name instanceof Expr\Variable) 
			{
				$nodeName = $this->node->getAttribute('name');
				$name .= $separator. (!empty($nodeName) ? $nodeName : '<variable>');
			} 
			elseif ($this->node->name instanceof Expr\ArrayDimFetch) 
			{
				$name .= $separator.'<variable>';	
			} 
			else
			{
				$name .= $separator . $this->node->name;
			}
		}
		elseif (isset($this->node->var))
		{
			if ($this->node->var->name instanceof Expr\Variable)
			{
				$name .= $separator.'$'.$this->node->var->name->name;
			}
			else
			{
				$name .= $separator.'$'.$this->node->var->name;
			}
		}
	
		return $name;
	}

	public function getArrayDimFetchName ($node)
	{
	    if (isset($node->var)) {
    	    return $this->getArrayDimFetchName($node->var);
    	}
    	return $node->name;
	}

	public function isSameClassAs ($classname) 
	{
		$name = end($this->node->class->parts);
		return ($name === $classname || $name === 'self');
	}

	public function hasChildren() 
	{
		return !empty($this->node->stmts);
	}
}
