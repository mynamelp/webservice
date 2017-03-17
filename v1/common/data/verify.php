<?php
error_reporting(E_ALL);
if(!defined('__VERIFY__')){
    define('__VERIFY__',1);
	require_once(dirname(__FILE__) . "/../../conf/config.global.php");
	// verrify methods all return 0 or 1
	// return {'status': type:bool, 'message': type:string }
	//
	class VERIFY{
		
		public function __construct(){
			//
		}
		
		//verify strings by length and preg_match ,such as username ,password and so on
		public function vfy_string($str, $sl, $ll, $rule="/^[@_a-zA-Z0-9]*$/"){
			$_ck = $this->ck_vchar_empty($str);
			if(!$_ck['status']) return $_ck;
			$_cl = $this->ck_len_btw($str, $sl, $ll);
			if (!$_cl['status']) return $_cl;
			if (!preg_match($rule, $str)) return array('status'=>false, 'message'=>"invalid string:$str");
			return array('status'=>true, 'message'=> '');
		}
		
		public function vfy_username($username, $sl, $ll, $rule="/^[@_a-zA-Z0-9]*$/"){
			$_ck = $this->ck_vchar_empty($username);
			if(!$_ck['status']) return $_ck;
			$_cl = $this->ck_len_btw($username, $sl, $ll);
			if (!$_cl) return $_cl;
			if (!preg_match($rule, $username)) return array('status'=>false, 'message'=>'invalid username');
			return array('status'=>true, 'message'=>'');
		}
		
		public function vfy_pw($pw, $sl, $ll, $rule="/^[_a-zA-Z0-9]*$/"){
			$_ck = $this->ck_vchar_empty($pw);
			if(!$_ck['status']) return $_ck;
			$_cl = $this->ck_len_btw($pw, $sl, $ll);
			if (!$_cl['status']) return $_cl;
			if (!preg_match($rule, $pw)) return array('status'=>false, 'message'=>'invalid password');
			return array('status'=>true, 'message'=> ''); 
		}
		
		public function vfy_email($mailaddr, $rule="/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*$/"){
			$_ck = $this->ck_vchar_empty($mailaddr);
			if(!$_ck['status']) return $_ck;
			if (!preg_match($rule, $mailaddr)){
				//(!ereg("^[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*@[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$", $mailaddr))   
			return array('status'=>false, 'message'=>"param: $mailaddr is not a email address");
			}
			return array('status'=>true, 'message'=>'');
		}
		
		public function vfy_tel($tel, $rule="/^[+]?[0-9]+([xX-][0-9]+)*$/"){
			$_ck = $this->ck_vchar_empty($tel);
			if(!$_ck['status']) return $_ck;
			if (!preg_match($rule, $tel)) return array('status'=>false, 'message'=>"param: $tel is not a telphone namber");
			return array('status'=>true, 'message'=>'');
		}
		
		public function clt_key(){
			
		}
		
		//check string type and if is empty
		public function ck_vchar_empty($v_char){
			if (!is_string($v_char)) return array('status'=>false, 'message'=>"parameter: $v_char type is not string");
			if (empty($v_char)) return array('status'=>false, 'message'=>"parameter: $v_char empty");
			if ($v_char=='') return array('status'=>false, 'message'=>"parameter: $v_char empty");
			return array('status'=>true, 'message'=>'');
		}
		
		//check string length between s_len and l_len,return bool
		public function ck_len_btw($C_cahr, $s_len, $l_len=100){
			$C_cahr = trim($C_cahr);
			if (strlen($C_cahr) < $s_len) return array('status'=>false, 'message'=>"string: $C_cahr length sorter than $s_len");    
			if (strlen($C_cahr) > $l_len) return array('status'=>false, 'message'=>"string: $C_cahr length longer than $l_len");
			return array('status'=>true, 'message'=>'');
		}
	}
}
?>