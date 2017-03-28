<?php
if(!defined('__MSGS__')){
    define('__MSGS__',1);
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/lib/docs.php");
	
	class MSGS extends DOCS{ }
}
?>