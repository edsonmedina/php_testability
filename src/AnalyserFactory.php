<?php
/**
 * AnalyserFactory
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;
use edsonmedina\php_testability\Analyser;
use edsonmedina\php_testability\AnalyserScope;
use edsonmedina\php_testability\AnalyserAbstractFactory;
use PhpParser;

class AnalyserFactory 
{
	public function create (ReportDataInterface $data)
	{
		$parser  = new PhpParser\Parser (new PhpParser\Lexer);
		$scope   = new AnalyserScope;
		$factory = new AnalyserAbstractFactory;

		return new Analyser ($data, $parser, $scope, $factory);
	}
}
