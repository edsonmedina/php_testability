<?php
namespace edsonmedina\php_testability;

interface ReportDataInterface  
{
	public function addIssue ($filePath, $line, $type, $identifier);
}