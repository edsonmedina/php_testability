<?php

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\Report;
use edsonmedina\php_testability\AnalyserInterface;

class Analyser implements AnalyserInterface
{
	private $report;

	public function __construct (ReportInterface $report) 
	{
		$this->report = $report;
	}

	public function scan ($filename) 
	{

	}
}