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
		
		public function __construct($cname){
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
		
		private function to_int($val){
			if(strlen(floatval($val)) == strlen($val))
				return floatval($val);
			return $val;
		}
		
		//$cursor->limit(1);
		//$cursor->skip(1);
		//$cursor->sort(array('date' => 1, 'age' => -1));
		
		//return array
		public function find($filters = array(), $limit= 0, $sort=array(), $skip=0){
			try{
				foreach($filters as &$v){
					$v = $this->to_int($v);
				}
				unset($v);
				$cursor = $this->collection->find($filters);
				//if($limit !== 0){$cursor->limit($limit);}
				//if(!empty($sort)){$cursor->sort($sort);}
				//if($skip !== 0){$cursor->skip($skip);}
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
		public function delate($filters){
			//$this->collection->remove(array('_id' => new MongoId($id)), true);
			try{
				return $this->collection->remove($filters);//, array("justOne" => true)
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function put($filters, $datas, $options = array('upsert', false, 'multiple'=>true)){
			try{
				return $this->collection->update($filters, $datas, $options);//, array("justOne" => true)
			}catch(Exception $e){
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function patch($filters, $datas, $options = array('upsert', false, 'multiple'=>true)){
			try{
				return $this->collection->update($filters, array('set'=> $datas), $options);//, array("justOne" => true)
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