<?php 
header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../include/verifications.php");
require_once(dirname(__FILE__) . "/../include/lib/request.php");
require_once(dirname(__FILE__) . "/../include/lib/response.php");


$response = new Response();
$request = new Request();
if(!$request->check_request()){
	//check http header
	die();
};

//API GET getCode matchCode

$method = $_SERVER['REQUEST_METHOD'];
switch($method){
	case 'POST':
		
		break;
	case 'GET':
		$filters = $_GET['filters'];
		$controller = $_GET['controller'];
		if($controller == 'getCode'){
			//API：注册 send：tel email ，验证码
			$vrfc = new VERIFICATIONS('verifications');
			if( isset($filters['username']) && isset($filters['mean']) ){
				$res = $vrfc->createVerification($filters['username'], $filters['mean']);
				if($res == false){
					echo $response->makeResults(404, $filters, 'fail to create verification code');
					die();
				}
			}else{
				echo $response->makeResults(404, $filters, 'username and mean are needed');
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