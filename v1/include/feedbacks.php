<?php 
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	//$users = new USERS();
	//$method = $_SERVER['REQUEST_METHOD'];
	//$req = $users->getRequest($method);
	//var_dump(json_encode($req));
	
	class FEEDBACKS{
		private static $method_type = array('get', 'post', 'put', 'patch', 'delete');
		private static $db;
		//private static $coll;
		
		public function __construct($cname){
			self::$db = new MGDB($cname);
			//self::$coll = self::$db->sel_coll($cname);
		}
		
		public static function getRequest($req_method, $datas){
			$method = strtolower($req_method);//request type [GET POST PATCH PUT DELATE]
			if (in_array($method, self::$method_type)){
				$data_name = $method . 'Data';
				return self::$data_name($datas);//run function
			}
			return false;
		}
		
		//GET :find
		private static function getData($criteria){//criteria is an object
			$datas = self::$db->find($criteria);
			return $datas;
		}

		//POST /insert
		private static function postData($datas){
			if (!empty($datas)){
				$res = self::$db->insert($datas);
				if($res['ok'] == 1){
					return $datas;
				}else{
					return false;
				}
			} else {
				return false;
			}
		}

		//PUT put
		private static function putData($criteria, $datas){
			$data = array();
			return self::$db->put($criteria, $datas);
		}

		//PATCH patch
		private static function patchData($criteria, $datas){
			$data = array();
			return self::$db->patch($criteria, $datas);
		}

		//DELETE remove
		private static function deleteData($criteria){
			return self::$db->delate($criteria);
		}
	}
?>