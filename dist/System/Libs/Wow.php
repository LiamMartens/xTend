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
				$RegEx = self::RegEx("(\s+|^)(@)(version)(:)([\d\.]+)(\s+|$)","i");
				//Get matches
				$Matches = array();
				preg_match($RegEx, $Content, $Matches);
				//Check version
				if(count($Matches)>0) {
					return intval($Matches[5]);
				}
				return false;
			}
			//	Get extended layout
			private static function Layout($Content) {
				//Build Regex
				$RegEx = self::RegEx("(\s+|^)(@)(layout)(:)([\w\-\_\.]+)(\s+|$)","i");
				//Get matches
				$Matches = array();
				preg_match($RegEx, $Content, $Matches);
				//Check layout
				if(count($Matches)>0) {
					return $Matches[5];
				}
				return false;
			}
			//	Get compile option
			private static function CompileOption($Content) {
				//Build Regex
				$RegEx = self::RegEx("(\s+|^)(@)(compile)(:)(always|version|never|change)(\s+|$)","i");
				//Get matches
				$Matches = array();
				preg_match($RegEx, $Content, $Matches);
				//Check layout
				if(count($Matches)>0) {
					return $Matches[5];
				}
				return false;
			}
			//Get file changed
			private static function Changed($FileName, $Layout = false) {
				//file exists should already be checked
				$time_last_mod = filemtime($FileName);
				if($Layout !== false) { $time_layout_last_mod = filemtime($Layout); }
				//get file meta info
				$last_compile = File::GetMeta($FileName, "last_compile");
				$last_layout_compile = File::GetMeta($FileName, "last_layout_compile");
				if($last_compile===false) { return true; }
				if(floatval($last_compile)<$time_last_mod) { return true; }
				if($Layout !== false) {
					if($last_layout_compile===false) { return true; }
					if(floatval($last_layout_compile)<$time_layout_last_mod) { return true; }
				}
				return false;
			}
			//Update meta data
			private static function UpdateMeta($FileName, $usesLayout = false) {
				File::SetMeta($FileName, "last_compile", time());
				if($usesLayout==true) { File::SetMeta($FileName, "last_layout_compile", time()); }
				return true;
			}
			//Compile piece of non layout Wow 
			public static function Compile($Content) {
				//replace @version: and @compile stuff
				$Content = preg_replace(self::RegEx("(\s+|^)(@version:\d+)(\s+|$)","i"), "", $Content);
				$Content = preg_replace(self::RegEx("(\s+|^)(@compile:[a-z0-9]+)(\s+|$)","i"), "", $Content);
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
					$RegEx = self::RegEx("(\s+|^)(@section:".$Sections[4][$i].")(\s+|$)([\s\S]*)(\s+|^)(@endsection:".$Sections[4][$i].")(\s+|$)", "im");
					//Save Section
					$SectionContent = array();
					preg_match_all($RegEx, $View, $SectionContent);
					if(count($SectionContent[4])>0) {
						$CompiledSections[$Sections[4][$i]] = self::Compile($SectionContent[4][0]);
					} else {
						$CompiledSections[$Sections[4][$i]] = "";
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
							(($CompileOption=="change")&&self::Changed($View->FileName, (($Layout!=false) ? $LayoutPath : false)))) {
							//Update ViewMeta
							self::UpdateMeta($View->FileName, (($Layout!=false) ? true : false));
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