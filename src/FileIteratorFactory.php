<?php
/**
 * FileIterator 
 * This class deals with file iteration
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\Contexts\RootContext;

/**
 * FileIteratorFactory
 * This class creates a FileIterator
 * @Factory
 * @author Edson Medina <edsonmedina@gmail.com>
 */
class FileIteratorFactory 
{
	public function create (RootContext $report)
	{
		$analyserFactory = new AnalyserFactory;
		
		$analyser = $analyserFactory->create ($report);
		return new FileIterator ($analyser);
	}
}
