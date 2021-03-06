<?php
if(!defined('__DOCS__')){
    define('__DOCS__',1);
	
	require_once(dirname(__FILE__) . "/../../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/../../common/data/funcs.gobal.php");
	
	class DOCS{
		private $db;
		
		public function __construct($cname){
			$this->db = new MGDB($cname);
		}
		
		//GET :find by filters limit sort skip and params
		public function getData($filters){
			$sort=array();
			$limit= 0;$skip=0;$order = 1;
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
			$datas = $this->db->find($filters, $limit, $sort, $skip);
			return $datas;
		}

		//POST /insert
		public function postData($datas){
			if (!empty($datas)){
				$res = $this->db->insert($datas);
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
		public function putData($params){
			$datas = $params['datas'];
			$filters = $params['filters'];
			$r = $this->db->patch($filters, $datas);
			if($r['ok'] == 1){
				return $params;
			}
			return false;
		}

		//PATCH patch
		public function patchData($params){
			$datas = $params['datas'];
			$filters = $params['filters'];
			$r = $this->db->patch($filters, $datas);
			if($r['ok'] == 1){
				return $params;
			}
			return false;
		}

		//DELETE remove
		public function deleteData($filters){
			return $this->db->delete($filters);
		}
	}
}
?>