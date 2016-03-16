<?php
	namespace xTend;
	class Archive
	{
		private $_zip;
		private $_destination;

		public function __construct($dest, $read = false) {
			$this->_destination = $dest;
			$this->_zip = new \ZipArchive;
			if($read) {
				$this->_zip->open($this->_destination);
			} else { $this->_zip->open($this->_destination, \ZipArchive::CREATE); }
		}
		public function addFile($filePath, $zipPath = false) {
			if($zipPath===false) {
				$this->_zip->addFile($filePath, preg_replace("/^(\.\.\/)+/", "", $filePath));
			} else { $this->_zip->addFile($filePath, preg_replace("/^(\.\.\/)+/", "", $zipPath)); }
		}
		public function addDirectory($dirPath) {
			$this->_zip->addEmptyDir(preg_replace("/^(\.\.\/)+/", "", $dirPath));
		}
		public function delete($name) {
			$this->_zip->deleteName($name);
		}
		public function extract($dest) {
			$res = $this->_zip->extractTo($dest);
			$this->_zip->close();
			return $res;
		}
		public function save() {
			$res = $this->_zip->close();
		}
	}