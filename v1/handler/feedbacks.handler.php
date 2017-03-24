<?php 
header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../include/feedbacks.php");
require_once(dirname(__FILE__) . "/../include/lib/request.php");
require_once(dirname(__FILE__) . "/../include/lib/response.php");
require_once(dirname(__FILE__) . "/../common/data/usr_token.php");


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
		//API：新增 用户反馈
		//请求格式
		//{ 
		//      datas:{	
		//				uid:(string),
		//				contact:(string), 
		//              content:(string), 
		//				client:(string),
		//				time:(int)
		//				},
		//		token:(string)
		//}
		$datas = $_POST['datas'];
		$uid= $datas['uid'];
		$token = $_POST['token'];
		$usrOBJ = new USRTK('users');
		$tkRT = $usrOBJ->get($_id);
		//check server token and client token matchment
		if(!$usrOBJ->check($token, $tkRT['token'])){
			echo $this->makeResults(403, array(), 'invaild token');
			die();
		}
		$fdbk = new FEEDBACKS('feedbacks');
		$result = $fdbk->postData($datas);
		if($result == false){
			echo $response->makeResults(500, $datas, 'Error ,wrong http method');
			die();
		}
		echo $response->sendResponse($result);
		break;
	case 'GET':
		echo $response->makeResults(500, array(), 'Error ,there is no method for get');
		break;
	default:
		echo $response->makeResults(404, array(), 'Error ,wrong method');
		break;
}
?>