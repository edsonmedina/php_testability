<?php

namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportInterface;

interface AnalyserInterface 
{
	public function __construct (ReportInterface $report);
	public function scan ($filename);
}