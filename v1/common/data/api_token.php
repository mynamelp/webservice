<?php
error_reporting(E_ALL);
if(!defined('__TOKEN__')){
    define('__TOKEN__',1);

	class APITK{
		
		public function __construct(){
			//
		}
		
		
	}
// 2、根据客户端传过来的 client_id ，查询数据库，获取对应的 client_secret
//$client_secret = getClientSecretById($client_id);
// 3、服务端重新生成一份 api_token
//$api_token_server = md5($module . $controller . $action .  date('Y-m-d', time()) .  $client_secret);
// 4、客户端传过来的 api_token 与服务端生成的 api_token 进行校对，如果不相等，则表示验证失败
//if ($api_token != $api_token_server) {
    //exit('access deny');  // 拒绝访问
//}
// 5、验证通过，返回数据给客户端
?>