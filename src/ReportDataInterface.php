<?php
namespace edsonmedina\php_testability;
use edsonmedina\php_testability\IssueInterface;
use edsonmedina\php_testability\AnalyserScope;

interface ReportDataInterface  
{
	public function addIssue (IssueInterface $issue, AnalyserScope $scope = null);
	public function setCurrentFilename ($filename);
	public function getCurrentFilename ();
	public function getFileList ();
	public function getFullDirList ();
	public function getIssuesForFile ($filename);
	public function saveScopePosition ($scope, $lineNum);
	public function getScopePosition ($filename, $scope);
	public function getScopesForFile ($filename);
	public function getGlobalIssuesForFile ($filename);
	public function getIssuesForScope ($filename, $scope);
	public function getIssuesCountForDirectory ($path);
	public function getIssuesCountForFile ($filename);
	public function getIssuesCountForScope ($filename, $scope);
	public function getGlobalIssuesCount ($filename);
	public function isFileUntestable ($filename);
	public function listDirectory ($path);
}
