<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');
require_once(dirname(__FILE__) . "\db.handler.php");

/**
* mysql test connection
*
**/
/*
$db = new MYSQLDB();
$condition = array();

$sql = "select * from users";
$collection = $db->query($sql);
while ($row = mysql_fetch_assoc($collection)) {
    var_dump($row);
}
*/

/**
* mongodb test connection
*
**/
$db = new MGDB('users');// users collection
//$r = $db->find(array());

//$r = $db->insert(array('id'=>3, 'uid'=>987654, 'username'=>1333988676, 'nick'=>'lucy', 'tips'=>array(1,2,3,4,5)));

$r = $db->delate(array('id'=>3));
var_dump($r);
?>