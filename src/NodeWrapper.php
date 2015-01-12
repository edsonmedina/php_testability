<?php
namespace edsonmedina\php_testability;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

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

	public function getVarList() 
	{
		return $this->node->vars;
	}

	public function getName() 
	{	
		$name      = '';
		$separator = '';

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
			$name .= $separator;
			if (!empty($this->node->name->name)) {
				$name .= $this->node->name->name;
			} else {
				$name .= '<variable>';	
			}
		} 
		else
		{
			if (!empty($this->node->name)) 
			{
				$name .= $separator . $this->node->name;
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
		return isset($this->node->stmts);
	}
}