<?php
	namespace xTend
	{
		class Route
		{
			private $_Handle;
			private $_Route;
			private $_Alias;
			private $_Data;

			//getters
			public function GetHandle() { return $this->_Handle; }
			public function GetRoute() { return $this->_Route; }
			public function GetAlias() { return $this->_Alias; }
			public function GetData() { return $this->_Data; }
			//setters
			public function Handle($Handle) {
				$this->_Handle = $Handle;
				if(is_string($this->_Handle)) { $this->_Handle = trim($this->_Handle,"/"); }
				return $this;
			}
			public function Route($Route) {
				$this->_Route = $Route;
				return $this;
			}
			public function Alias($Alias) {
				$this->_Alias = $Alias;
				return $this;
			}
			public function Data($Data) {
				$this->_Data = $Data;
				return $this;
			}
			//constructor
			public function __construct($Handle, $Route, $Alias = false) {
				$this->Handle($Handle);
				$this->Route($Route);
				$this->Data(array());
				if($Alias!==false) { $this->Alias($Alias); }
				//check whether GET variables are accepted
				if((strrpos($this->_Handle, "+{get}")==strlen($this->_Handle)-6)||
					(strrpos($this->_Handle, "+{GET}")==strlen($this->_Handle)-6)) {
					$this->_Handle = substr($this->_Handle, 0, strlen($this->_Handle)-6);
					$rx_matches; $exHandle = explode("/", $this->_Handle);
					if(preg_match('/^(rx)(\{)([a-zA-Z0-9_]+)(\})(\{)(.*)(\})$/', $exHandle[count($exHandle)-1], $rx_matches)) {
						$exHandle[count($exHandle)-1] = "rx{".$rx_matches[3]."}{".("(".$rx_matches[6].")(\\?)(.*)")."}";
					} elseif(preg_match('/^(rx)(\{)(.*)(\})$/', $exHandle[count($exHandle)-1], $rx_matches)) {
						$exHandle[count($exHandle)-1] = "rx{".("(".$rx_matches[3].")(\\?)(.*)")."}";
					} elseif(!preg_match('/^(\{)([a-zA-Z0-9_]+)(\})$/', $exHandle[count($exHandle)-1])) {
						$exHandle[count($exHandle)-1] = "rx{(".$exHandle[count($exHandle)-1].")(\\?)(.*)}";
					}
					$this->_Handle="";
					foreach ($exHandle as $part) { $this->_Handle.=$part."/"; }
					$this->_Handle=rtrim($this->_Handle,"/");
				}
			}
			//other methods
			public function To() {
				if(is_string($this->_Handle)) {
					URL::to($this->_Handle);
					return true;
				}
				return false;
			}
			public function Load() {
				if(is_callable($this->_Route)) {
					//call function
					call_user_func($this->_Route);
					return true;
				} elseif (is_string($this->_Route)) {
					//just a string
					echo $this->_Route;
					return true;
				} elseif(is_array($this->_Route)) {
					//check for controller or view data
					$Data = array();
					if(array_key_exists("Data", $this->_Route)) {
						$Data = $this->_Route["Data"];
					} else { $Data = $this->_Data; }
					//Check for model existance
					if(array_key_exists("Model",$this->_Route)) {
						Models::Initialize($this->_Route["Model"]);
					}
					//Check for controller existance
					if(array_key_exists("Controller",$this->_Route)) {
						Controllers::Initialize($this->_Route["Controller"],$Data);
					}
					//Check for view existance
					if(array_key_exists("View",$this->_Route)) {
						//Don't pass data to the view when there is a controller available
						if(!array_key_exists("Controller",$this->_Route)) {
							Views::Initialize($this->_Route["View"],$Data);
						} else {
							Views::Initialize($this->_Route["View"]);
						}
					}
					return true;
				}
				return false;
			}
			public function IsMatch($Request) {
				if(is_string($this->_Handle)) {
					//explode both urls
					$exRequest = explode("/", $Request);
					$exHandle = explode("/", $this->_Handle);
					//check for equal parts
					if(count($exRequest)!==count($exHandle)) { return false; }
					//check each part
					$rx_matches;
					for($i=0;$i<count($exRequest);$i++) {
						//is regexed variable
						if(preg_match('/^(rx)(\{)([a-zA-Z0-9_]+)(\})(\{)(.*)(\})$/', $exHandle[$i], $rx_matches)&&
							preg_match('/'.$rx_matches[6].'/', $exRequest[$i])) {
							URL::SetParameter($rx_matches[3], $exRequest[$i]);
						} elseif(preg_match('/^(\{)([a-zA-Z0-9_]+)(\})$/', $exHandle[$i], $rx_matches)) {
							//is non regexed variable
							URL::SetParameter($rx_matches[2], $exRequest[$i]);
						} elseif(
							!((preg_match('/^(rx)(\{)(.*)(\})$/', $exHandle[$i], $rx_matches)&&
							preg_match('/'.$rx_matches[3].'/', $exRequest[$i])) ||
							preg_match('/(\*+)/', $exHandle[$i]) ||
							($exHandle[$i]==$exRequest[$i]))
						) {
							return false;
						}
					}
					//set saved url
					URL::SetRoute($this->_Handle);
					return true;
				}
				return false;
			}
		}
	}