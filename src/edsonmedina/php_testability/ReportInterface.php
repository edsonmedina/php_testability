<?php

namespace edsonmedina\php_testability;

interface ReportInterface  
{
	public function addIssue ($filePath, $line, $type, $identifier);
	public function generate();
}