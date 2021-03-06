<?php
if(!defined('__VERIFICATIONS__')){
    define('__VERIFICATIONS__',1);
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/lib/docs.php");
	require_once(dirname(__FILE__) . "/lib/alidayu/TopSdk.php");
	
	class VERIFICATIONS extends DOCS{
		private $appkey = '23710753';
		private $secretKey = '6dd2adac369ce99fb70f153983e0baba';
		
		public function createVerification($username, $medium){
			$code = rand(100000, 999999);
			$expire = time()+300;
			$d = array('username'=>$username, 'code'=>$code, 'expire'=>$expire);
			$res = $this->postData($d);
			if(!empty($res)){
				if($medium == 'tel'){
					$res = $this->sendSMS($code, $username);
					if($res['result']['success'] == true){
						return $d;
					}else{
						return false;
					}
				}else if($medium == 'addr'){
					$res = $this->sendMail($code, $username);
					return $res;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		
		public function sendMail($code, $addr){
			//TODO
			return array();
		}
		
		public function sendSMS($code, $tel){
			$c = new TopClient;
			$c->appkey = $this->appkey;
			$c->secretKey = $this->secretKey;
			$req = new AlibabaAliqinFcSmsNumSendRequest;
			//$req->setExtend("123456");
			$req->setSmsType("normal");
			$req->setSmsFreeSignName("辣蜜蜂翻译");
			$req->setSmsParam("{\"code\":\"". $code ."\",\"product\":\"辣蜜蜂\"}");
			$req->setRecNum($tel);
			$req->setSmsTemplateCode("SMS_57105183");
			$resp = $c->execute($req);
			return json_decode( json_encode($resp),true);//return json
		}
		
	}
}
?>