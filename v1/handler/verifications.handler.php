<?php 
//header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../include/verifications.php");
require_once(dirname(__FILE__) . "/../include/lib/request.php");
require_once(dirname(__FILE__) . "/../include/lib/response.php");


$response = new Response();
$request = new Request();
$cr = $request->check_request();
if(!$cr['success']){
	echo $response->makeResults($cr['status'], $cr['datas'], $cr['errmsg']);
	die();
};
$method = $_SERVER['REQUEST_METHOD'];
switch($method){
	case 'POST':
		//TODO
		break;
	case 'GET':
		//API：向用户手机发送验证码，并向客户端返回验证码 PASS
		$filters = $_GET['filters'];
		$controller = $_GET['controller'];
		if($controller == 'getcode'){
			$vrfc = new VERIFICATIONS('verifications');
			if( isset($filters['username']) && isset($filters['medium']) ){
				$res = $vrfc->createVerification($filters['username'], $filters['medium']);
				if($res == false){
					echo $response->makeResults(404, $filters, 'fail to create verification code');
					die();
				}
			}else{
				echo $response->makeResults(404, $filters, 'username and medium are needed');
				die();
			}
		}else{
			echo $response->makeResults(404, $filters, 'wrong controller');
			die();
		}
		echo $response->sendResponse($res);
		break;
	default:
		echo $response->makeResults(404, array(), 'Error ,wrong method');
		break;
}
?>