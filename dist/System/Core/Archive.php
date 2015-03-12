<?php
	namespace xTend
	{
		class Archive
		{
			private $Zip;
			private $Destination;
			private $Exclusions;
			
			public function __construct($Dest, $Read = false) {
				//Reset exclusions
				$this->Exclusions = array();
				//set source and destination
				$this->Destination = $Dest;
				//start zip archive
				$this->Zip = new \ZipArchive;
				//open ziparchive as read or write
				if($Read) { $this->Zip->open($this->Destination); } else { $this->Zip->open($this->Destination, \ZipArchive::CREATE); }
			}
			
			public function AddFile($FilePath, $ZipPath = false) {
				if($ZipPath===false) {
					$this->Zip->addFile(preg_replace('/^(\.\.\/)+/', '', $FilePath));
				} else { $this->Zip->addFile(preg_replace('/^(\.\.\/)+/', '', $FilePath), preg_replace('/^(\.\.\/)+/', '', $ZipPath)); }
			}
			
			public function AddFolder($DirPath) {
				$Directories = Dir::RecursiveDirectories($DirPath);
				foreach($Directories as $Dir) {
					$this->Zip->addEmptyDir(preg_replace('/^(\.\.\/)+/', '', $DirPath."/".$Dir));
				}
				$Files = Dir::RecursiveFiles($DirPath);
				foreach($Files as $File) {
					$this->Zip->addFile($DirPath."/".$File, preg_replace('/^(\.\.\/)+/', '', $DirPath."/".$File));
				}
			}

			public function ExcludeFolder($DirPath) {
				$Files = Dir::RecursiveFiles($DirPath);
				foreach($Files as $File) {
					$this->Exclusions[] = $DirPath."/".$File;
					//remove name
					$this->Zip->deleteName(preg_replace('/^(\.\.\/)+/', '', $DirPath."/".$File));
				}
			}

			public function ExcludeFile($FilePath) {
				$this->Exclusions[] = $FilePath;
				$this->Zip->deleteName(preg_replace('/^(\.\.\/)+/', '', $FilePath));
			}

			public function Extract($Dest) {
				return $this->Zip->extractTo($Dest);
			}
			
			public function Save() {
				//remove all exclusions again
				foreach($this->Exclusions as $Excl) {
					$this->Zip->deleteName($Excl);
				}
				//Save
				return $this->Zip->close();
			}
		}
	}