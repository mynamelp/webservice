<?php
if(!defined('__PLANS__')){
    define('__PLANS__',1);
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/lib/docs.php");
	
	class PLANS extends DOCS{ }
}
?>