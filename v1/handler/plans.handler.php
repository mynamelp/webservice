<?php 
//header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../include/plans.php");
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
		//API:旅游计划 发布
		//请求格式
		//{
		//	controller:'', 
		//      datas:{	
		//				username:(string), 
		//              password:(string), 
		//				client:(string),
		//				expire:(int)
		//			  }
		//}
		$datas = $POST['datas'];
		$_id= $POST['datas']['_id'];
		$token = $POST['token'];
		$usrOBJ = new USRTK('users');
		$tkRT = $usrOBJ->get($_id);
		//check server token and client token matchment
		if(!$usrOBJ->check($token, $tkRT['token'])){
			echo $response->makeResults(403, array(), 'invaild token');
			die();
		}
		$plans = new PLANS('plans');
		$res = $plans->postData($datas);
		if($res == false){
			echo $response->makeResults(500, $datas, 'Error ,Plans insert failed,result is null');
			die();
		}
		echo $response->sendResponse($res);
		break;
	case 'GET':
		//TODO
		echo $response->sendResponse($res);
		break;
	case 'PATCH':
		//TODO
		break;
	case 'DELETE':
		//TODO
		break;
	default:
		echo $response->makeResults(404, array(), 'Error ,wrong method');
		break;
}
?>