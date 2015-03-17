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
			public static function Post($Handle,$Route=false,$Alias=false) {
				$h; if(is_string($Handle)) {
					$h = new Route($Handle, $Route, $Alias);
				} elseif($Handle instanceof  Route) { $h = $Handle; }
				//add route to post
				self::$_Post[] = $h;
				//return handle
				return $h;
			}
			//Get route
			public static function Get($Handle,$Route=false,$Alias=false) {
				$h; if(is_string($Handle)) {
					$h = new Route($Handle, $Route, $Alias);
				} elseif($Handle instanceof  Route) { $h = $Handle; }
				//add route to post
				self::$_Get[] = $h;
				//return handle
				return $h;
			}
			//Any route
			public static function Any($Handle,$Route=false,$Alias=false) {
				$h; if(is_string($Handle)) {
					$h = new Route($Handle, $Route, $Alias);
				} elseif($Handle instanceof  Route) { $h = $Handle; }
				//add route to post
				self::$_Any[] = $h;
				//return handle
				return $h;
			}
			//Error routes
			public static function AppError($Handle,$Route=false,$Alias=false) {
				$h; if(is_string($Handle)) {
					$h = new Route($Handle, $Route, $Alias);
				} elseif($Handle instanceof  Route) { $h = $Handle; }
				//add route to post
				self::$_Error[] = $h;
				//return handle
				return $h;
			}
			//Set default route
			public static function Def($Route, $Alias=false) {
				self::$_Default = new Route(false, $Route, $Alias);
				return self::$_Default;
			}
			//Set home route
			public static function Home($Route, $Alias=false) {
				self::$_Home = new Route('', $Route, $Alias);
				return self::$_Home;
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
			//Execute error 
			public static function ThrowError($Error) {
				//Check whether error route has been set
				foreach(self::$_Error as $Route) {
					if($Route->GetHandle()===$Error) {$Route->Load();return true;break;}
				}
				return false;
			}
			//Post configuration
			public static function PostConfiguration() {
				$Request = $_SERVER['REQUEST_URI'];
				URL::SetRequest($Request);
				//Step one check home
				if(isset(self::$_Home)&&self::$_Home->IsMatch('')) {
					self::$_Home->Load();
					return true;
				}
				//Step two, check Any Routes
				foreach(self::$_Any as $Route) {
					//Is Match?
					if($Route->IsMatch($Request)) {
						//Execute route
						$Route->Load();
						return true;
					}
				}
				//Step three check for post or get
				$SavedRequests=array();
				if($_SERVER["REQUEST_METHOD"]=="POST") {$SavedRequests = self::$_Post;URL::SetMethod("POST");}
				else{$SavedRequests = self::$_Get;URL::SetMethod("GET");}
				//Execute $SavedRequests
				foreach($SavedRequests as $Route) {
					//Is Match?
					if($Route->IsMatch($Request)) {
						//Execute route
						$Route->Load();
						return true;
					}
				}
				//No routes comply
				//Try for a not found error
				if(!App::Error(Error::NotFound)) {
					//Check for default route
					if(isset(self::$_Default)) {
						//Execute default route
						self::$_Default->Load();
						return true;
					}
				}
				return false;
			}
		}
	}