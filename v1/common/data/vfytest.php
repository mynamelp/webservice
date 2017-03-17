<?php 
error_reporting(E_ALL);
ini_set('display_errors','On');
require_once(dirname(__FILE__) . '/verify.php');

$v = new VERIFY();

//var_dump($v->vfy_string("tss", 4, 20));
//var_dump($v->vfy_string("tsfffffffffffffffffffffffffffsss", 4, 20));
//var_dump($v->vfy_string("ts@sss", 4, 20));
//var_dump($v->vfy_string("ts!~sss", 4, 20));

//var_dump($v->vfy_username("tss", 4, 20));
//var_dump($v->vfy_pw("tsa~ss", 4, 20));

//var_dump($v->vfy_email("1@lap.ingguo.com"));

//var_dump($v->vfy_tel('+862132'));


?>