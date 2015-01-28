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
				self::$_Expressions[$Expression] = $RegReplacement;
			}

			//----- COMPILER -----//
			//	Get view version
			private static function Version($Content) {
				//Build Regex
				$RegEx = self::RegEx("(@)(version)(:)(\d+)","i");
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
				$RegEx = self::RegEx("(@)(layout)(:)([\w\-\_]+)","i");
				//Get matches
				$Matches = array();
				preg_match($RegEx, $Content, $Matches);
				//Check layout
				if(count($Matches)>0) {
					return $Matches[4];
				}
				return false;
			}
			//	Get compile option
			private static function CompileOption($Content) {
				//Build Regex
				$RegEx = self::RegEx("(@)(compile)(:)(always|version|never|change)","i");
				//Get matches
				$Matches = array();
				preg_match($RegEx, $Content, $Matches);
				//Check layout
				if(count($Matches)>0) {
					return $Matches[4];
				}
				return false;
			}
			//Get file changed
			private static function Changed($FileName) {
				//file exists should already be checked
				$time_last_mod = filemtime($FileName);
				//get file meta info
				$last_compile = File::GetMeta($FileName, "last_compile");
				if($last_compile===false) { return true; }
				if(floatval($last_compile)<$time_last_mod) { return true; }
				return false;
			}
			//Update meta data
			private static function UpdateMeta($FileName) {
				File::SetMeta($FileName, "last_compile", time());
				return true;
			}
			//Compile piece of non layout Wow 
			private static function Compile($Content) {
				foreach (self::$_Expressions as $RegEx => $Replacement) {
					$Content = preg_replace($RegEx, $Replacement, $Content);
					//$Content = preg_replace($RegEx, $Replacement, $Content);
				}
				return $Content;
			}
			//Compile piece of layout wow
			private static function CompileLayout($Layout, $View) {
				//Step one compile the layout
				$Layout = self::Compile($Layout);
				//Get sections
				$RegEx = self::RegEx("(@)(section)(:)([\w\-\_]+)","i");
				$Sections = array();
				preg_match_all($RegEx, $Layout, $Sections);
				//Save CompiledSections for future replacement
				$CompiledSections = array();
				//Loop through all sections and build them accordingly
				for($i=0;$i<count($Sections[4]);$i++) {
					$RegEx = self::RegEx("(@section:".$Sections[4][$i]."\n)([\s\S]*)(\n@endsection:".$Sections[4][$i].")", "im");
					//Save Section
					$SectionContent = array();
					preg_match_all($RegEx, $View, $SectionContent);
					if(count($SectionContent[2])>0) {
						$CompiledSections[$Sections[4][$i]] = self::Compile($SectionContent[2][0]);
					}
				}
				foreach ($CompiledSections as $Section => $Content) {
					$Layout = preg_replace(self::RegEx("(@)(section)(:)($Section)"), $Content, $Layout);
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
						$Version = self::Version($Content);
						//Get layout
						$Layout = self::Layout($Content);
						//Layout path
						$LayoutPath = File::System("Layouts.$Layout.wow").".php";
						//Get compile option
						$CompileOption = self::CompileOption($Content);
						//Compile stuffs
						$CompiledContent = "";
						//Should the view be recompiled
						if(((!File::Exists(File::System("ViewOutput.".$View->Name."-$Version.php")))&&
							($CompileOption!="never"))||
							($CompileOption=="always")||
							(($CompileOption=="change")&&self::Changed($View->FileName))||
							self::Changed($LayoutPath)) {
							//Update ViewMeta
							self::UpdateMeta($View->FileName);
							self::UpdateMeta($LayoutPath);
							if($Layout!=false) {
								//Extend layout
								$LayoutContent = str_replace("\r\n","\n",File::Read($LayoutPath));
								//Compile content
								$CompiledContent = self::CompileLayout($LayoutContent, $Content);
							} else {
								//Don't extend layout
								$CompiledContent = self::Compile($Content);
							}
							//Save compiled view
							File::Write(File::System("ViewOutput.".$View->Name."-$Version.php"), trim($CompiledContent));
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