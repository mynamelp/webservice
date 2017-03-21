<?php
if(!defined('__DOCS__')){
    define('__DOCS__',1);
	
	require_once(dirname(__FILE__) . "/../../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/../../common/data/funcs.gobal.php");
	
	class DOCS{
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
		
		//GET :find by filters limit sort skip and params
		private static function getData($filters){//criteria is an object
			$limit= 0;
			$sort=array();
			$skip=0;
			$order = 1;
			if(isset($filters['limit'])){
				if(__isInt($filters['limit']) !== false)
					$limit = $filters['limit'];
			}
			if(isset($filters['skip'])){
				if(__isInt($filters['skip']) !== false)
					$skip = $filters['skip'];
			}
			if(isset($filters['order'])){
				if(__isInt($filters['order']) !== false)
					$order = $filters['order'];
			}
			if(isset($filters['sortby'])){
				if(is_String($filters['sortby'])){
					$sort = array($filters['sortby']=>$order);
				}
			}
			$datas = self::$db->find($filters, $limit, $sort, $skip);
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
}
?>