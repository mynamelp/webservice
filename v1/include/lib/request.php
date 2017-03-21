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
				echo $this->makeResults(404, array(), 'Server is empty');
				return false;
			}
			//check method
			if(!isset($_SERVER['REQUEST_METHOD'])){
				echo $this->makeResults(404, array(), 'request is unset');
				return false;
			}
			switch($_SERVER['REQUEST_METHOD']){
				case 'GET':
				
					break;
				case 'POST':
					//check post
					if (empty($_POST)){
						//err result 
						echo $this->makeResults(404, array(), 'post empty');
						return false;
					}
					//check post['token']
					if(!isset($_POST['token'])){
						//unset token 
						echo $this->makeResults(403, $_POST, 'token is needed');
						return false;
					}
					//check post['datas']
					if(!isset($_POST['datas'])){
						//unset token 
						echo $this->makeResults(404, $_POST, 'post datas empty');
						return false;
					}
					break;
				case 'PUT':
				
					break;
				case 'PATCH':
				
					break;
				case 'DELATE':
				
					break;
				default:
					break;
			}
			return true;
		}
		
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