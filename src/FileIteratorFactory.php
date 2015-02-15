<?php
/**
 * FileIterator 
 * This class deals with file iteration
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

/**
 * FileIteratorFactory
 * This class creates a FileIterator
 * @Factory
 * @author Edson Medina <edsonmedina@gmail.com>
 */
class FileIteratorFactory 
{
	public function create ()
	{
		$analyserFactory = new AnalyserFactory;
		return new FileIterator ($analyserFactory->create ());
	}
}
