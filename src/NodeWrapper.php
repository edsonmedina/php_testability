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
		$separator = '';

		if (!empty($this->node->class->parts)) 
		{
			if (is_array($this->node->class->parts)) 
			{
				// fully qualified names
				$name .= join ('\\', $this->node->class->parts);
			} 
			else 
			{
				$name .= $this->node->class->parts;
			}

			$separator = '::';
		}

		if ($this->node->name instanceof Expr\Variable) 
		{
			$name .= $separator . $this->node->getAttribute('name');
		} 
		elseif ($this->node->name instanceof Expr\ArrayDimFetch) 
		{
			$name .= 'variable function';
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

	public function isClass() {
		return ($this->node instanceof Stmt\Class_);
	}

	public function isTrait() {
		return ($this->node instanceof Stmt\Trait_);
	}

	public function isFunction() {
		return ($this->node instanceof Stmt\Function_);
	}

	public function isSameClassAs ($classname) 
	{
		$name = end($this->node->class->parts);
		return ($name === $classname || $name === 'self');
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

	public function isVariable() {
		return ($this->node instanceof Expr\Variable);
	}

	public function isArrayDimFetch() {
		return ($this->node instanceof Expr\ArrayDimFetch);
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

	public function hasChildren() {
		return isset($this->node->stmts);
	}

	public function isUse() {
		return ($this->node instanceof Stmt\UseUse || $this->node instanceof Stmt\Use_);
	}

	public function isNamespace() {
		return ($this->node instanceof Stmt\Namespace_ || $this->node instanceof Node\Name);
	}

	public function isThrow() {
		return ($this->node instanceof Stmt\Throw_);
	}

	public function isInclude() {
		return ($this->node instanceof Expr\Include_);
	}

	public function isCatch() {
		return ($this->node instanceof Stmt\Catch_);
	}

    /**
     * Is node allowed on global space?
     * @param NodeWrapper $node
     * @param bool
     */
    public function isAllowedOnGlobalSpace () {
        return ($this->isClass() || $this->isTrait() || $this->isFunction() || $this->isUse() || $this->isNamespace() || $this->isInterface());
    }
}