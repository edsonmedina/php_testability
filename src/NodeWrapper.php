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
			$names = array ();
			foreach ($this->node->vars as $var) {
				$names[] = '$'.$var->name;
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
			if (!empty($this->node->name)) 
			{
				$name .= $separator . $this->node->name;
			} 
			elseif (!empty($this->node->var->name))
			{
				$name .= $separator.'$'.$this->node->var->name;
			}
		}
	
		return $name;
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
