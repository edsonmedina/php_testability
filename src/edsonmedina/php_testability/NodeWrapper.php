<?php
namespace edsonmedina\php_testability;

use PhpParser;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

class NodeWrapper
{
	private $node;
	public  $line;
	public  $endLine;

	public function __construct ($node) 
	{
		$this->node    = $node;
		$this->line    = $node->getLine();
		$this->endLine = $node->getAttribute('endLine');
	}

	public function getVarList() {
		return $this->node->vars;
	}

	public function getName() 
	{	
		$name = '';

		if (!empty($this->node->class->parts)) 
		{
			if (is_array($this->node->class->parts)) 
			{
				$name .= join('\\', $this->node->class->parts);
			} 
			else 
			{
				$name .= $this->node->class->parts;
			}

			$name .= empty($this->node->name) ? '' : '::'.$this->node->name;
		}
		else
		{
			$name .= empty($this->node->name) ? '' : $this->node->name;			
		}
		
		return $name;
	}

	public function isClass() {
		return ($this->node instanceof Stmt\Class_);
	}

	public function isFunction() {
		return ($this->node instanceof Stmt\Function_);
	}

	public function isSameClassAs ($classname) {
		return end($this->node->class->parts) === $classname;
	}

	public function isMethod() {
		return ($this->node instanceof Stmt\ClassMethod);
	}

	public function isGlobal() {
		return ($this->node instanceof Stmt\Global_);
	}

	public function isInterface() {
		return ($this->node instanceof Stmt\Interface_);
	}

	public function isNew() {
		return ($this->node instanceof Expr\New_);
	}

	public function isReturn() {
		return ($this->node instanceof Stmt\Return_);
	}

	public function isExit() {
		return ($this->node instanceof Expr\Exit_);
	}

	public function isStaticCall() {
		return ($this->node instanceof Expr\StaticCall);
	}

	public function isClassConstantFetch() {
		return ($this->node instanceof Expr\ClassConstFetch);
	}

	public function isStaticPropertyFetch() {
		return ($this->node instanceof Expr\StaticPropertyFetch);
	}

	public function isFunctionCall() {
		return ($this->node instanceof Expr\FuncCall);
	}

	public function hasNoChildren() {
		return !($this->node->stmts);
	}
}