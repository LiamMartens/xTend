<?php
	namespace xTend
	{
		class Router {
			private static $_Default;
			private static $_Home;
			private static $_Post = array();
			private static $_Get = array();
			private static $_Any = array();
			private static $_Error = array();
			private static $_Vars = array();
			//Return Vars
			public static function Vars() {
				return self::$_Vars;
			}
			//Post route
			public static function Post($Url,$Route) {
				self::$_Post[trim(strtolower($Url),"/")] = $Route;
			}
			//Get route
			public static function Get($Url,$Route) {
				self::$_Get[trim(strtolower($Url),"/")] = $Route;
			}
			//Any route
			public static function Any($Url,$Route) {
				self::$_Any[trim(strtolower($Url),"/")] = $Route;
			}
			//Error routes
			public static function AppError($Error, $Route) {
				self::$_Error[$Error] = $Route;
			}
			//Set default route
			public static function Def($Route) {
				self::$_Default = $Route;
			}
			//Set home route
			public static function Home($Route) {
				self::$_Home = $Route;
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
				//Reset Url variables
				self::$_Vars = array();
				//Explode both urls
				$RequestPath = explode("/",trim(strtolower($RequestUri),"/"));
				$SavedPath = explode("/", $SavedUri);
				json_encode($RequestPath);
				json_encode($SavedPath);
				//Check whether they have equal parts
				if(count($RequestPath)!=count($SavedPath)) {
					return false;
				}
				//Check each part
				for($i=0;$i<count($RequestPath);$i++) {
					if(
						!preg_match('/(\{)([a-zA-Z0-9_]+)(\})/', $SavedPath[$i]) &&
						!preg_match('/(\*+)/', $SavedPath[$i]) &&
						($SavedPath[$i]!=$RequestPath[$i])
					) {
						//No match
						//No Url variable
						//No any char
						return false;
					} else if(preg_match('/(\{)([a-zA-Z0-9_]+)(\})/', $SavedPath[$i])) {
						//Url Variable
						self::$_Vars[substr($SavedPath[$i],1,count($SavedPath[$i])-2)] = $RequestPath[$i];
					}
				}
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
				} else {
					//GET
					$SavedRequests = self::$_Get;
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
?>