<?php
if(!defined('__REQUEST__')){
    define('__REQUEST__',1);


	class REQUEST{
		private $client_secret = 'lamifeng';
	
		public function __construct(){
	
		}
		
		public function check_request(){
			//check _SERVER
			if(empty($_SERVER)){
				return array(	'success'=>false, 
								'status'=>400, 
								'datas'=>array(), 
								'errmsg'=>'$_Server is empty'
							);
			}
			//check method
			if(!isset($_SERVER['REQUEST_METHOD'])){
				return array(	'success'=>false, 
								'status'=>400, 
								'datas'=>array(), 
								'errmsg'=>'Request method is unset'
							);
			}
			
			//$_POST $_GET $_PUT $_PATCH $_DELETE 
			$_request = $_REQUEST;
			if(empty($_request)){
				parse_str(file_get_contents('php://input'), $_request);
				$tmp = "_".$_SERVER['REQUEST_METHOD'];
				global $$tmp;
				$$tmp = $_request;
			}
			
			//check post and get datas
			if(empty($_request)){
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
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check post token , token unset'
						);
					}
					//check post['datas']
					if(!isset($_request['datas'])){
						//unset datas
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
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check put token ,token unset'
						);
					}
					//check post['datas']
					if(!isset($_request['datas'])){
						//unset datas
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check put datas ,datas unset'
						);
					}
					//check post['filters']
					if(!isset($_request['filters'])){
						//unset filters
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
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check PATCH token ,token unset'
						);
					}
					//check post['datas']
					if(!isset($_request['datas'])){
						//unset token 
						return array(	'success'=>false, 
										'status'=>400, 
										'datas'=>$_request, 
										'errmsg'=>'Request check PATCH datas ,datas unset'
						);
					}
					//check post['filters']
					if(!isset($_request['filters'])){
						//unset token 
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
			return array(	'success'=>true, 
							'status'=>200, 
							'datas'=>$_request, 
							'errmsg'=>''
						);
		}
		
		//Un use
		public function check_token($token){
			//token rule: url + date + secret md5
			$url = "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
			$server_token = md5($url . date('Y-m-d', time()) . $client_secret);
			if ($token == $server_token){
				return true;
			}
			return false;
		}
		

	}
}
?>