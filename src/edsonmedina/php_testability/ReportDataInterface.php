<?php
namespace edsonmedina\php_testability;

interface ReportDataInterface  
{
	public function setCurrentFilename ($filename);
	public function getIssuesCountForPath ($path);
	public function addIssue ($line, $type, $scope = null, $identifier = null);
}