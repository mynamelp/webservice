<?php 
if(!defined('__GOLBALFUNCS__')){
    define('__GOLBALFUNCS__',1);
	
	function __isInt($value){
		if(is_numeric($value) && is_int($value+0)){
			return $value;
		}else{
			return false;
		}
	}
}
?>