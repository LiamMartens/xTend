<?php
	namespace xTend\Core;
	class FileManager
	{
		private $_files;
		public function __construct() {
			$this->_files=[];
		}
		public function includeFile($fullPath) {
			$fullPath=realpath($fullPath);
			if(array_search($fullPath, $this->_files)===false) {
				include($fullPath);
				$this->_files[]=$fullPath;
			}
		}
		public function includeFiles($filePaths) {
			foreach ($filePaths as $path) {
				$this->includeFile($path);
			}
		}
	}
