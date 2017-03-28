<?php 
//header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, access');
header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../include/users.php");
require_once(dirname(__FILE__) . "/../include/verifications.php");
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
		$controller = $_POST['controller'];
		$datas = $_POST['datas'];
		if($controller == 'setpwd'){
			//API:验证码 注册 或 更新密码 PASS
			//请求格式
			//{
			//	controller:'setpwd', 
			//      datas:{	
			//				username:(string), 
			//              password:(string), 
			//				client:(string),
			//				code:(int)
			//				}
			//		token:(string)
			//}
			$username = (string)$datas['username'];
			$password = (string)$datas['password'];
			$client = (string)$datas['client'];
			$code = (int)$datas['code'];
			//check code available
			$vrfc = new VERIFICATIONS('verifications');
			$vr = $vrfc->getData(array('username'=>$username, 'code'=>$code));
			if(empty($vr)){
				echo $response->makeResults(400, $_POST, 'invalid code or username');
				die();
			}
			//check if user exists
			$usr = new USERS('users');
			$res = $usr->getData(array('username'=>$username));
			if($res != null){
				//account exists ,update
				$res = $usr->patchData(
					array( 'filters'=>array('username'=>$username), 
							'datas'=>array('password'=>$password))
				);
				if($res == false){
					echo $response->makeResults(500, $datas, 'Error: Fail to update password, API setpwd');
					die();
				}
			}else{
				//account not exists , create
				$res = $usr->postData(array('username'=>$username, 'password'=>$password, 'create_time'=>time()));
				if($res == false){
					echo $response->makeResults(500, $_POST, 'Error: Fail to create user, API setpwd');
					die();
				}
			}
			//delete code
			$vr = $vrfc->deleteData(array('username'=>$username, 'code'=>$code));
			if(empty($vr)){
				echo $response->makeResults(400, $_POST, 'delete code by username failed');
				die();
			}
			//create and update token
			$usrTK = new USRTK('users');
			$token = $usrTK->create($username, $password, $client);
			$r = $usrTK->save($username, $token);
			if(!$r['status']){
				echo $response->makeResults(500, $_POST, $r['message']);
				die();
			}
			$datas['token'] = $token;
			$result = $datas;
		}else if($controller == 'tellogin'){
			//API:用户 手机验证码登录 PASS
			//请求格式
			//{
			//	controller:'tellogin', 
			//      datas:{	
			//				username:(string),  
			//				code:(int), 
			//				client:(string)
			//				}
			//}
			$username = (string)$datas['username'];
			$code = (int)$datas['code'];
			$client = (string)$datas['client'];
			//check code available
			$vrfc = new VERIFICATIONS('verifications');
			$vr = $vrfc->getData(array('username'=>$username, 'code'=>$code));
			if(empty($vr)){
				echo $response->makeResults(400, $_POST, 'invalid code or username');
				die();
			}
			//check if user exists
			$usr = new USERS('users');
			$res = $usr->getData(array('username'=>$username));
			if(count($res) == 0){
				echo $response->makeResults(400, $_POST, 'User is not exists');
				die();
			}
			//create and update token
			$usrTK = new USRTK('users');
			$token = $usrTK->create($username, (string)$code, $client);
			$r = $usrTK->save($username, $token);
			if(!$r['status']){
				echo $response->makeResults(500, $_POST, $r['message']);
				die();
			}
			//delete code
			$vr = $vrfc->deleteData(array('username'=>$username, 'code'=>$code));
			if(empty($vr)){
				echo $response->makeResults(400, $_POST, 'delete code by username failed');
				die();
			}
			$datas['token'] = $token;
			$result = $datas;
		}else if($controller == 'pwdlogin'){
			//API:用户 账号密码登录 PASS
			//请求格式
			//{
			//	controller:'pwdlogin', 
			//      datas:{	
			//				username:(string), 
			//              password:(string), 
			//				client:(string),
			//				expire:(int)
			//			  }
			//}
			$username = (string)$datas['username'];
			$password = (string)$datas['password'];
			$client = (string)$datas['client'];
			//check if user exists
			$usr = new USERS('users');
			$res = $usr->getData(array('username'=>$username, 'password'=>$password));
			if(count($res) < 0){
				echo $response->makeResults(400, $_POST, 'invaild username or password');
				die();
			}
			//create and update token
			$usrTK = new USRTK('users');
			$token = $usrTK->create($username, $password, $client);
			if(!$token['status']){
				echo $response->makeResults(500, $token, 'create token failed');
				die();
			}
			$r = $usrTK->save($username, $token['data']);
			if(!$r['status']){
				echo $response->makeResults(500, $_POST, $r['message']);
				die();
			}
			$datas['token'] = $token['data'];
			$result = $datas;
		}else{
			//todo
			echo $response->makeResults(500, $_POST, 'invaild controller');
			die();
		}
		echo $response->sendResponse($result);
		break;
	case 'GET':
		//API: Get users info ,one or list PASS
		$filters = $_GET['filters'];
		if (empty($filters)){
			//err result 
			echo $response->makeResults(406, array(), 'Error: HTTP GET null filters is forbidden');
			die();
		}else{
			//API 查询用户信息
			$users = new USERS('users');
			$result = $users->getData($filters);
			echo $response->sendResponse($result);
		}
		break;
	case 'PATCH':
		//API:更新 用户信息 PASS
		//请求格式
		//{
		//      datas:(object),
		//		filters:(object :_id ...),
		//		token:(string)		
		//				
		//}
		$datas = $_PATCH['datas'];
		$_id= $_PATCH['filters']['_id'];
		$token = $_PATCH['token'];
		$usrOBJ = new USRTK('users');
		$tkRT = $usrOBJ->get($_id);
		//check server token and client token matchment
		if(!$usrOBJ->check($token, $tkRT['token'])){
			echo $response->makeResults(403, array(), 'invaild token');
			die();
		}
		$usr = new USERS('users');
		$result = $usr->patchData($_PATCH);
		if($result == false){
			echo $response->makeResults(500, $_PATCH, 'Error ,PATCH user info faild,result is false');
			die();
		}
		echo $response->sendResponse($result);
		break;
	case 'PUT':
		//TODO
		echo $response->makeResults(500, $_PUT, 'Error ,PUT is unused');
		die();
		break;
	case 'DELETE':
		//todo
		//TODO
		echo $response->makeResults(500, $_DELETE, 'Error ,DELETE is unused');
		die();
		break;
	default:
		echo $response->makeResults(404, array(), 'Error ,invaild method');
		break;
}
?>