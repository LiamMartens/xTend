<?php
	//Wow templating engine
	abstract class Wow
	{
		private static $_Expressions = array();
		
		public static function RegEx($criteria,$flags="") {
			return ("/(\{)(\{)$criteria(\})(\})/$flags");
		}
		
		public static function RegisterExpression($rx,$replace) {
			Self::$_Expressions[$rx]=$replace;
		}
	
		private static function Version($ViewContent) {
			$rx = Self::RegEx("(version)(:)(\d+)","i");
			$match = array();
			preg_match($rx,$ViewContent,$match);
			if(count($match)>0) {
				return substr($match[0],10,strlen($match[0])-12);
			}
			return false;
		}
		
		private static function Layout($ViewContent) {
			$rx = Self::RegEx("(layout)(:)(.+)","i");
			$match = array();
			preg_match($rx,$ViewContent,$match);
			if(count($match)>0) {
				return substr($match[0],9,strlen($match[0])-11);
			}
			return false;
		}
		
		private static function Compile($ViewContent) {
			$TempViewContent = $ViewContent;
			$CompiledContent = "";
			$SearchOffset = 0;
			$StartIndex = strpos($TempViewContent,"{{",$SearchOffset);
			$EndIndex = strpos($TempViewContent,"}}",$StartIndex);
			while($StartIndex!==false) {
				//Loop through content and replace accordingly
				//Append all previous content to CompiledContent
				$CompiledContent.=substr($TempViewContent,$SearchOffset,$StartIndex-$SearchOffset);
				//Fetch to be compiled commands
				$Command = substr($TempViewContent,$StartIndex,$EndIndex-$StartIndex+2);
				//Perform regex checks to determine the command type
				$RegExMatch = false;
				//Check all registered expressions
				foreach(Self::$_Expressions as $rx => $replace) {
					if(preg_match(Self::RegEx($rx),$Command)&&!$RegExMatch) {
						$CompiledContent.='<?php '.preg_replace('/'.$rx.'/i',$replace,substr($Command,2,strlen($Command)-4)).' ?>';
						$RegExMatch = true;
						break;
					}
				}
				//-----------------------
				//OTHER
					if(!$RegExMatch) {
						$CompiledContent.='<?php '.substr($Command,2,strlen($Command)-4).' ?>';
					}
				//-----------------------
				//Go to next command
				$SearchOffset=$EndIndex+2;
				$StartIndex = strpos($TempViewContent,"{{",$SearchOffset);
				$EndIndex = strpos($TempViewContent,"}}",$StartIndex);
			}
			//AFTER ADD EVERYTHING THAT IS LEFT
			$CompiledContent.=substr($TempViewContent,$SearchOffset);
			return $CompiledContent;
		}
		
		private static function CompileLayout($LayoutContent, $ViewContent) {
			$TempLayoutContent = $LayoutContent;
			$TempViewContent = $ViewContent;
			$CompiledContent = "";
			//Find and compile all sections
			$SearchOffset = 0;
			$StartIndex = strpos($TempLayoutContent,"{{section:",$SearchOffset);
			$EndIndex = strpos($TempLayoutContent,"}}",$StartIndex);
			//Loop through file content
			while($StartIndex!==false) {
				//Append any previous content to CompiledContent
				$CompiledContent.=substr($TempLayoutContent,$SearchOffset,$StartIndex-$SearchOffset);
				//Fetch section name
				$Section = substr($TempLayoutContent, $StartIndex+10,$EndIndex-$StartIndex-10);
				//COMPILE SECTION CONTENT INSIDE VIEW
				$SectionStartIndex = strpos($TempViewContent,"{{start:$Section}}");
				$SectionEndIndex = strpos($TempViewContent,"{{end:$Section}}");
				if($SectionStartIndex!==false) {
					$SectionContent = substr($TempViewContent,$SectionStartIndex+strlen("{{start:$Section}}"),$SectionEndIndex-$SectionStartIndex-strlen("{{start:$Section}}"));
					//Compile Section Content
					$CompiledContent.=Self::Compile($SectionContent);
				}
				//Go to next section
				$SearchOffset = $EndIndex+2;
				$StartIndex = strpos($TempLayoutContent,"{{section:",$SearchOffset);
				$EndIndex = strpos($TempLayoutContent,"}}",$StartIndex);
			}
			//Add after content
			$CompiledContent.=substr($TempLayoutContent,$SearchOffset);
			//Return content
			return $CompiledContent;
		}
	
		public static function Parse($view) {
			//Fetch File Path
			if(File::Exists(App::System("Views.$view").".php")) {
				App::Inc("Views.$view");
				return true;
			} else if(File::Exists(App::System("Views.$view").".wow.php")) {
				$FilePath = App::System("Views.$view").".wow.php";
				$ViewContent = File::Get($FilePath);
				$Version = Self::Version($ViewContent);
				$Layout = Self::Layout($ViewContent);
				$CompiledContent = "";
				//Determine whether the view should be recompiled or not
				if(!File::Exists(App::System("ViewOutput.$view-v$Version").".php")) {
					//If layout extension is present or not
					if($Layout!==false) {
						//Only allow Wow templated files as layout since there would be no use for non wow templated layout files
						$LayoutPath = App::System("Layouts.$Layout").".wow.php";
						$LayoutContent = File::Get($LayoutPath);
						//compiled content
						$CompiledContent = Self::CompileLayout($LayoutContent, $ViewContent);
					} else {
						//compiled content
						$CompiledContent = Self::Compile(substr($ViewContent,strlen("{{version:}}")+strlen($Version)));
					}
					//Save
					File::Put(App::System("ViewOutput.$view-v$Version").".php",$CompiledContent);
				}
				//Include compiled file
				App::Inc("ViewOutput.$view-v$Version");
				return true;
			}
			return false;
		}
	}
?>