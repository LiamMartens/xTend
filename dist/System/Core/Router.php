<?php
	namespace xTend
	{
		class Router {
			private static $_Default;
			private static $_Home;
			private static $_Aliases = array();
			private static $_Post = array();
			private static $_Get = array();
			private static $_Any = array();
			private static $_Error = array();
			//Redirection
			public static function To($Alias) {
				if(array_key_exists($Alias, self::$_Aliases)) {
					if(self::$_Aliases[$Alias]["Url"]!==false) {
						URL::to(self::$_Aliases[$Alias]["Url"]);
					}
				}
			}
			public static function Load($Alias) {
				if(array_key_exists($Alias, self::$_Aliases)) {
					self::ExecuteRoute(self::$_Aliases[$Alias]["Route"]);
				}
			}
			//Post route
			public static function Post($Url,$Route,$Alias=false) {
				self::$_Post[trim(strtolower($Url),"/")] = $Route;
				//save alias if necessary
				if(($Alias!==false)&&is_string($Alias)) {
					self::$_Aliases[$Alias] = array("Url" => $Url, "Route" => $Route);
				}
			}
			//Get route
			public static function Get($Url,$Route,$Alias=false) {
				self::$_Get[trim(strtolower($Url),"/")] = $Route;
				//save alias if necessary
				if(($Alias!==false)&&is_string($Alias)) {
					self::$_Aliases[$Alias] = array("Url" => $Url, "Route" => $Route);
				}
			}
			//Any route
			public static function Any($Url,$Route,$Alias=false) {
				self::$_Any[trim(strtolower($Url),"/")] = $Route;
				//save alias if necessary
				if(($Alias!==false)&&is_string($Alias)) {
					self::$_Aliases[$Alias] = array("Url" => $Url, "Route" => $Route);
				}
			}
			//Error routes
			public static function AppError($Error, $Route, $Alias=false) {
				self::$_Error[$Error] = $Route;
				//save alias if necessary
				if(($Alias!==false)&&is_string($Alias)) {
					self::$_Aliases[$Alias] = array("Url" => false, "Route" => $Route);
				}
			}
			//Set default route
			public static function Def($Route, $Alias=false) {
				self::$_Default = $Route;
				//save alias if necessary
				if(($Alias!==false)&&is_string($Alias)) {
					self::$_Aliases[$Alias] = array("Url" => false, "Route" => $Route);
				}
			}
			//Set home route
			public static function Home($Route, $Alias=false) {
				self::$_Home = $Route;
				//save alias if necessary
				if(($Alias!==false)&&is_string($Alias)) {
					self::$_Aliases[$Alias] = array("Url" => false, "Route" => $Route);
				}
			}
			//Route restriction
			//Restriction must return true
			//Routes will define the routes to be executed
			public static function Restrict($Restriction, $Routes) {
				//Restriction is callable and returns true OR
				//Restriction is not callable and represents true AND
				//Routes is callable
				if(((is_callable($Restriction)&&($Restriction()==true))||(($Restriction==true)&&!is_callable($Restriction)))&&is_callable($Routes)) {
					$Routes();
				}
				return false;
			}
			//Is Match
			private static function IsMatch($RequestUri, $SavedUri) {
				//Explode both URLs
				$Request = explode("/", trim(strtolower($RequestUri),"/"));
				$Saved = explode("/", $SavedUri);
				//check whether they have equal parts
				if(count($Request)!=count($Saved)) { return false; };
				//check each part
				$rx_matches = array();
				for($i=0;$i<count($Request);$i++) {
					$part_valid = false;
					//is url variable
					if(preg_match('/(\{)([a-zA-Z0-9_]+)(\})/', $Saved[$i], $rx_matches)&&preg_match('/([a-zA-Z0-9_]+)/', $Request[$i])) {
						URL::SetParameter($rx_matches[2], $Request[$i]);
						$part_valid = true;
					}
					//is regex
					if(preg_match('/(rx)(\{)(.*)(\})/', $Saved[$i], $rx_matches)&&preg_match('/'.$rx_matches[3].'/', $Request[$i])) {
						$part_valid = true;
					}
					//is regexed variable
					if(preg_match('/(rx)(\{)([a-zA-Z0-9_]+)(\})(\{)(.*)(\})/', $Saved[$i], $rx_matches)&&preg_match('/'.$rx_matches[6].'/', $Request[$i])) {
						URL::SetParameter($rx_matches[3], $Request[$i]);
						$part_valid = true;
					}
					//is *
					if(preg_match('/(\*+)/', $Saved[$i])) {
						$part_valid = true;
					}
					//is plain text
					if($Saved[$i]==$Request[$i]) {
						$part_valid = true;
					}
					//if a part of the url is not ok -> return false
					if(!$part_valid) {
						return false;
					}
				}
				//set saved url
				URL::SetRoute($SavedUri);
				return true;
			}
			//Execute request
			private static function ExecuteRoute($Route) {
				if(is_callable($Route)) {
					//It's a function
					$Route();
				} elseif(is_string($Route)) {
					//Its just a string
					echo $Route;
				} elseif(is_array($Route)) {
					//Check for Controller or View Data
					$Data = array();
					if(array_key_exists("Data", $Route)) {
						$Data = $Route["Data"];
					}
					//Check for model existance
					if(array_key_exists("Model",$Route)) {
						Models::Initialize($Route["Model"]);
					}
					//Check for controller existance
					if(array_key_exists("Controller",$Route)) {
						Controllers::Initialize($Route["Controller"],$Data);
					}
					//Check for view existance
					if(array_key_exists("View",$Route)) {
						//Don't pass data to the view when there is a controller available
						if(!array_key_exists("Controller",$Route)) {
							Views::Initialize($Route["View"],$Data);
						} else {
							Views::Initialize($Route["View"]);
						}
					}
				}
			}
			//Execute error 
			public static function ThrowError($Error) {
				//Check whether error route has been set
				if(array_key_exists($Error, self::$_Error)) {
					//Execute request
					self::ExecuteRoute(self::$_Error[$Error]);
					return true;
				}
				return false;
			}
			//Post configuration
			public static function PostConfiguration() {
				$Request = $_SERVER['REQUEST_URI'];
				URL::SetRequest($Request);
				//Step one check home
				if(self::IsMatch($Request,'')) {
					self::ExecuteRoute(self::$_Home);
					return true;
				}
				//Step two, check Any Routes
				foreach(self::$_Any as $Uri => $Route) {
					//Is Match?
					if(self::IsMatch($Request, $Uri)) {
						//Execute route
						self::ExecuteRoute($Route);
						return true;
					}
				}
				//Step three check for post or get
				$SavedRequests=array();
				if(
					$_SERVER["REQUEST_METHOD"]=="POST"
				) {
					//POST 
					$SavedRequests = self::$_Post;
					URL::SetMethod("POST");
				} else {
					//GET
					$SavedRequests = self::$_Get;
					URL::SetMethod("GET");
				}
				//Execute $SavedRequests
				foreach($SavedRequests as $Uri => $Route) {
					//Is Match?
					if(self::IsMatch($Request, $Uri)) {
						//Execute route
						self::ExecuteRoute($Route);
						return true;
					}
				}
				//No routes comply
				//Try for a not found error
				if(!App::Error(Error::NotFound)) {
					//Check for default route
					if(self::$_Default) {
						//Execute default route
						self::ExecuteRoute(self::$_Default);
						return true;
					}
				}
				return false;
			}
		}
	}