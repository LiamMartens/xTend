<?php
	namespace xTend
	{
		class Backup
		{
			public static function Save() {
				//Save a new backup
				$bak = new Archive(File::System("Backups.".time()."-".date('Y_m_d_H_i_s').".zip"));
				$bak->AddFolder("..");
				$bak->ExcludeFolder(Dir::System("Backups"));
				$bak->Save();
			}
			
			public static function Restore($Name = false) {
				$BackupName = $Name.".zip";
				//restore last backup or provided name
				if($Name==false) {
					$Files = Dir::Files(Dir::System("Backups"));
					$BackupName = $Files[count($Files)-1];
				}
				//check file existance
				if(!File::Exists(File::System("Backups.$BackupName"))) { return false; }
				//open archive and extract backup
				$bak = new Archive(File::System("Backups.$BackupName"), true);
				$bak->Extract(Dir::System("Backups.Temp"));
				//restore all files --> loop
				$TempDirectories = Dir::RecursiveDirectories(Dir::System("Backups.Temp"));
				$CurrentDirectories = Dir::RecursiveDirectories("..");
				foreach($TempDirectories as $Dir) {
					$FullDir = Dir::System("Backups.Temp")."/".$Dir;
					$CurrentDir = "../$Dir";
					$DirFiles = Dir::Files($FullDir);
					$CurrentFiles = Dir::Files($CurrentDir);
					foreach($DirFiles as $File) {
						File::Write("$CurrentDir/$File", File::Read("$FullDir/$File"));
						//remove index from currentfiles
						$index = array_search($File, $CurrentFiles);
						if($index!==false) {
							unset($CurrentFiles[$index]);
						}
					}
					//remove index from currentdirectories
					$index = array_search($Dir, $CurrentDirectories);
					if($index!==false) {
						unset($CurrentDirectories[$index]);
					}
					//reset indexes of currentfiles
					$CurrentFiles = array_values($CurrentFiles);
					foreach($CurrentFiles as $File) {
						//remove non backup files
						File::Remove("$CurrentDir/$File");
					}
				}
				//reset indexes of currentdirectories
				$CurrentDirectories = array_values($CurrentDirectories);
				foreach($CurrentDirectories as $Dir) {
					Dir::Remove("../$Dir");
				}
				//remove temp directory
				Dir::Remove(Dir::System("Backups.Temp"));
			}
		}
	}
?>