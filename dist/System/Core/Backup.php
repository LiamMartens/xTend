<?php
	namespace xTend
	{
		class Backup
		{
			public static function Save() {
				//Save a new backup
				$bak = new Archive(File::System("Backups.".time()."-".date('Y_m_d_H_i_s').".zip"));
				$bak->AddFolder("../");
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
				$TempFiles = Dir::RecursiveFiles(Dir::System("Backups.Temp"));
				foreach($TempFiles as $File) {
					$FileContent = File::Read(Dir::System("Backups.Temp")."/".$File);
					file_put_contents("../".$File, $FileContent);
				}
				//remove temp directory
				Dir::Remove(Dir::System("Backups.Temp"));
			}
		}
	}
?>