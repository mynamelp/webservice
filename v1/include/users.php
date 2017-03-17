<?php 
	//header("Content-Type:application/json;charset=utf-8");
	//json_encode
	//urldecode
	//$req = $_REQUEST;
	
	
	//var_dump($_SERVER['REQUEST_METHOD']);
	
	
	
	
	//$re = request_post('http://127.0.0.1/lamf/trunk/api/v1/handler/users.php', 'name=11&count=30');
	
	
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	$users = new USERS();
	$method = $_SERVER['REQUEST_METHOD'];
	$req = $users->getRequest($method);
	var_dump(json_encode($req));
	
	
	
	class USERS{
		private static $method_type = array('get', 'post', 'put', 'patch', 'delete');
		private static $db;
		private static $coll;
		
		public function __construct(){
			self::$db = new MGDB();
			self::$coll = self::$db->sel_coll('users');
		}
		
		//测试数据
		private static $test_class = array(
			1 => array('name' => '托福班', 'count' => 18),
			2 => array('name' => '雅思班', 'count' => 20),
		);
		
		
		public static function getRequest($req_method){
			$method = strtolower($req_method);//request type [GET POST PATCH PUT DELATE]
			if (in_array($method, self::$method_type)){
				$data_name = $method . 'Data';
				return self::$data_name($_REQUEST);//run function
			}
			return false;
		}

		//GET
		private static function getData($filter){//filter is an object
			$datas = self::$coll->get($filter);
			return $datas;
		}

		//POST /class：新建一个班
		private static function postData($request_data){
			if (!empty($request_data['name'])) {
				$data['name'] = $request_data['name'];
				$data['count'] = (int)$request_data['count'];
				self::$test_class[] = $data;
				return self::$test_class;//返回新生成的资源对象
			} else {
				return false;
			}
		}

		//PUT /class/ID：更新某个指定班的信息（全部信息）
		private static function putData($request_data){
			$class_id = (int)$request_data['class'];
			if ($class_id == 0) {
				return false;
			}
			$data = array();
			if (!empty($request_data['name']) && isset($request_data['count'])) {
				$data['name'] = $request_data['name'];
				$data['count'] = (int)$request_data['count'];
				self::$test_class[$class_id] = $data;
				return self::$test_class;
			} else {
				return false;
			}
		}

		//PATCH /class/ID：更新某个指定班的信息（部分信息）
		private static function patchData($request_data){
			$class_id = (int)$request_data['class'];
			if ($class_id == 0){
				return false;
			}
			if (!empty($request_data['name'])){
				self::$test_class[$class_id]['name'] = $request_data['name'];
			}
			if (isset($request_data['count'])){
				self::$test_class[$class_id]['count'] = (int)$request_data['count'];
			}
			return self::$test_class;
		}

		//DELETE /class/ID：删除某个班
		private static function deleteData($request_data){
			$class_id = (int)$request_data['class'];
			if ($class_id == 0)
				return false;
			unset(self::$test_class[$class_id]);
			return self::$test_class;
		}
	}
?>