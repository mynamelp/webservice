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

//API signup findUser updateUser

$method = $_SERVER['REQUEST_METHOD'];
switch($method){
	case 'POST':
		//API：注册新用户
		$controller = $_POST['controller'];
		$datas = $_POST['datas'];
		//datas username password code: int expire:int
		if($controller == 'signup'){
			$username = $datas['username'];
			$password = $datas['password'];
			$code = $datas['code'];
			$expire = $datas['expire'];
			//check code
			$vrfc = new VERIFICATIONS('verifications');
			$vr = $vrfc->getRequest($method, array('username'=>$username, 'code'=>$code));
			if(empty($vr)){
				echo $response->makeResults(404, $_POST, 'invalid code or username');
				die();
			}
			if($vr['expire'] - $expire < 0){
				echo $response->makeResults(404, $_POST, 'code expire');
				die();
			}
			//check if user exists
			//create new account
			$usr = new USERS('users');
			$result = $usr->getRequest($method, array('username'=>$username, 'password'=>$password));
			if($result == false){
				echo $response->makeResults(500, $_POST, 'Error ,no result');
				die();
			}
		}else{
			//TODO
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
	case 'PATCH':
		//API 更新用户信息
		if(!$request->check_token($_POST['token'])){
			echo $this->makeResults(403, array(), 'wrong token');
			die();
		}
		$controller = $_POST['controller'];
		$datas = $_POST['datas'];
		$usr = new USERS('users');
		$result = $usr->getRequest($method, $datas);
		if($result == false){
			echo $response->makeResults(500, $_POST, 'Error ,wrong http method');
			die();
		}
		echo $response->sendResponse($result);
		break;
	case 'PUT':
		
		break;
	default:
		echo $response->makeResults(404, array(), 'Error ,wrong method');
		break;
}
?>