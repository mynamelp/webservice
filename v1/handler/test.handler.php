<?php 
//header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, access');
header("Content-Type: application/json");
require_once(dirname(__FILE__) . "/../include/users.php");
require_once(dirname(__FILE__) . "/../include/verifications.php");
require_once(dirname(__FILE__) . "/../include/lib/request.php");
require_once(dirname(__FILE__) . "/../include/lib/response.php");
require_once(dirname(__FILE__) . "/../common/data/usr_token.php");

echo json_encode($_POST);

$response = new Response();
$request = new Request();
$cr = $request->check_request();
?>