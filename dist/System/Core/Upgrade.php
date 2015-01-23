<?php
	namespace xTend
	{
		class Upgrade
		{
			/*
				upgrade name format = xtend-{version}-{build}
				ex. xtend-0_4-2.zip
			*/
			public static function Latest() {
				$Files = Dir::Files(Dir::System("Upgrades"));
				$Upgrade = $Files[count($Files)-1];
			}

			public static function VersionBuild($Version, $Build) {
				
			}

			public static function Downgrade($Version, $Build) {

			}
		}
	}
?>