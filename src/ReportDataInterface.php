<?php
namespace edsonmedina\php_testability;

interface ReportDataInterface  
{
	public function addIssue ($line, $type, $scope = null, $identifier = null);
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
	public function listFilesWithNoIssues ();
}