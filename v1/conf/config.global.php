<?php 
error_reporting(E_ALL);
if(!defined("__CONFIG_")){
	define("__CONFIG_",1);
	date_default_timezone_set("Asia/Hong_Kong");
	define('DEBUG',1);// (0 or 1)
	// user token salt
	define('SALT', 'fdsafagfdgv43532ju76jM');
	define('_LOG',1);//log 0 or 1
	define('REQ_LOGPATH','./reqlogs.txt');//mongo log file path
	
}

?>