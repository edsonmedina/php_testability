<?php
namespace edsonmedina\php_testability;

use PhpParser;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;

class NodeWrapper
{
	private $node;
	public  $line;

	public function __construct ($node) {
		$this->node = $node;
		$this->line = $node->getLine();
	}

	public function getVarList() {
		return $this->node->vars;
	}

	public function isClass() {
		return ($this->node instanceof Stmt\Class_);
	}

	public function isFunction() {
		return ($this->node instanceof Stmt\Function_);
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
}