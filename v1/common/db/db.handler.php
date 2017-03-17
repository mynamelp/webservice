<?php
error_reporting(E_ALL);
if(!defined('__DB__')){
    define('__DB__',1);
	require_once(dirname(__FILE__) . "/mysql.php");
	require_once(dirname(__FILE__) . "/mongo.php");

	Class DB {
		private $_type;
		private $db;
		//private $connection;
		
		//private $handle;
		
		public function __construct(){
			$this->_type = DB_type;
			if($this->_type){
				$this->db = new MYSQLDB();
			}else{
				$this->db = new MGDB();
			}
		}
		//select
		public function query(){
			if($this->_type){
				//mysql
			}else{
				//mongo
			}
		}
		//insert
		public function insert(){
			if($this->_type){
				//mysql
			}else{
				//mongo
			}
		}
		//update
		public function update(){
			if($this->_type){
				//mysql
			}else{
				//mongo
			}
		}
		//delate
		public function delate(){
			if($this->_type){
				//mysql
			}else{
				//mongo
			}
		}
	}
 }