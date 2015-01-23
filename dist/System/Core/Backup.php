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
			
			public static function Restore() {
				
			}
		}
	}
?>