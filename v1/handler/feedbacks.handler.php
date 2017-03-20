<?php 
//header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, access');
require_once(dirname(__FILE__) . "/../include/feedbacks.php");
require_once(dirname(__FILE__) . "/../include/lib/response.php");

$method = $_SERVER['REQUEST_METHOD'];
$response = new Response();
if(!$response->processRequest()){
	//check info in http header
	die();
};

switch($method){
	case 'POST':
		if (empty($_POST)){
			//err result 
			echo $response->makeResults(404, array(), 'post is empty');
			die();
		}else{
			//新增 用户反馈
			$datas = $_POST;
			$fdbk = new FEEDBACKS('feedbacks');
			$result = $fdbk->getRequest($method, $datas);
			if($result == false){
				echo $response->makeResults(500, $datas);
				die();
			}
			echo $response->sendResponse($result);
		}
	break;
	case 'GET':
		//
	break;
	default:
	break;
}
?>