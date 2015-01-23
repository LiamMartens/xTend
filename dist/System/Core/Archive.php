<?php
	namespace xTend
	{
		class Archive
		{
			private $Zip;
			private $Destination;
			
			public function __construct($Dest, $Read = false) {
				//set source and destination
				$this->Destination = $Dest;
				//start zip archive
				$this->Zip = new \ZipArchive;
				//open ziparchive as read or write
				if($Read) { $this->Zip->open($this->Destination); } else { $this->Zip->open($this->Destination, \ZipArchive::CREATE); }
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

			public function Extract($Dest) {
				return $this->Zip->extractTo($Dest);
			}
			
			public function Save() {
				return $this->Zip->close();
			}
		}
	}
?>