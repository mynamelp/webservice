<?php 
error_reporting(E_ALL);
if(!defined("__DBCONFIG_")){
	define("__DBCONFIG_",1);
	define('DB_type', 1); // mongo 0 ,mysql 1
    // mongo db info
	define("MONGO_ADDR",'mongodb://106.15.102.76:27017');//127.0.0.1:28017
    //define("MONGO_RS",'rs1');
    define("MONGO_DB",'lamf');
	define('MONGODB_LOGPATH','./mongologs.txt');//mongo log file path
    //mysql db info
    define('MYSQL_ADDR','127.0.0.1');
	define('MYSQL_USR','root');
	define('MYSQL_PWD','');
	define('MYSQL_DB','lamf');
	define('MYSQL_CHST', 'utf8');// charset
	define('MYSQL_PCNT',0);//pconnect 0 or 1
	define('MYSQL_LOGPATH','./sqllogs.txt');//sql log file path
}

?>