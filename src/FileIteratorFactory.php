<?php
/**
 * FileIterator 
 * This class deals with file iteration
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportData;

/**
 * FileIteratorFactory
 * This class creates a FileIterator
 * @Factory
 * @author Edson Medina <edsonmedina@gmail.com>
 */
class FileIteratorFactory 
{
	public function create (ReportData $data)
	{
		$analyserFactory = new AnalyserFactory;
		
		$analyser = $analyserFactory->create ($data);
		return new FileIterator ($analyser);
	}
}
