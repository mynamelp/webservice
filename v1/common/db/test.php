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

$db = new MGDB();// users collection
$collection = $db->sel_coll('users');
$cursor = $collection->find();
var_dump(iterator_to_array($cursor));
?>