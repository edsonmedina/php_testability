<?php
/**
 * AnalyserFactory
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\Analyser;
use edsonmedina\php_testability\AnalyserAbstractFactory;
use PhpParser;

class AnalyserFactory
{
	public function create ()
	{
		$parser  = new PhpParser\Parser (new PhpParser\Lexer);
		$factory = new AnalyserAbstractFactory;

		return new Analyser ($parser, $factory);
	}
}
