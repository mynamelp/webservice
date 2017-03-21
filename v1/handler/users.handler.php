<?php 
//header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, access');
header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../include/users.php");
require_once(dirname(__FILE__) . "/../include/lib/request.php");
require_once(dirname(__FILE__) . "/../include/lib/response.php");


$response = new Response();
$request = new Request();
if(!$request->check_request()){
	//check http header
	die();
};

$method = $_SERVER['REQUEST_METHOD'];
switch($method){
	case 'POST':
		//API：新增 用户
		if(!$request->check_token($_POST['token'])){
			echo $this->makeResults(403, array(), 'wrong token');
			die();
		}
		$datas = $_POST['datas'];
		$usr = new USERS('users');
		$result = $usr->getRequest($method, $datas);
		if($result == false){
			echo $response->makeResults(500, $datas, 'Error ,wrong http method');
			die();
		}
		echo $response->sendResponse($result);
	break;
	case 'GET':
		$filters = $_GET;
		if (empty($filters)){
			//err result 
			echo $response->makeResults(400, array(), 'Get filters is empty');
			die();
		}else{
			//API 查询用户信息
			$users = new USERS('users');
			$result = $users->getRequest($method, $filters);
			if(empty($result)){
				echo $response->makeResults(404, $filters, 'Error ,results is empty');
				die();
			}
			echo $response->sendResponse($result);
		}
	break;
	default:
		echo $response->makeResults(404, array(), 'Error ,wrong method');
	break;
}
?>