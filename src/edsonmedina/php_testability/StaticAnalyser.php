<?php
/**
 * PHP_Testability 
 * This class deals with the static analyser
 * @author Edson Medina <edsonmedina@gmail.com>
 */
namespace edsonmedina\php_testability;

class StaticAnalyser 
{
	private $baseDir;
	private $reportDir;

	/**
	 * Runs the static analyser
	 * @param  string $baseDir   Codebase directory
	 */
	public function __construct ($baseDir)
	{
		$this->baseDir   = $baseDir;
	}

	/**
	 * Run report
	 */
	public function run ()
	{
		// $iterator = new FileIterator ($baseDir);

	}	
}