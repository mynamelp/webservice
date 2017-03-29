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
				$this->write_log('Construct exception : ' .$e->getMessage());
				echo 'Message: ' .$e->getMessage();
			}
			$this->is_log = _LOG;
			if($this->is_log){
				$handle = fopen(MONGODB_LOGPATH, "a+");
				$this->handle=$handle;
			}
		}
		
		private function to_int($val){
			if(is_string($val) && strlen(floatval($val)) == strlen($val))
				return floatval($val);
			return $val;
		}
		
		//$cursor->limit(1);
		//$cursor->skip(1);
		//$cursor->sort(array('date' => 1, 'age' => -1));
		
		//return array
		public function find($filters = array(), $limit= 0, $sort=array(), $skip=0){
			try{
				if(isset($filters['_id'])){
					$filters['_id'] = new MongoId($filters['_id']);
				}
				//foreach($filters as &$v){
					//$v = $this->to_int($v);
				//}
				//unset($v);
				$cursor = $this->collection->find($filters);
				if($limit !== 0){$cursor->limit($limit);}
				if(!empty($sort)){$cursor->sort($sort);}
				if($skip !== 0){$cursor->skip($skip);}
			}catch(Exception $e){
				$this->write_log('Find exception : ' .$e->getMessage());
				echo 'Message: ' .$e->getMessage();
			}
			//return iterator_to_array($cursor);
			$datas = array();
			foreach($cursor as $v){
				array_push($datas,$v);
			}
			$this->write_log("Find ,filters:" . json_encode($filters) . ",Result:". json_encode($datas));
			return $datas;
		}
		
		public function find_one($filters = array()){
			try{
				if(isset($filters['_id'])){
					$filters['_id'] = new MongoId($filters['_id']);
				}
				$cursor = $this->collection->findOne($filters);
				$this->write_log("find_one ,filters:" . json_encode($filters) . ",Result:". json_encode($cursor));
			}catch(Exception $e){
				$this->write_log('Find one exception : ' .$e->getMessage());
				echo 'Message: ' .$e->getMessage();
			}
			return $cursor;
		}
		
		//return {'ok': , 'err': , 'n': } check php mongodb insert doc 
		public function insert($datas = array()){
			try{
				$res = $this->collection->insert($datas);
				$this->write_log("Delete ,datas:" . json_encode($datas) . ",Result:". json_encode($res));
				return $res;
			}catch(Exception $e){
				$this->write_log('Insert exception : ' .$e->getMessage());
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		//return n (int) count of datas be removed
		//ok 1
		//err null
		//errmsg null
		public function delete($filters){
			try{
				if(isset($filters['_id'])){
					$filters['_id'] = new MongoId($filters['_id']);
				}
				$res = $this->collection->remove($filters);//, array("justOne" => true)
				$this->write_log("Delete ,filters:" . json_encode($filters) . ",Result:". json_encode($res));
				return $res;
			}catch(Exception $e){
				$this->write_log('Delete exception : ' .$e->getMessage());
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function put($filters, $datas, $options = array('upsert', false, 'multiple'=>true)){
			try{
				
				if(isset($filters['_id'])){
					$filters['_id'] = new MongoId($filters['_id']);
				}
				$res = $this->collection->update($filters, $datas, $options);//, array("justOne" => true)
				$this->write_log("Put ,filters:" . json_encode($filters) .", datas:" . json_encode($datas) . ",Result:". json_encode($res));
				return $res;
			}catch(Exception $e){
				$this->write_log('Put exception : ' .$e->getMessage());
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function patch($filters, $datas, $options = array('upsert', false, 'multiple'=>true)){
			try{
				$this->write_log("Patch ,filters:" . json_encode($filters) .", datas:" . json_encode($datas) . "");
				if(isset($filters['_id'])){
					$filters['_id'] = new MongoId($filters['_id']);
				}
				$res = $this->collection->update($filters, array('$set'=> $datas), $options);//, array("justOne" => true)
				$this->write_log("Put ,filters:" . json_encode($filters) .", datas:" . json_encode($datas) . ",Result:". json_encode($res));
				return $res;
			}catch(Exception $e){
				$this->write_log('Patch exception : ' .$e->getMessage());
				echo 'Message: ' .$e->getMessage();
			}
		}
		
		public function __destruct() {
			$use_time = ($this-> microtime_float())-($this->time);
			$this->write_log("To fisnish the hull query needs time: ".$use_time);
			if($this->is_log){
				fclose($this->handle);
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