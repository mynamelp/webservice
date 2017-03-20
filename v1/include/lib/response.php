<?php
/**
 * 输出类
 */
class Response
{
    const HTTP_VERSION = "HTTP/1.1";
	
	public function __construct() {
		
	}
	
	public function processRequest(){
		if(empty($_SERVER)){
			echo $this->makeResults(404, array(), 'Server is empty');
			return false;
		}
		if(!isset($_SERVER['REQUEST_METHOD'])){
			echo $this->makeResults(404, array(), 'request is unset');
			return false;
		}
		return true;
	}

    //返回结果
    public function sendResponse($data){
        if (!$data){
			//err result
			$code = 404;
			header(self::HTTP_VERSION . " " . $code . " " . $this->getStatusCodeMessage($code));
			return $this->makeResults($code, array());
        }else{
			try{
				//输出结果
				$http_accept = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : $_SERVER['Content-Type'];
				if (strpos($http_accept, 'application/json') !== false){
					header("Content-Type: application/json");
					//result
					$code = 200;
					header(self::HTTP_VERSION . " " . $code . " " . $this->getStatusCodeMessage($code));
					return $this->makeResults($code, $data);
				}else{
					//err result
					$code = 404;
					header(self::HTTP_VERSION . " " . $code . " " . $this->getStatusCodeMessage($code));
					return $this->makeResults($code, array());
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
            200 => 'OK',  
            201 => 'Created',  
            202 => 'Accepted',  
            204 => 'No Content',   
            400 => 'Bad Request',  //参数校验
            401 => 'Unauthorized',  //未验证用户
            403 => 'Forbidden',  //无权限
            404 => 'Not Found',    
            406 => 'Not Acceptable',  
            410 => 'Gone',  
            500 => 'Internal Server Error'//内部异常
        );  
        return (isset($codes[$status])) ? $codes[$status] : '';  
    }
}