<?php
	namespace Ood
	{
		use \Ood as Ood;
		use \PDO as PDO;
		//For managing different database connections (or just one)
		class Connection
		{
			private $_Host = "localhost";
			private $_Engine = "mysql";
			private $_Database = "";
			private $_Username = "root";
			private $_Password = "";
			private $_Charset = "utf8";
			private $_Collation = "utf8_unicode_ci";
		
			public function __construct($options = array()) {
				//Set any given parameter options
				foreach($options as $key => $value) {
					if(property_exists($this, "_$key")) {
						$this->{"_$key"} = $value;
					}
				}
			}
			
			public function __get($name) {
				return $this->{"_$name"};
			}
		}
		
		class ConnectionManager
		{
			private static $_Connections = array();
			
			public static function AddConnection($name, $options = array()) {
				Self::$_Connections[$name] = new Connection($options);
			}
			
			public static function GetConnection($name) {
				if(array_key_exists($name, Self::$_Connections)) {
					return Self::$_Connections[$name];
				}
				return false;
			}
			
			public static function Exists($name) {
				return array_key_exists($name, Self::$_Connections);
			}
		}
	
		//Table builder
		class Table
		{
		
		}
	
		//Query builder and executor
		class Query
		{
			private $_Table;
			private $_Sql;
			private $_Parameters;
			private $_IsDistinct;
			private $_Data;
			private $_RowCount;
			
			public function __construct() {
				$this->_Table = "";
				$this->_Sql = "";
				$this->_Parameters = array();
				$this->_IsDistinct = false;
				$this->_Data = array();
				$this->_RowCount = 0;
			}
			//Getters
			public function __get($name) {
				if(property_exists($this,"_$name")) {
					return $this->{"_$name"};
				}
				return false;
			}
			public function __set($name,$value) {
				if(property_exists($this,"_$name")) {
					$this->{"_$name"}=$value;
				}
			}
			//Table change
			public function Table($table_name) {
				$this->_Table = $table_name;
			}
			//Select statement
			public function Distinct() {
				$this->_IsDistinct = true;
			}
			public function NotDistinct() {
				$this->_IsDistinct = false;
			}
			public function Select($columns) {
				//Reset previous parameters
				$this->_Parameters = array();
				//Start SELECT statement
				$this->_Sql = "SELECT ";
				if($this->_IsDistinct) {
					$this->_Sql .= "DISTINCT ";
				}
				//Add column names
				if(gettype($columns)=="string") {
					$this->_Sql .= "$columns ";
				} else {
					for($i=0;$i<count($columns);$i++) {
						if($i!=0) { $this->_Sql .= ","; }
						$this->_Sql .= $columns[$i]." ";
					}
				}
				//Add FROM statement
				$this->_Sql .= "FROM ".$this->_Table." ";
			}
			//Update statement
			public function Update($column,$value) {
				//Reset parameter from previous sql
				$this->_Parameters = array();
				//Start update statement
				$this->_Sql = "UPDATE ".$this->_Table." SET $column = ? ";
				$this->_Parameters[] = $value;
			}
			public function AndUpdate($column,$value) {
				$this->_Sql .=", $column = ? ";
				$this->_Parameters[] = $value;
			}
			//Insert statement
			public function Insert($key_vals) {
				//Reset parameters from previous sql
				$this->_Parameters = array();
				//Start the insert statement
				$ValuesString="(";
				$this->_Sql = "INSERT INTO ".$this->_Table." ";
				//Build the statement
				$ArrayKeys=array_keys($key_vals);
				if($ArrayKeys!==range(0,count($ArrayKeys)-1)) {
					//Associative array -> column names given
					//Start column name summation + add to ValuesString
					for($i=0;$i<count($ArrayKeys);$i++) {
						//Add commas
						if($i!=0) { $this->_Sql .= ","; }
						if($i!=0) { $ValuesString .= ","; }
						//Change SQL statement
						$this->_Sql .= $ArrayKeys[$i]." ";
						$this->_Parameters[] = $key_vals[$ArrayKeys[$i]];
						//Change ValuesString
						$ValuesString.="? ";
					}
					//Finish statement
					$this->_Sql.=") VALUES $ValuesString )";
				} else {
					//Sequential array -> no column names given
					for($i=0;$i<count($ArrayKeys);$i++) {
						//Add commas
						if($i!=0) { $ValuesString.=","; }
						$this->_Parameters[] = $key_vals[$ArrayKeys[$i]];
						//Change ValuesString
						$ValuesString.="? ";
					}
					//Finish statement
					$this->_Sql.="VALUES $ValuesString )";
				}
			}
			//Delete statement
			public function Delete() {
				//Reset parameter from previous sql
				$this->_Parameters = array();
				//Start delete statement
				$this->_Sql = "DELETE FROM ".$this->_Table." ";
			}
			//Where statements
			public function Where($column,$expression,$value) {
				$this->_Sql .= "WHERE $column $expression ? ";
				$this->_Parameters[] = $value;
			}
			public function AndWhere($column,$expression,$value) {
				$this->_Sql .= "AND $column $expression ? ";
				$this->_Parameters[] = $value;
			}
			public function OrWhere($column,$expression,$value) {
				$this->_Sql .= "OR $column $expression ? ";
				$this->_Parameters[] = $value;
			}
			//Joins
			public function InnerJoin($table_name) {
				$this->_Sql.="INNER JOIN $table_name ";
			}
			public function On($left_hand,$expression,$right_hand) {
				$this->_Sql.="ON $left_hand $expression $right_hand ";
			}
			//Order
			public function OrderBy($column,$order_flag="") {
				$this->_Sql.="ORDER BY $column $order_flag ";
			}
			public function AndOrderBy($column,$order_flag="") {
				$this->_Sql.=",$column $order_flag ";
			}
			//Grouping
			public function GroupBy($column) {
				$this->_Sql .= "GROUP BY $column ";
			}
			public function AndGroupBy($column) {
				$this->_Sql .= ", $column ";
			}
		}
	
		//For managing sessions in one Ood instance
		class Session 
		{
			//All executed queries in this session
			private $_QueryHistory = array();
			//All created tables in this session
			private $_TableHistory = array();
			
			//Constructor
			public function __construct() {
				$this->_QueryHistory[] = new Query();
				$this->_TableHistory[] = new Table();
			}
			
			//Queries
			public function Query() {
				return $this->_QueryHistory[count($this->_QueryHistory)-1];
			}
			public function AddQuery() {
				$this->_QueryHistory[] = new Query();
			}
			public function PreviousQuery() {
				return $this->_QueryHistory[count($this->_QueryHistory)-2];
			}
			public function AllQueries() {
				return $this->_QueryHistory;
			}
			
			//Tables
			public function CreatedTable() {
				return $this->_TableHistory[count($this->_TableHistory)-1];
			}
			public function CreateTable($name) {
				$this->_TableHistory[] = new Table($name);
			}
			public function AllCreatedTables() {
				return $this->_TableHistory;
			}
		}
		
		class SessionManager
		{
			private $_CurrentSession;
			private $_Sessions = array();
			
			public function __construct() {
				$this->_CurrentSession = 0;
				$this->_Sessions[] = new Session();
			}
			
			public function Add($key) {
				$this->_Sessions[$key] = new Session();
			}
			
			public function Load($key) {
				if(array_key_exists($key, $this->_Sessions)) {
					$this->_CurrentSession = $key;
				}
			}
			
			public function Delete($key) {
				if(array_key_exists($key, $this->_Sessions)) {
					unset($this->_Sessions[$key]);
				}
			}
			
			public function Current() {
				return $this->_Sessions[$this->_CurrentSession];
			}
		}
		
		//Actual ood instance
		class Instance
		{
			private $_PDO;
			private $_Table;
			private $_ConnectionName;
			private $_SessionManager;
			
			public function __construct($connection_name) {
				$this->_ConnectionName = $connection_name;
				$this->_Table = "";
				//Connect to PDO
				$Connection = ConnectionManager::GetConnection($connection_name);
				try {
					$this->_PDO = new PDO($Connection->Engine.":dbname=".$Connection->Database.";host=".$Connection->Host.";charset=".$Connection->Charset,
											$Connection->Username,
											$Connection->Password);
				} catch(PDOException $e) {
					App::Error(Error::DatabaseConnectionFailed);
				}
				//Make a new sessionmanager
				$this->_SessionManager = new SessionManager();
			}
			//Methods to forward to other classes
			//SESSIONMANAGER
			public function AddSession($key) {
				$this->_SessionManager->Add($key);
			}
			public function LoadSession($key) {
				$this->_SessionManager->Load($key);
			}
			public function DeleteSession($key) {
				$this->_SessionManager->Delete($key);
			}
			public function CurrentSession() {
				return $this->_SessionManager->Current();
			}
			//SESSION
			public function GetQuery() {
				return $this->CurrentSession()->Query();
			}
			public function AddQuery() {
				//Add new Query
				$this->CurrentSession()->AddQuery();
				//Set all queries to currently selected table
				$this->Table($this->_Table);
			}
			public function PreviousQuery() {
				return $this->CurrentSession()->PreviousQuery();
			}
			public function AllQueries() {
				return $this->CurrentSession()->AllQueries();
			}
			public function CreatedTable() {
				return $this->CurrentSession()->CreatedTable();
			}
			public function CreateTable($name) {
				$this->CurrentSession()->CreateTable($name);
			}
			public function AllCreatedTables() {
				return $this->CurrentSession()->AllCreatedTables();
			}
			//Queries
			public function Table($table_name) {
				//Set table for queries
				$queries = $this->AllQueries();
				foreach($queries as $query) {
					$query->Table($table_name);
				}
				//Set own table name
				$this->_Table = $table_name;
			}
			public function Distinct() {
				$this->GetQuery()->Distinct();
			}
			public function NotDistinct() {
				$this->GetQuery()->NotDistinct();
			}
			public function Select($columns) {
				$this->GetQuery()->Select($columns);
			}
			public function Update($column,$value) {
				$this->GetQuery()->Update($column,$value);
			}
			public function AndUpdate($column,$value) {
				$this->GetQuery()->AndUpdate($column,$value);
			}
			public function Insert($key_vals) {
				$this->GetQuery()->Insert($key_vals);
			}
			public function Delete() {
				$this->GetQuery()->Delete();
			}
			public function Where($column,$expression,$value) {
				$this->GetQuery()->Where($column,$expression,$value);
			}
			public function AndWhere($column,$expression,$value) {
				$this->GetQuery()->AndWhere($column,$expression,$value);
			}
			public function OrWhere($column,$expression,$value) {
				$this->GetQuery()->OrWhere($column,$expression,$value);
			}
			public function InnerJoin($table_name) {
				$this->GetQuery()->InnerJoin($table_name);
			}
			public function On($left_hand,$expression,$right_hand) {
				$this->GetQuery()->On($left_hand,$expression,$right_hand);
			}
			public function OrderBy($column,$order_flag="") {
				$this->GetQuery()->OrderBy($column,$order_flag);
			}
			public function AndOrderBy($column,$order_flag="") {
				$this->GetQuery()->AndOrderBy($column,$order_flag);
			}
			public function GroupBy($column) {
				$this->GetQuery()->GroupBy($column);
			}
			public function AndGroupBy($column) {
				$this->GetQuery()->AndGroupBy($column);
			}
			//Query execution
			public function Execute($sql="",$parameters=array()) {
				$PreparedQuery;
				if((strlen($sql)==0)&&(strlen($this->GetQuery()->Sql)>0)) {
					//Prepare query from prepared statement in Query object
					$PreparedQuery = $this->_PDO->prepare($this->GetQuery()->Sql);
					$PreparedQuery->execute($this->GetQuery()->Parameters);
				} else {
					//Prepare query from given query
					$PreparedQuery = $this->_PDO->prepare($sql);
					$PreparedQuery->execute($parameters);
				}
				//Set data and rowcount
				$this->GetQuery()->Data = $PreparedQuery->fetchAll(PDO::FETCH_ASSOC);
				$this->GetQuery()->RowCount = $PreparedQuery->rowCount();
				//Reset sql and parameters
				$this->GetQuery()->Sql = "";
				$this->GetQuery()->Parameters = array();
			}
		}
		
		//For multiton instantiation
		class Multiton
		{
			private static $_OodInstances = array();
			public static function Create($connection_name) {
				//Check connection name
				if(!ConnectionManager::Exists($connection_name)) {
					ConnectionManager::AddConnection($connection_name);
				}
				//Create new Ood object
				$OodObject = new Ood();
				//Create new instance
				$Instance = new Instance($connection_name);
				//Add Instance to Multiton instances
				Self::$_OodInstances[spl_object_hash($OodObject)] = $Instance;
				//Return OodObject
				return $OodObject;
			}
			public static function GetInstance($key) {
				if(array_key_exists($key,Self::$_OodInstances)) {
					return Self::$_OodInstances[$key];
				}
				return false;
			}
			public static function DeleteInstance($key) {
				if(array_key_exists($key,Self::$_OodInstances)) {
					unset(Self::$_OodInstances[$key]);
					return true;
				}
				return false;
			}
		}
	}
	namespace
	{
		use Ood\ConnectionManager as ConnectionManager;
		use Ood\Multiton as Multiton;
		class Ood
		{
			//MAGIC
			public function __get($name) {
				//Return Data
				$QueryData = $this->GetQuery()->Data;
				foreach($QueryData as $Data) {
					if(array_key_exists($name,$Data)) {
						return $Data[$name];
					}
				}
				return false;
			}
			public function __set($name, $value) {
				//Set returned query data
				$QueryData = $this->GetQuery()->Data;
				for($i=0;$i<count($QueryData);$i++) {
					if(array_key_exists($name,$QueryData[$i])) {
						$QueryData[$i][$name] = $value;
					}
				}
				$this->GetQuery()->Data = $QueryData;
				return true;
			}
			//Save Query
			public function Save() {
				
			}
			//ConnectionManager methods
			public static function AddConnection($name, $options = array()) {
				ConnectionManager::AddConnection($name, $options);
			}
			public static function GetConnection($name) {
				return ConnectionManager::GetConnection($name);
			}
			//Connect methods
			public static function Connect($connection_name) {
				return Multiton::Create($connection_name);
			}
			//Constructor / destructor
			public function __destruct() {
				Multiton::DeleteInstance(spl_object_hash($this));
			}
			//Methods to forward to back-end instance
			//GET OWN INSTANCE
			public function Instance() {
				return Multiton::GetInstance(spl_object_hash($this));
			}
			//SESSIONMANAGER
			public function AddSession($key) {
				$this->Instance()->AddSession($key);
				return $this;
			}
			public function LoadSession($key) {
				$this->Instance()->LoadSession($key);
				return $this;
			}
			public function DeleteSession($key) {
				$this->Instance()->Delete($key);
				return $this;
			}
			public function CurrentSession() {
				return $this->Instance()->CurrentSession();
			}
			//SESSION
			public function GetQuery() {
				return $this->Instance()->GetQuery();
			}
			public function AddQuery() {
				$this->Instance()->AddQuery();
				return $this;
			}
			public function PreviousQuery() {
				return $this->Instance()->PreviousQuery();
			}
			public function AllQueries() {
				return $this->Instance()->AllQueries();
			}
			public function CreatedTable() {
				return $this->Instance()->CreatedTable();
			}
			public function CreateTable($name) {
				$this->Instance()->CreateTable($name);
			}
			public function AllCreatedTables() {
				return $this->Instance()->AllCreatedTables();
			}
			//Queries
			public function Table($table_name) {
				$this->Instance()->Table($table_name);
				return $this;
			}
			public function Distinct() {
				$this->Instance()->Distinct();
				return $this;
			}
			public function NotDistinct() {
				$this->Instance()->NotDistinct();
				return $this;
			}
			public function Select($columns) {
				$this->Instance()->Select($columns);
				return $this;
			}
			public function Update($column,$value) {
				$this->Instance()->Update($column,$value);
				return $this;
			}
			public function AndUpdate($column,$value) {
				$this->Instance()->AndUpdate($column,$value);
				return $this;
			}
			public function Insert($key_vals) {
				$this->Instance()->Insert($key_vals);
				return $this;
			}
			public function Delete() {
				$this->Instance()->Delete();
				return $this;
			}
			public function Where($column,$expression,$value) {
				$this->Instance()->Where($column,$expression,$value);
				return $this;
			}
			public function AndWhere($column,$expression,$value) {
				$this->Instance()->AndWhere($column,$expression,$value);
				return $this;
			}
			public function OrWhere($column,$expression,$value) {
				$this->Instance()->OrWhere($column,$expression,$value);
				return $this;
			}
			public function InnerJoin($table_name) {
				$this->Instance()->InnerJoin($table_name);
				return $this;
			}
			public function On($left_hand,$expression,$right_hand) {
				$this->Instance()->On($left_hand,$expression,$right_hand);
				return $this;
			}
			public function OrderBy($column,$order_flag="") {
				$this->Instance()->OrderBy($column,$order_flag);
				return $this;
			}
			public function AndOrderBy($column,$order_flag="") {
				$this->Instance()->AndOrderBy($column,$order_flag);
				return $this;
			}
			public function GroupBy($column) {
				$this->Instance()->GroupBy($column);
				return $this;
			}
			public function AndGroupBy($column) {
				$this->Instance()->AndGroupBy($column);
				return $this;
			}
			public function All() {
				$this->Instance()->GetQuery()->Select("*");
				$this->Instance()->Execute();
				return $this->Instance()->GetQuery()->Data;
			}
			public function Execute($sql="",$parameters=array()) {
				$this->Instance()->Execute($sql,$parameters);
				return $this->Instance()->GetQuery()->Data;
			}
		}
	}
?>