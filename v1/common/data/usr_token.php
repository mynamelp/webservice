<?php
error_reporting(E_ALL);
if(!defined('__TOKEN__')){
    define('__TOKEN__',1);
	require_once(dirname(__FILE__) . "/../../conf/config.global.php");
	require_once(dirname(__FILE__) . "/../db/db.handler.php");
	require_once(dirname(__FILE__) . "/verify.php");

	class USRTK{
		private $db;
		private $coll;
		private $vfy;
		
		public function __construct($cname){
			$this->db = new MGDB($cname);// users collection
			$this->coll = $this->db->sel_coll($cname);
			$this->vfy = new VERIFY();
			//
		}
		//first login ,create a token for user
		public function create($username, $pw, $clt_key){
			$_ck_un = $this->vfy->vfy_username($username);
			if(!$_ck_un['status']) return $_ck_un;
			$_ck_pw = $this->vfy->vfy_pw($pw);
			if(!$_ck_pw['status']) return $_ck_pw;
			$_ck_clt = $this->vfy->ck_vchar_empty($clt_key);
			if(!$_ck_clt['status']) return {'status':$_ck_clt['status'], 'message':'client key is empty'};
			return $token=md5($username.$pw.date('yyyy').date('mm').$clt_key.SALT);
		}
		//then save it as username with expiry
		public function save($username, $token, $expiry=36000){
			//db insert
			$_ck_un = $this->vfy->vfy_username($username);
			if(!$_ck_un['status']) return $_ck_un;
			$_ck_tk = $this->vfy->ck_vchar_empty($token);
			if(!$_ck_tk['status']) return {'status':$_ck_tk['status'], 'message':'token is empty'};
			$up_rslt = $this->coll->update(array("username" => $username), array('$set' => array("token" => $token, 'expiry'=>$expiry)));
			if(!$up_rslt) return {'status':false, 'message':"failed to save token by username: $username"};
			return {'status':true, 'message':"sucess to update token by user $username"};
		}
		//after get token from server db 
		public function get($username){
			//db get
			return $this->coll->findOne(array("username" => $username));
		}
		//compare client token with server token,and return bool
		public function check($clt_tk, $svr_tk){
			if($clt_tk == $svr_tk)
				return true;
			else
				return false;
		}
	}
?>