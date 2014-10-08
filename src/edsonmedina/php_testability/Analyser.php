<?php
/**
 * Analyser 
 * This class deals with the static code analysis
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserInterface;

use PhpParser\Parser;
use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;

class Analyser implements AnalyserInterface
{
	private $data;
	private $parser;

	public function __construct (ReportDataInterface $data) 
	{
		ini_set('xdebug.max_nesting_level', 2000);

		$this->data = $data;
		$this->parser = new Parser (new Lexer);
	}

	public function scan ($filename) 
	{

	}
}