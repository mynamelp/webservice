<?php
if(!defined('__USERS__')){
    define('__USERS__',1);
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/lib/docs.php");
	
	class USERS extends DOCS{ }
}
?>