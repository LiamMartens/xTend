<?php
	abstract class Router
	{
		private static $_DefaultRoute;
		private static $_Home;
		private static $_PostRoutes = array();
		private static $_GetRoutes = array();
		private static $_AnyRoutes = array();
		private static $_ErrorRoutes = array();
		private static $_TempVariables = array();
		private static $_Variables = array();
		
		public static function Variables() {
			return Self::$_Variables;
		}
		
		public static function Variable($key) {
			if(array_key_exists($key,Self::$_Variables)) {
				return Self::$_Variables[$key];
			}
			return false;
		}
		
		public static function Def($route) {
			Self::$_DefaultRoute = $route;
		}
		
		public static function Post($request,$route) {
			Self::$_PostRoutes[trim($request,"/")] = $route;
		}
		
		public static function Get($request,$route) {
			Self::$_GetRoutes[trim($request,"/")] = $route;
		}
		
		public static function Any($request,$route) {
			Self::$_AnyRoutes[trim($request,"/")] = $route;
		}
		
		public static function AppError($error,$route) {
			Self::$_ErrorRoutes[$error] = $route;
		}
		
		public static function Home($route) {
			Self::$_Home = $route;
		}
		
		public static function Restrict($function,$routes) {
			if($function()===true) {
				$routes();
			}
		}
		
		//Match the url
		private static function IsMatch($request,$saved_request) {
			//Before every Math request clear the current temp url variables
			Self::$_TempVariables = array();
			$RequestPath = explode("/",trim(strtolower($request),"/"));
			$SavedPath = explode("/", $saved_request);
			//Step one, check n° of parts in both Paths. if the request path is larger than the saved path there is no match
			if(count($RequestPath)!=count($SavedPath)) {
				return false;
			}
			//Step two check each separate part
			for($i = 0; $i < count($SavedPath); $i++) {
				//
				// No Named Variable
				// No Any Text
				//
				if(
					((substr($SavedPath[$i],0,1)!="{")||(substr($SavedPath[$i],strlen($SavedPath[$i])-1)!="}")) &&
					($SavedPath[$i]!="*") &&
					($SavedPath[$i]!=$RequestPath[$i])
				) {
					return false;
				} else if((substr($SavedPath[$i],0,1)=="{")&&(substr($SavedPath[$i],strlen($SavedPath[$i])-1)=="}")) {
					//Named url variable->save it
					Self::$_TempVariables[substr(substr($SavedPath[$i],1),0,strlen($SavedPath[$i])-2)] = $RequestPath[$i];
				}
			}
			//Set Current Variables to Temp Variables because it was matched
			Self::$_Variables = Self::$_TempVariables;
			return true;
		}
		
		private static function IsHome($request) {
			$RequestPath = explode("/",trim($request,"/"));
			return ((count($RequestPath)==1)&&(trim($RequestPath[0])==""));
		}
		
		private static function ExecuteRequest($route) {
			switch(gettype($route)) {
				case "object":
					$route();
					break;
				case "string":
					echo $route;
					break;
				case "array":
					if(array_key_exists("Model",$route)) {
						Models::Init($route["Model"]);
					}
					if(array_key_exists("Controller",$route)) {
						if(array_key_exists("Data",$route)) {
							Controllers::Init($route["Controller"],$route["Data"]);
						} else {
							Controllers::Init($route["Controller"]);
						}
					}
					if(array_key_exists("View",$route)) {
						if(array_key_exists("Data",$route)&&(!array_key_exists("Controller",$route))) {
							Views::Init($route["View"],$route["Data"]);
						} else {
							Views::Init($route["View"]);
						}
					}
					break;
			}
		}
		
		public static function Error($error) {
			if(array_key_exists($error,Self::$_ErrorRoutes)) {
				Self::ExecuteRequest(Self::$_ErrorRoutes[$error]);
				return true;
			}
			return false;
		}
		
		public static function PostConfigInitialize() {
			$RequestUrl = $_SERVER['REQUEST_URI'];
			//Step one check whether it is home and whether the home route exists
			if(Self::IsHome($RequestUrl)&&Self::$_Home) {
				Self::ExecuteRequest(Self::$_Home);
				return true;
			}
			//Step two, check AnyRoutes
			foreach(Self::$_AnyRoutes as $Key => $Route) {
				if(Self::IsMatch($RequestUrl,$Key)) {
					Self::ExecuteRequest($Route);
					return true;
				}
			}
			//Step three check whether get or post was passed and execute accordingly
			$headers = getallheaders();
			if(
				array_key_exists("Content-Length",$headers) &&
				array_key_exists("Origin",$headers) &&
				array_key_exists("Content-Type",$headers) &&
				array_key_exists("Referer",$headers)
			) {
				//POST
				foreach(Self::$_PostRoutes as $Key => $Route) {
					if(Self::IsMatch($RequestUrl,$Key)) {
						Self::ExecuteRequest($Route);
						return true;
					}
				}
			} else {
				//GET
				foreach(Self::$_GetRoutes as $Key => $Route) {
					if(Self::IsMatch($RequestUrl,$Key)) {
						Self::ExecuteRequest($Route);
						return true;
					}
				}
			}
			//Not found error or try default
			if((!App::Error(Error::NotFound))&&Self::$_DefaultRoute) {
				Self::ExecuteRequest(Self::$_DefaultRoute);
			}
			return false;
		}
	}
?>