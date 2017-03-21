<?php
if(!defined('__FEEDBACKS__')){
    define('__FEEDBACKS__',1);
	require_once(dirname(__FILE__) . "/../common/db/db.handler.php");
	require_once(dirname(__FILE__) . "/lib/docs.php");
	
	class FEEDBACKS extends DOCS{ }
}
?>