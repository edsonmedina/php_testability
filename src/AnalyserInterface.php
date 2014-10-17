<?php
namespace edsonmedina\php_testability;

use edsonmedina\php_testability\ReportDataInterface;

interface AnalyserInterface 
{
	public function __construct (ReportDataInterface $data);
	public function scan ($filename);
}