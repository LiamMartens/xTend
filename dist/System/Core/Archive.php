<?php
	namespace xTend
	{
		class Archive
		{
			private $Zip;
			private $Source;
			private $Destination;
			
			public function __construct($Dest) {
				//set source and destination
				$this->Source = $Src;
				$this->Destination = $Dest;
				//start zip archive
				$this->Zip = new \ZipArchive;
				$this->Zip->open($this->Destination, \ZipArchive::CREATE);
			}
			
			public function AddFile($FilePath, $ZipPath) {
				$this->Zip->addFile($FilePath);
			}
			
			public function AddFolder($DirPath) {
				$Directories = Dir::RecursiveDirectories($DirPath);
				foreach($Directories as $Dir) {
					$this->Zip->addEmptyDir($Dir);
				}
				$Files = Dir::RecursiveFiles($DirPath);
				foreach($Files as $File) {
					$this->Zip->addFile($DirPath."/".$File,$File);
				}
			}
			
			public function Save() {
				$this->Zip->close();
			}
		}
	}
?>