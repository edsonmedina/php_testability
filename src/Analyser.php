<?php
/**
 * Analyser 
 * This class deals with the static code analysis
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\AnalyserInterface;
use edsonmedina\php_testability\AnalyserScope;
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
	private $dictionary;
	private $traverser;
	private $scope;

	public function __construct (ReportDataInterface $data) 
	{
		$this->data       = $data;
		$this->parser     = new PhpParser\Parser (new PhpParser\Lexer);
		$this->scope      = new AnalyserScope;
		$this->dictionary = new Dictionary;

		$this->traverser = new PhpParser\NodeTraverser;
		$this->traverser->addVisitor (new NodeVisitors\CodeInGlobalSpaceVisitor   ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\ClassConstantFetchVisitor  ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\StaticPropertyFetchVisitor ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\GlobalFunctionVisitor      ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\GlobalFunctionCallVisitor  ($this->data, $this->dictionary, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\ClassVisitor      ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\TraitVisitor      ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\InterfaceVisitor  ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\NewVisitor        ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\ExitVisitor       ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\GlobalVarVisitor  ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\StaticCallVisitor ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\MethodVisitor     ($this->data, $this->scope));
		$this->traverser->addVisitor (new NodeVisitors\IncludeVisitor    ($this->data, $this->scope));
	}

	/**
	 * Scan a php file
	 * @param  string $filename 
	 */
	public function scan ($filename) 
	{
		$code = file_get_contents ($filename);

		try 
		{
			// parse
		    $stmts = $this->parser->parse ($code);

		    // traverse
			$this->scope->reset();
			$this->data->setCurrentFilename ($filename);
		    
		    $this->traverser->traverse ($stmts);
		} 
		catch (PhpParser\Error $e)
		{
		    echo "\n\nParse Error: " . $e->getMessage() . " ({$filename})\n";
		}
		catch (\Exception $e)
		{
		    echo "\n\n" . $e->getMessage() . " (in {$filename})\n";
		}
	}
}