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
				//open archive and extract backup
				$bak = new Archive(Dir::System("Backups.Temp"), true);
				$bak->Extract();
				//restore all files

				//remove temp directory
				Dir::Remove(Dir::System("Backups.Temp"));
				//Remove backup zip file
				File::Remove(File::System("Backups.Temp.".$BackupName));
			}
		}
	}
?>