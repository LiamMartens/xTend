<?php
	//Object oriented database wrapper
	class Ood2
	{
		//Multiton instantiation
		private static $_Instances = array();
		//For configuration possibilites
		private static $AllowedParameters = array(
			"Host",
			"Engine",
			"Database",
			"Username",
			"Password",
			"Charset",
			"Collation"
		);
		private static $_sHost = "localhost";
		private static $_sEngine = "mysql";
		private static $_sDatabase = "";
		private static $_sUsername = "root";
		private static $_sPassword = "";
		private static $_sCharset = "utf8";
		private static $_sCollation = "utf8_unicode_ci";
		public static function Host($host) { Self::$_sHost = $host; }
		public static function Engine($engine) { Self::$_sEngine = $engine; }
		public static function Database($database) { Self::$_sDatabase = $database; }
		public static function Username($username) { Self::$_sUsername = $username; }
		public static function Password($password) { Self::$_sPassword = $password; }
		public static function Charset($charset) { Self::$_sCharset = $charset; }
		public static function Collation($collation) { Self::$_sCollation = $collation; }
		//Non static variables
		//Username and Password are left out because these don't need to be stored
		protected $_PDO;
		protected $_Host;
		protected $_Engine;
		protected $_Database;
		protected $_Charset;
		protected $_Collation;
		//For connecting and initializing
		public static function Connect($options = array()) {
			$Host;
			$Engine;
			$Database;
			$Username;
			$Password;
			$Charset;
			$Collation;
			foreach(Self::$AllowedParameters as $Parameter) {
				if(array_key_exists($Parameter, $options)) {
					//Use the parameter given
					${$Parameter}=$options[$Parameter];
				} else {
					//Use the static configuration
					${$Parameter}=Self::${"_s".$Parameter};
				}
			}
			//Build the connection to check the multiton instances
			$dsn = "$Engine:dbname=$Database;host=$Host;charset=$Charset";
			//Check multiton instances
			if(!array_key_exists($dsn,Self::$_Instances)) {
				//Return new instance of Ood
				Self::$_Instances[$dsn] = new Ood($Host, $Engine, $Database, $Username, $Password, $Charset, $Collation);
			}
			//Start session 0
			Self::$_Instances[$dsn]->Session(0);
			//Return instance
			return Self::$_Instances[$dsn];
		}
		private function __construct($host,$engine,$database,$username,$password,$charset,$collation) {
			//Set variables
			$this->_Host = $host;
			$this->_Engine = $engine;
			$this->_Database = $database;
			$this->_Charset = $charset;
			$this->_Collation = $collation;
			//Build the connection string
			$dsn = "$engine:dbname=$database;host=$host;charset=$charset";
			//Connect to the database or throw App Error
			try {
				$this->_PDO = new PDO($dsn,$username,$password);
			} catch(PDOException $e) {
				App::Error(Error::DatabaseConnectionFailed);
			}
		}
		//For models
		protected function ConnectModel($options = array()) {
			$Host;
			$Engine;
			$Database;
			$Username;
			$Password;
			$Charset;
			$Collation;
			foreach(Self::$AllowedParameters as $Parameter) {
				if(array_key_exists($Parameter, $options)) {
					${$Parameter}=$options[$Parameter];
				} else {
					${$Parameter}=Self::${"_s".$Parameter};
				}
			}
			$dsn = "$Engine:dbname=$Database;host=$Host;charset=$Charset";
			try {
				$this->_PDO = new PDO($dsn,$Username,$Password);
			} catch(PDOException $e) {
				App::Error(Error::DatabaseConnectionFailed);
			}
			$this->Session(0);
			return $this;
		}
		
		//Session management --> For multiton instantiation but still keep separate data available
		private $_AllowedSessionParameters = array(
			"Table",
			"LastParameters",
			"OriginalLastData",
			"LastQuery",
			"LastData",
			"LastRowCount",
			"NewParameters",
			"NewQuery",
			"IsDistinct",
			"TableCreationQuery"
		);
		private $_Session = 0;
		private $_Sessions = array();
		public function Session($key) {
			//Save current session if there are any sessions to save
			if(count($this->_Sessions)>0) {
				foreach($this->_AllowedSessionParameters as $Parameter) {
					$this->_Sessions[$this->_Session][$Parameter] = $this->{"_".$Parameter};
				}
			}
			//Select new session
			if(array_key_exists($key,$this->_Sessions)) {
				//Session exists, select that session
				$this->_Session = $key;
				foreach($this->_Sessions[$key] as $Parameter => $Value) {
					$this->{"_".$Parameter} = $Value;
				}
			} else {
				//Session does not exist create new session and select it
				$this->_Session = $key;
				$this->_Sessions[$key]=array();
				foreach($this->_AllowedSessionParameters as $Parameter) {
					$this->_Sessions[$key][$Parameter]=null;
				}
			}
			return $this;
		}
		//Querying and data fetching variables
		protected $_Table="";
		protected $_LastParameters=array();
		protected $_LastQuery="";
		protected $_OriginalLastData=array();
		protected $_LastData=array();
		protected $_LastRowCount;
		protected $_NewParameters=array();
		protected $_NewQuery="";
		protected $_IsDistinct=false;
		//Methods
		public function Table($table) {
			$this->_Table = $table;
			return $this;
		}
		public function Query() {
			return $this->_LastQuery;
		}
		public function Data() {
			return $this->_LastData;
		}
		public function RowsCount() {
			return $this->_LastRowCount;
		}
		public function NewQuery() {
			return $this->_NewQuery;
		}
		//Overload
		public function __get($name) {
			foreach($this->_LastData as $Data) {
				if(array_key_exists($name,$Data)) {
					return $Data[$name];
				}
			}
			return false;
		}
		public function __set($name,$value) {
			for($i=0;$i<count($this->_LastData);$i++) {
				if(array_key_exists($name,$this->_LastData[$i])) {
					$this->_LastData[$i][$name]=$value;
				}
			}
			return true;
		}
		public function Save() {
			$Queries=array();
			for($j=0;$j<count($this->_LastData);$j++) {
				$Data=$this->_LastData[$j];
				$DataKeys = array_keys($Data);
				for($i=0;$i<count($DataKeys);$i++) {
					if($i==0) {
						$this->Update($DataKeys[$i],$Data[$DataKeys[$i]]);
					} else {
						$this->AndUpdate($DataKeys[$i],$Data[$DataKeys[$i]]);
					}
				}
				for($i=0;$i<count($DataKeys);$i++) {
					if($i==0) {
						$this->Where($DataKeys[$i],"=",$this->_OriginalLastData[$j][$DataKeys[$i]]);
					} else {
						$this->AndWhere($DataKeys[$i],"=",$this->_OriginalLastData[$j][$DataKeys[$i]]);
					}
				}
				$Queries[]=array(
					$this->_NewQuery,
					$this->_NewParameters
				);
				$this->_NewQuery="";
				$this->_NewParameters=array();
			}
			foreach($Queries as $Query) {
				$this->Execute($Query[0],$Query[1]);
			}
			return $this;
		}
		//All data
		public function All() {
			return $this->Execute("SELECT * FROM ".$this->_Table);
		}
		//Select
		public function Distinct() {
			$this->_IsDistinct=true;
			return $this;
		}
		public function NotDistinct() {
			$this->_IsDistinct=false;
			return $this;
		}
		public function Select($cols) {
			$this->_NewQuery = "SELECT ";
			if($this->_IsDistinct) {
				$this->_NewQuery.="DISTINCT ";
			}
			if(gettype($cols)=="string") {
				$this->_NewQuery.=$cols." ";
			} else {
				for($i=0;$i<count($cols);$i++) {
					if($i!=0) { $this->_NewQuery.=","; }
					$this->_NewQuery.= $cols[$i];
					$this->_NewQuery.=" ";
				}
			}
			$this->_NewQuery.= "FROM ".$this->_Table." ";
			return $this;
		}
		//Start where normally
		public function Where($column,$expression,$value) {
			$this->_NewQuery .= "WHERE $column $expression ? ";
			$this->_NewParameters[] = $value;
			return $this;
		}
		//Add AND criteria
		public function AndWhere($column,$expression,$value) {
			$this->_NewQuery .= "AND $column $expression ? ";
			$this->_NewParameters[] = $value;
			return $this;
		}
		//Add OR criteria
		public function OrWhere($column,$expression,$value) {
			$this->_NewQuery .= "OR $column $expression ? ";
			$this->_NewParameters[] = $value;
			return $this;
		}
		//Update statement
		public function Update($column,$value) {
			$this->_NewQuery .= "UPDATE ".$this->_Table." SET $column = ? ";
			$this->_NewParameters[] = $value;
			return $this;
		}
		public function AndUpdate($column,$value) {
			$this->_NewQuery .= ", $column = ? ";
			$this->_NewParameters[] = $value;
			return $this;
		}
		//Delete statement
		public function Delete() {
			$this->_NewQuery .= "DELETE FROM ".$this->_Table." ";
			return $this;
		}
		//Insert statement
		public function Insert($keyvalues) {
			$ValuesString="(";
			$this->_NewQuery .= "INSERT INTO ".$this->_Table." ";
			//Build
			$ArrayKeys=array_keys($keyvalues);
			if($ArrayKeys!==range(0,count($keyvalues)-1)) {
				//associative
				$this->_NewQuery.=" ( ";
				for($i=0;$i<count($ArrayKeys);$i++) {
					if($i!=0) { $this->_NewQuery.=", "; }
					if($i!=0) { $ValuesString.=", "; }
					$this->_NewQuery .= $ArrayKeys[$i]." ";
					$this->_NewParameters[] = $keyvalues[$ArrayKeys[$i]];
					$ValuesString.="? ";
				}
				$this->_NewQuery.=") VALUES $ValuesString )";
			} else {
				//Sequential
				for($i=0;$i<count($ArrayKeys);$i++) {
					if($i!=0) { $ValuesString.=", "; }
					$this->_NewParameters[] = $keyvalues[$ArrayKeys[$i]];
					$ValuesString.="? ";
				}
				$this->_NewQuery.="VALUES $ValuesString )";
			}
			return $this;
		}
		//Add flag
		public function InnerJoin($table) {
			$this->_NewQuery .= "INNER JOIN $table ";
			return $this;
		}
		public function On($left_hand,$expression,$right_hand) {
			$this->_NewQuery .= "ON $left_hand $expression $right_hand ";
			return $this;
		}
		public function OrderBy($column,$ordertype="") {
			$this->_NewQuery .= "ORDER BY $column $ordertype ";
			return $this;
		}
		public function AndOrderBy($column,$ordertype="") {
			$this->_NewQuery .= ", $column $ordertype ";
			return $this;
		}
		public function GroupBy($column) {
			$this->_NewQuery.="GROUP BY $column ";
			return $this;
		}
		public function AndGroupBy($column) {
			$this->_NewQuery.=", $column ";
			return $this;
		}
		//Execute statement
		public function Execute($sql="",$parameters=array()) {
			if((strlen($sql)==0)&&(strlen($this->_NewQuery)>0)) {
				//Execute new query
				$PreparedQuery = $this->_PDO->prepare($this->_NewQuery);
				$PreparedQuery->execute($this->_NewParameters);
				//Replace last query
				$this->_LastQuery=$this->_NewQuery;
				$this->_LastData=$PreparedQuery->fetchAll(PDO::FETCH_ASSOC);
				$this->_OriginalLastData=$this->_LastData;
				$this->_LastParameters=$this->_NewParameters;
				$this->_LastRowCount=$PreparedQuery->rowCount();
				//Dismiss new query
				$this->_NewQuery="";
				$this->_NewParameters=array();
			} else {
				//Execute given query
				$PreparedQuery = $this->_PDO->prepare($sql);
				$PreparedQuery->execute($parameters);
				//Replace last query
				$this->_LastQuery = $sql;
				$this->_LastData=$PreparedQuery->fetchAll(PDO::FETCH_ASSOC);
				$this->_OriginalLastData=$this->_LastData;
				$this->_LastParameters=$parameters;
				$this->_LastRowCount=$PreparedQuery->rowCount();
			}
			//return $this
			return $this;
		}
		
		//Table creator
		protected $_TableCreationQuery="";
		protected $_TableColumnNames=array();
		
		public function Column($column,$type) {
			if(array_search($column,$this->_TableColumnNames)==false) {
				$this->_TableCreationQuery .= "$column $type, ";
			}
			return false;
		}
		public function Create($TableName,$Columns) {
			$this->_TableCreationQuery .= "CREATE TABLE $TableName ( ";
			$Columns($this);
			if(count($this->_TableColumnNames)>0) {
				$this->_TableCreationQuery = substr($this->_TableColumnNames,0,strlen($this->_TableCreationQuery)-2);
				$this->_TableCreationQuery .= ")";
				$PreparedQuery = $this->_PDO->prepare($this->_TableCreationQuery);
				$PreparedQuery->execute();
				return true;
			}
			return false;
		}
	}
?>