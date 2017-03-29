<?php
if(!defined('__REQUEST__')){
    define('__REQUEST__',1);


	class REQUEST{
		private $client_secret = 'lamifeng';
		private $handle;
		private $is_log;
		private $time;
	
		public function __construct(){
			// time start
			$this->time = $this->microtime_float();
			$this->is_log = _LOG;
			if($this->is_log){
				$handle = fopen(REQ_LOGPATH, "a+");
				$this->handle=$handle;
			}
		}
		
		public function check_request(){
			//check _SERVER
			if(empty($_SERVER)){
				$this->write_log('check_request: $_Server is empty');
				return array(	'success'=>false, 
								'status'=>400, 
								'datas'=>array(), 
								'errmsg'=>'$_Server is empty'
							);
			}
			//check method
			if(!isset($_SERVER['REQUEST_METHOD'])){
				$this->write_log('check_request: REQUEST_METHOD is unset');
				return array(	'success'=>false, 
								'status'=>400, 
								'datas'=>array(), 
								'errmsg'=>'Request method is unset'
							);
			}
			//$_POST $_GET $_PUT $_PATCH $_DELETE 
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$_request = $_POST;
				$this->write_log('Request method POST : ' . json_encode($_request));
			}else if($_SERVER['REQUEST_METHOD'] == 'GET'){
				$_request = $_GET;
				$this->write_log('Request method GET : ' . json_encode($_request));
			}else{
				parse_str(file_get_contents('php://input'), $_request);
				$tmp = "_".$_SERVER['REQUEST_METHOD'];
				global $$tmp;
				$$tmp = $_request;
				$this->write_log("Request method $tmp : " . json_encode($$tmp));
			}
			//check request datas
			if(empty($_request)){
				$this->write_log('Request datas ,datas is empty');
				return array(	'success'=>false, 
								'status'=>400, 
								'datas'=>array(), 
								'errmsg'=>'Request datas ,datas is empty'
				);
			}
			switch($_SERVER['REQUEST_METHOD']){
				case 'GET':
					//filters cant be empty
					if(!isset($_request['filters'])){
						//unset filters 
						$this->write_log('check_request GET: filters is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check post filters , filters unset'
						);
					}
					break;
				case 'POST':
					//check post['token']
					if(!isset($_request['token'])){
						//unset token 
						$this->write_log('check_request POST: token is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check post token , token unset'
						);
					}
					//check post['datas']
					if(!isset($_request['datas'])){
						//unset datas
						$this->write_log('check_request POST: datas is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check post datas ,datas unset'
						);
					}
					break;
				case 'PUT':
					//check post['token']
					if(!isset($_request['token'])){
						//unset token 
						$this->write_log('check_request PUT: token is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check put token ,token unset'
						);
					}
					//check post['datas']
					if(!isset($_request['datas'])){
						//unset datas
						$this->write_log('check_request PUT: datas is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check put datas ,datas unset'
						);
					}
					//check post['filters']
					if(!isset($_request['filters'])){
						//unset filters
						$this->write_log('check_request PUT: filters is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check put filters ,filters unset'
						);
					}
					break;
				case 'PATCH':
					//check post['token']
					if(!isset($_request['token'])){
						//unset token 
						$this->write_log('check_request PATCH: token is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check PATCH token ,token unset'
						);
					}
					//check post['datas']
					if(!isset($_request['datas'])){
						//unset token 
						$this->write_log('check_request PATCH: datas is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check PATCH datas ,datas unset'
						);
					}
					//check post['filters']
					if(!isset($_request['filters'])){
						//unset token 
						$this->write_log('check_request PATCH: filters is unset');
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check PATCH filters ,filters unset'
						);
					}
					break;
				case 'DELATE':
				
					break;
				default:
					break;
			}
			$this->write_log("check_request: Success ". $_SERVER['REQUEST_METHOD'] ." uri: " . $_SERVER['REQUEST_URI']);
			return array(	'success'=>true, 
							'status'=>200, 
							'datas'=>$_request, 
							'errmsg'=>''
						);
		}
		
		//Un use
		/*
		public function check_token($token){
			//token rule: url + date + secret md5
			$url = "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			$server_token = md5($url . date('Y-m-d', time()) . $client_secret);
			if ($token == $server_token){
				return true;
			}
			return false;
		}
		*/
		
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
			//$msg .= "\r\n".mysql_error();
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