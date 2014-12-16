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

		$this->traverser = (new TraverserFactory())->getInstance ($this->data, $this->scope);
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