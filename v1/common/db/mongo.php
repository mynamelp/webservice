<?php
error_reporting(E_ALL);
if(!defined('__MONGO__')){
    define('__MONGO__',1);
    require_once(dirname(__FILE__) . "/../../conf/config.db.php");

	Class MGDB {
		private $connection;
		private $db;
		private $handle;
		private $is_log;
		private $time;
		
		public function __construct() {
			// time start
			$this->time = $this->microtime_float();
			if(!isset($this->connection)){
				$this->connection = new MongoClient(MONGO_ADDR); 
			}
			$this->db = $this->connection->selectDB(MONGO_DB);//$this->db = $this->connection->lamf;	
			$this->is_log = _LOG;
			if($this->is_log){
				$handle = fopen(MONGODB_LOGPATH, "a+");
				$this->handle=$handle;
			}
		}
		
		/// <summary>
        /// select collection by name
        /// </summary>
        /// <param name="name">collection name</param>
        /// <returns>collection object</returns>
		public function sel_coll($name){
			return $this->db->selectCollection($name);//return $this->db->$name;
		}
		
		public function get_colls(){
			 return $this->db->getCollectionNames();
		}
		
		//return array
		public function get($conditions){
			$cursor = $collection->find($conditions);
			return iterator_to_array($cursor);
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