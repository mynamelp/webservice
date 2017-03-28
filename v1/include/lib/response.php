<?php
/**
 * 输出类
 */
if(!defined('__RESPONSE__')){
    define('__RESPONSE__',1);
	class Response{
		const HTTP_VERSION = "HTTP/1.1";
		
		public function __construct() {
			
		}
		
		public function processRequest(){
			
		}

		//返回结果
		public function sendResponse($data){
			if (empty($data)){
				//err result
				$code = 404;
				header(self::HTTP_VERSION . " " . $code . " " . $this->getStatusCodeMessage($code));
				return $this->makeResults($code, array(), 'sendResponse data is empty');
			}else{
				try{
					//输出结果
					if(!isset($_SERVER['CONTENT_TYPE'])){
						//err result
						$code = 404;
						header(self::HTTP_VERSION . " " . $code . " " . $this->getStatusCodeMessage($code));
						return $this->makeResults($code, $_SERVER, "Request content-type is defined");
					}
					if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false ||
							strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false){
						header("Content-Type: application/json");
						//result
						$code = 200;
						header(self::HTTP_VERSION . " " . $code . " " . $this->getStatusCodeMessage($code));
						return $this->makeResults($code, $data);
					}else{
						//err result
						$code = 404;
						header(self::HTTP_VERSION . " " . $code . " " . $this->getStatusCodeMessage($code));
						return $this->makeResults($code, $_SERVER, "Request content type is not application/json");
					}
				}catch(Exception $e){
					echo 'Message: ' .$e->getMessage();
				}
			}
		}
		
		public function makeResults($status, $datas, $errmsg=''){
			if(isset($_SERVER['REQUEST_URI']) && isset($_SERVER['SERVER_ADDR']))
				$uri = $_SERVER['SERVER_ADDR'] . $_SERVER['REQUEST_URI'];
				$uri_profile = $_SERVER['SERVER_ADDR']. '/profile' . $_SERVER['REQUEST_URI'];
			$datas = array(
			'status' => $status,
			'msg' => $this->getStatusCodeMessage($status),
			'errmsg'=> $errmsg,
			'responsetime' => time(),
			'datas' => $datas,
			'links' => array('_self' => array('href' => $uri), 'profile' => array('href' => $uri_profile))
			);
			return $this->encode($datas);
		}
		
		public function encode($datas){
			return json_encode($datas);
		}
		
		public function getStatusCodeMessage($status)  
		{  
			// these could be stored in a .ini file and loaded  
			// via parse_ini_file()... however, this will suffice  
			$codes = Array(    
				200 => 'OK',  		//get 请求成功
				201 => 'Created',  //post put patch 创建成功
				202 => 'Accepted',  //请求已进入后台，异步
				204 => 'No Content',   //删除成功 delate
				400 => 'Bad Request',  //错误请求，参数校验
				401 => 'Unauthorized',  //未验证用户
				403 => 'Forbidden',  //无权限
				404 => 'Not Found',    //记录不存在
				406 => 'Not Acceptable',  //请求格式异常
				410 => 'Gone',  //该资源已被永久删除
				500 => 'Internal Server Error'//内部异常
			);  
			return (isset($codes[$status])) ? $codes[$status] : '';  
		}
	}
}