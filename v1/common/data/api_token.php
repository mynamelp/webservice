<?php
error_reporting(E_ALL);
if(!defined('__TOKEN__')){
    define('__TOKEN__',1);

	class APITK{
		
		public function __construct(){
			//
		}
		
		
	}
// 2�����ݿͻ��˴������� client_id ����ѯ���ݿ⣬��ȡ��Ӧ�� client_secret
//$client_secret = getClientSecretById($client_id);
// 3���������������һ�� api_token
//$api_token_server = md5($module . $controller . $action .  date('Y-m-d', time()) .  $client_secret);
// 4���ͻ��˴������� api_token ���������ɵ� api_token ����У�ԣ��������ȣ����ʾ��֤ʧ��
//if ($api_token != $api_token_server) {
    //exit('access deny');  // �ܾ�����
//}
// 5����֤ͨ�����������ݸ��ͻ���
?>