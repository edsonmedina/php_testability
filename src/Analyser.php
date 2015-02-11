<?php
/**
 * Analyser 
 * This class deals with the static code analysis
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\AnalyserAbstractFactory;
use edsonmedina\php_testability\Contexts\FileContext;
use PhpParser;

class Analyser 
{
	private $parser;
	private $factory;
	private $report;

	public function __construct (PhpParser\Parser $parser, AnalyserAbstractFactory $factory)
	{
		$this->parser  = $parser;
		$this->factory = $factory;
	}

	/**
	 * Scan a php file
	 * @param FileContext $file 
	 */
	public function scan (FileContext $file) 
	{
		$filename = $file->getName();

		$code = file_get_contents ($filename);

		try 
		{
			// parse
		    $stmts = $this->parser->parse ($code);

		    // traverse
			$traverser = $this->factory->createTraverser ($file);
		    $traverser->traverse ($stmts);
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
