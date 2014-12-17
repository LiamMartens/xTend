<?php
	namespace xTend
	{
		class Wow {
			//Possible Wow Expressions
			private static $_Expressions = array();
			//Regex start
			public static function RegEx($Criteria, $Flags = "") {
				return "/$Criteria/$Flags";
			}
			//Register expression
			public static function RegisterExpression($Expression, $RegReplacement) {
				Self::$_Expressions[$Expression] = $RegReplacement;
			}

			//----- COMPILER -----//
			//	Get view version
			private static function Version($Content) {
				//Build Regex
				$RegEx = Self::RegEx("(@)(version)(:)(\d+)","i");
				//Get matches
				$Matches = array();
				preg_match($RegEx, $Content, $Matches);
				//Check version
				if(count($Matches)>0) {
					return intval($Matches[4]);
				}
				return false;
			}
			//	Get extended layout
			private static function Layout($Content) {
				//Build Regex
				$RegEx = Self::RegEx("(@)(layout)(:)([\w\-\_]+)","i");
				//Get matches
				$Matches = array();
				preg_match($RegEx, $Content, $Matches);
				//Check layout
				if(count($Matches)>0) {
					return $Matches[4];
				}
				return false;
			}
			//Compile piece of non layout Wow 
			private static function Compile($Content) {
				foreach (Self::$_Expressions as $RegEx => $Replacement) {
					$Content = preg_replace($RegEx, $Replacement, $Content);
					//$Content = preg_replace($RegEx, $Replacement, $Content);
				}
				return $Content;
			}
			//Compile piece of layout wow
			private static function CompileLayout($Layout, $View) {
				//Step one compile the layout
				$Layout = Self::Compile($Layout);
				//Get sections
				$RegEx = Self::RegEx("(@)(section)(:)([\w\-\_]+)","i");
				$Sections = array();
				preg_match_all($RegEx, $Layout, $Sections);
				//Save CompiledSections for future replacement
				$CompiledSections = array();
				//Loop through all sections and build them accordingly
				for($i=0;$i<count($Sections[4]);$i++) {
					$RegEx = Self::RegEx("(@section:".$Sections[4][$i]."\n)([\s\S]*)(\n@endsection:".$Sections[4][$i].")", "im");
					//Save Section
					$SectionContent = array();
					preg_match_all($RegEx, $View, $SectionContent);
					$CompiledSections[$Sections[4][$i]] = Self::Compile($SectionContent[2][0]);
				}
				foreach ($CompiledSections as $Section => $Content) {
					$Layout = preg_replace(Self::RegEx("(@)(section)(:)($Section)"), $Content, $Layout);
				}
				return $Layout;
			}
			//Compile view
			public static function PostConfiguration() {
				$View = App::View();
				//Check whether there is a view
				if($View!=null) {
					//Is it Wow Or not?
					if(!$View->IsWow) {
						//Just include
						App::IncludeFileName($View->FileName);
					} else {
						//Compile Wow
						$Content = str_replace("\r\n","\n",File::Read($View->FileName));
						//Get version 
						$Version = Self::Version($Content);
						//Get layout
						$Layout = Self::Layout($Content);
						//Compile stuffs
						$CompiledContent = "";
						//Should the view be recompiled
						if(!File::Exists(File::System("ViewOutput.".$View->Name."-$Version.php"))) {
							if($Layout!=false) {
								//Extend layout
								$LayoutPath = File::System("Layouts.$Layout.wow").".php";
								$LayoutContent = str_replace("\r\n","\n",File::Read($LayoutPath));
								//Compile content
								$CompiledContent = Self::CompileLayout($LayoutContent, $Content);
							} else {
								//Don't extend layout
								$CompiledContent = Self::Compile($Content);
							}
							//Save compiled view
							File::Write(File::System("ViewOutput.".$View->Name."-$Version.php"), $CompiledContent);
						}
						App::IncludeFile("ViewOutput.".$View->Name."-$Version.php");
					}
					return true;
				}
				return false;
			}
		}
	}
?>