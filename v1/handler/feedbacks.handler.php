<?php 
//header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, access');
require_once(dirname(__FILE__) . "/../include/feedbacks.php");
require_once(dirname(__FILE__) . "/../include/lib/response.php");

$method = $_SERVER['REQUEST_METHOD'];
$response = new Response();
$request = new Request();
if(!$request->check_request()){
	//check http header
	die();
};

switch($method){
	case 'POST':
		//API：新增 用户反馈
		if(!$request->check_token($_POST['token'])){
			echo $this->makeResults(403, array(), 'wrong token');
			die();
		}
		$datas = $_POST['datas'];
		$fdbk = new FEEDBACKS('feedbacks');
		$result = $fdbk->getRequest($method, $datas);
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