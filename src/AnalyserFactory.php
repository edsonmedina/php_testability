<?php
/**
 * AnalyserFactory
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use PhpParser\ParserFactory;

class AnalyserFactory
{
	public function create ()
	{
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$factory = new AnalyserAbstractFactory;

		return new Analyser ($parser, $factory);
	}
}
