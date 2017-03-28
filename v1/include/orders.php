<?php
if(!defined('__ORDERS__')){
    define('__ORDERS__',1);
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/lib/docs.php");
	
	class ORDERS extends DOCS{ }
}
?>