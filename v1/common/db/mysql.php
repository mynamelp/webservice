<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
if(!defined('__MYSQL__')){
	define('__MYSQL__',1);
	require_once(dirname(__FILE__) . "/../../conf/config.db.php");

	Class MYSQLDB {
		private $link_id;
		private $handle;
		private $is_log;
		private $time;	//

		public function __construct() {
			// time start
			$this->time = $this->microtime_float();
			//
			$this->connect(MYSQL_ADDR, MYSQL_USR, MYSQL_PWD, MYSQL_DB, MYSQL_PCNT);
			$this->is_log = _LOG;
			if($this->is_log){
				$handle = fopen(MYSQL_LOGPATH, "a+");
				$this->handle=$handle;
			}
		}
		
		//make a connection
		public function connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect = MYSQL_PCNT,$charset= MYSQL_CHST) {
			if( $pconnect==0 ) {
				$this->link_id = @mysql_connect($dbhost, $dbuser, $dbpw, true);
				if(!$this->link_id){
					$this->halt("Faile to conect Mysql server.");
				}
			} else {
				$this->link_id = @mysql_pconnect($dbhost, $dbuser, $dbpw);
				if(!$this->link_id){
					$this->halt("Fail to open a persistent connection to MySQL server.");
				}
			}
			if(!@mysql_select_db($dbname,$this->link_id)) {
				$this->halt('Fail to select the Mysql server.');
			}
			@mysql_query("set names ".$charset);
		}
		
		//select 
		public function query($sql) {
			$this->write_log("select ".$sql);
			$query = mysql_query($sql,$this->link_id);
			if(!$query) $this->halt('Query Error: ' . $sql);
			return $query;
		}
		
		//insert
		public function insert($table,$dataArray) {
			$field = "";
			$value = "";
			if( !is_array($dataArray) || count($dataArray)<=0) {
				$this->halt('There is no data to be inserted.');
				return false;
			}
			while(list($key,$val)=each($dataArray)) {
				$field .="$key,";
				$value .="'$val',";
			}
			$field = substr( $field,0,-1);
			$value = substr( $value,0,-1);
			$sql = "insert into $table($field) values($value)";
			$this->write_log("func insert: ".$sql);
			if(!$this->query($sql)) return false;
			return true;
		}
		
		//update
		public function update( $table,$dataArray,$condition="") {
			if( !is_array($dataArray) || count($dataArray)<=0) {
				$this->halt('There is no data to be updated.');
				return false;
			}
			$value = "";
			while( list($key,$val) = each($dataArray))
			$value .= "$key = '$val',";
			$value .= substr( $value,0,-1);
			$sql = "update $table set $value where 1=1 and $condition";
			$this->write_log("func update: ".$sql);
			if(!$this->query($sql)) return false;
			return true;
		}
		
		//delate
		public function delete( $table,$condition="") {
			if( empty($condition) ) {
				$this->halt('There is no data to be delated.');
				return false;
			}
			$sql = "delete from $table where 1=1 and $condition";
			$this->write_log("func delate: ".$sql);
			if(!$this->query($sql)) return false;
			return true;
		}
		
		//get one（MYSQL_ASSOC，MYSQL_NUM，MYSQL_BOTH）				
		public function get_one($sql,$result_type = MYSQL_ASSOC) {
			$query = $this->query($sql);
			$rt =mysql_fetch_array($query,$result_type);
			$this->write_log("func get_one: ".$sql);
			return $rt;
		}

		//get all 
		public function get_all($sql,$result_type = MYSQL_ASSOC) {
			$query = $this->query($sql);
			$i = 0;
			$rt = array();
			while($row =mysql_fetch_array($query,$result_type)) {
				$rt[$i]=$row;
				$i++;
			}
			$this->write_log("func get_all: ".$sql);
			return $rt;
		}

		//fetch collection
		public function fetch_array($query, $result_type = MYSQL_ASSOC){
			$this->write_log("func fetch_array");
			return mysql_fetch_array($query, $result_type);
		}

		//counts
		public function num_rows($results) {
			if(!is_bool($results)) {
				$num = mysql_num_rows($results);
				$this->write_log("获取的记录条数为".$num);
				return $num;
			} else {
				return 0;
			}
		}

		//free result
		public function free_result() {
			$void = func_get_args();
			foreach($void as $query) {
				if(is_resource($query) && get_resource_type($query) === 'mysql result') {
					return mysql_free_result($query);
				}
			}
			$this->write_log("func free_result");
		}

		//get the last inserted data's id
		public function last_inserted_id() {
			$id = mysql_insert_id($this->link_id);
			$this->write_log("func last_inserted_id: ".$id);
			return $id;
		}

		//close connection
		private function close() {
			$this->write_log("Sucess to close Mysql server.");
			return @mysql_close($this->link_id);
		}
		
		public function __destruct() {
			$this->free_result();
			$use_time = ($this-> microtime_float())-($this->time);
			$this->write_log("To fisnish the hull query needs time: ".$use_time);
			if(MYSQL_PCNT){
				$this->close();
			}
			if($this->is_log){
				fclose($this->handle);
			}
		}

		//die and alert error
		private function halt($msg='') {
			$msg .= "\r\n".mysql_error();
			$this->write_log($msg);
			die($msg);
		}
		
		//log
		private function write_log($msg=''){
			if($this->is_log){
				$text = date("Y-m-d H:i:s")." ".$msg."\r\n";
				fwrite($this->handle,$text);
			}
		}
		
		//turn to microtime
		private function microtime_float() {
			list($usec, $sec) = explode(" ", microtime());
			return ((float)$usec + (float)$sec);
		}
	}
}
?>
