<?php
error_reporting(E_ALL);
if(!defined('__MONGO__')){
    define('__MONGO__',1);
    require_once(dirname(__FILE__) . "/../../conf/config.db.php");

	Class MGDB {
		private $connection;
		private $db;
		private $collection;
		private $handle;
		private $is_log;
		private $time;
		
		public function __construct($cname) {
			// time start
			$this->time = $this->microtime_float();
			try{
				if(!isset($this->connection)){
					$this->connection = new MongoClient(MONGO_ADDR); 
				}
				$this->db = $this->connection->selectDB(MONGO_DB);//$this->db = $this->connection->lamf;
				$this->collection = $this->db->selectCollection($cname);
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
			$this->is_log = _LOG;
			if($this->is_log){
				$handle = fopen(MONGODB_LOGPATH, "a+");
				$this->handle=$handle;
			}
		}
		
		//return array
		public function find($criteria = array()){
			try{
				$cursor = $this->collection->find($criteria);
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
			return iterator_to_array($cursor);
		}
		
		//return {'ok': , 'err': , 'n': } check php mongodb insert doc 
		public function insert($datas = array()){
			try{
				return $this->collection->insert($datas);
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		//return n (int) count of datas be removed
		//ok 1
		//err null
		//errmsg null
		public function delate($criteria){
			//$this->collection->remove(array('_id' => new MongoId($id)), true);
			try{
				return $this->collection->remove($criteria);//, array("justOne" => true)
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function put($criteria, $datas, $options = array('upsert', false, 'multiple'=>true)){
			try{
				return $this->collection->update($criteria, $datas, $options);//, array("justOne" => true)
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function patch($criteria, $datas, $options = array('upsert', false, 'multiple'=>true)){
			try{
				return $this->collection->update($criteria, array('set'=> $datas), $options);//, array("justOne" => true)
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		////////////////////////////////////
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