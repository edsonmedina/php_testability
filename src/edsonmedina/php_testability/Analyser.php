<?php
/**
 * Analyser 
 * This class deals with the static code analysis
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserInterface;
use edsonmedina\php_testability\NodeVisitors;

use PhpParser;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;

class Analyser implements AnalyserInterface
{
	private $data;
	private $parser;
	private $prettyPrinter;

	public function __construct (ReportDataInterface $data) 
	{
		ini_set('xdebug.max_nesting_level', 2000);

		$this->data = $data;
		$this->parser = new PhpParser\Parser (new PhpParser\Lexer);
		$this->prettyPrinter = new PhpParser\PrettyPrinter\Standard;
	}

	/**
	 * Scan a php file
	 * @param  string $filename 
	 */
	public function scan ($filename) 
	{
		$code = file_get_contents ($filename);
		$traverser = new PhpParser\NodeTraverser;

		$traverser->addVisitor (new NodeVisitors\ClassVisitor ($this->data));

		try 
		{
			// parse
		    $stmts = $this->parser->parse ($code);

		    // traverse
		    $stmts = $traverser->traverse($stmts);
		} 
		catch (PhpParser\Error $e) 
		{
		    echo $filename . ' - Parse Error: ' . $e->getMessage();
		}
	}
}