<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;

interface AnalyserInterface 
{
	public function scan ($filename);
}