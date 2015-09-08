<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {
	var $db_obj;
	
	public function __construct(){
		parent::__construct();
		ini_set('display_errors', 0);
		set_error_handler("customError"); 
		$this->load->helper('file');
   		//$this->load->dbforge();			
	}
	
	public function index()
	{
		$base_url = $this->config->item('base_url');
		$this->view_data = array("module"=>"install",
								  "title_page"=>"Install Application",
								  "script_load"=>""
								);
		$this->layout->theme = "install";
		$this->layout->load($this->view_data,"index");
		
	}
	
	public function setup_db(){
		$this->read_database_file();
		$this->view_data = array("module"=>"install",
								  "title_page"=>"Setup Database",
								  "script_load"=>""
								);
		$this->view_data["dbconfig"] = $this->read_database_file();
		$this->layout->theme = "install";
		$this->layout->load($this->view_data,"setup_db");
	}
	
	public function test_db_connect(){
		
		$this->can_connect_db($_REQUEST["username"],$_REQUEST["password"],$_REQUEST["hostname"],$_REQUEST["database"]);
		
	}
	
	public function save_db_config()
	{
		$result_config = $this->read_update_database_file($_REQUEST["username"],$_REQUEST["password"],$_REQUEST["hostname"],$_REQUEST["database"]);
		$data_config   = implode("\n",$result_config);
		$fp = fopen(APPPATH."config/database.php", 'w');
		fwrite($fp, $data_config);
		fclose($fp);
		echo(json_encode(array("success"=>true,"message"=>"Database configuration file was updated ")));
			exit;
		//
	}
	
	public function save_add_admin()
	{
		//echo(time());
		
		$this->load->database();
		
		if($_POST["password"] !== $_POST["repassword"]){
			echo(json_encode(array("success"=>false,"errors"=>array("reason"=>"Password tidak sama" ))));
		}else{
			$xdata = array(
				"username"=>$_POST["username"],
				"password"=>_prep_password($_POST["password"]),
				"fullname"=>"SYSTEM ADMINISTRATOR",
				"groupid"=>1,
				"registered"=>time(),
				"isactive"=>1
			);
			$this->db->insert("users",$xdata);
			if($this->db->error()["code"]){
				echo(json_encode(array("success"=>false,"errors"=>array("reason"=>$this->db->error()["message"]))));
			}
		}
		echo(json_encode(array("success"=>true,"message"=>"Success, User telah dibuat" )));
		// */
	}
	
	public function get_db_state(){
		$this->load->database();
		echo(json_encode(array("success"=>true,"message"=>"Success, Database name : <b> ".$this->db->database."</b>")));
	
	}
	
	public function generate_table(){
		$this->load->database();
		$this->load->dbforge();
		$this->db->db_debug = false;
		$this->view_data = array("module"=>"install",
								  "title_page"=>"Setup Table",
								  "script_load"=>""
								);
		
		$this->table_session();
		$this->table_users();
		$this->table_groups();
		$this->table_modules();
		
		$this->layout->theme = "install";
		$this->layout->load($this->view_data,"setup_table");
	}
	
	public function add_admin()
	{	
		$this->load->database();
		$this->load->dbforge();
		$this->view_data = array("module"=>"install",
								  "title_page"=>"Setup Administrator User",
								  "script_load"=>""
								);
		
		$this->layout->theme = "install";
		$this->layout->load($this->view_data,"add_admin");
		
	}
	
	private function table_session()
	{
		$fields = Array
			(
				"id" => Array
                (
                    "type" => "varchar",
                    "constraint" => 40
                ),
				"ip_address" => Array
                (
                    "type" => "varchar",
                    "constraint" => 45
                ),
				"timestamp" => Array
                (
                    "type" => "int",
                    "constraint" => 10
                ),
				"data" => Array
                (
                    "type" => "blob"
                )
			);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('ci_sessions', TRUE);
	}
	private function table_groups()
	{
		$fields = Array
			(
				"id" => Array
                (
                    "type" => "int",
                    "constraint" => 11
                ),
				"group" => Array
                (
                    "type" => "char",
                    "constraint" => 100
                )
			);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('groups', TRUE);
		$roles = array(
				array(
					"id"=>"1",
					"group"=>"ADMINISTRATOR"
				),
				array(
					"id"=>"2",
					"group"=>"USER"
				)
			);
		$this->db->insert_batch('groups', $roles);
		//return $fields;
	}
	private function table_users(){
		$fields = array(
				"id" => array(
                    "type" => "INT",
                    "constraint" => 11,
					"unsigned" => TRUE,
					"auto_increment" => TRUE
                ),
				"username" => array(
                    "type" => "varchar",
                    "constraint" => 50
                ),
				"password" => array(
                    "type" => "varchar",
                    "constraint" => 50
                ),
				"email" => array(
                    "type" => "varchar",
                    "constraint" => 255,
					"null"=>true
                ),
				"fullname" => array(
                    "type" => "char",
                    "constraint" => 255
                ),
				"groupid" => Array
                (
                    "type" => "int",
                    "constraint" => 11
                ),
				"lastvisit" => Array
                (
                    "type" => "int",
                    "constraint" => 11,
					"null"=>true
                ),
				"registered" => Array
                (
                    "type" => "int",
                    "constraint" => 11
                ),
				"isonline" => Array
                (
                    "type" => "tinyint",
                    "constraint" => 1,
					"default"=>0
                ),
				"activation" => Array
                (
                    "type" => "varchar",
                    "constraint" => 255,
					"null"=>true
                ),
				"isactive" => Array
                (
                    "type" => "tinyint",
                    "constraint" =>1,
					"default"=>0
                ),
				"ipaddress" => Array
                (
                    "type" => "char",
                    "constraint" => 20,
					"null"=>true
                )

        );
		
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('users', TRUE);
		//return $fields;
	}
	
	private function table_modules(){
		$fields = Array
				(
					"id" => Array
						("type" => "int",
						 "constraint" => 11,
						 "auto_increment" => TRUE),
					"modulename" => Array
						("type" => "char",
						 "constraint" => 200),
					"icon" => Array
						("type" => "char",
						 "constraint" => 100,
						 "null"=>true
						),
					"moduleurl" => Array
						("type" => "char",
						 "constraint" => 255,
						 "null"=>true
						),
					"parentid" => Array
						("type" => "char",
						 "constraint" => 4,
						 "default"=>0
						),
					"config" => Array
						("type" => "varchar",
						 "constraint" => 5000,
						 "null"=>true
						),
					"isactive" => Array
						("type" => "tinyint",
						 "constraint" => 1,
						 "default"=>0
						)

				);
		$this->dbforge->add_field($fields); 
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('modules', TRUE);
		//return fields;
	}
	
	
	private function read_database_file(){
		$file_db = $trimmed = file(APPPATH."config/database.php", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		foreach ($file_db as $lineNumber => $line) {
			if(strpos($line, "'hostname'") !== false) {
				$h_data = explode("=>",$file_db[$lineNumber]);
				$out["hostname"] = str_replace(array("'",","),"",$h_data[1]);
			}
			
			if (strpos($line, "'username'") !== false) {
				$u_data = explode("=>",$file_db[$lineNumber]);
				$out["username"] = str_replace(array("'",","),"",$u_data[1]);
			}
			
			if (strpos($line, "'password'") !== false) {
				$p_data = explode("=>",$file_db[$lineNumber]);
				$out["password"] = str_replace(array("'",","),"",$p_data[1]);
			}
			
			if (strpos($line, "'database'") !== false) {
				$d_data = explode("=>",$file_db[$lineNumber]);
				$out["database"] = str_replace(array("'",","),"",$d_data[1]);
			}
// */
		}
		//echo("<pre>");
		//print_r($out);
		return $out;		
	}
	
	private function read_update_database_file($username,$password,$hostname,$database){
		$file_db = $trimmed = file(APPPATH."config/database.php", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		foreach ($file_db as $lineNumber => $line) {
			if (strpos($line, "'hostname'") !== false) {
				$file_db[$lineNumber] = "'hostname'=>'".$hostname."',";
			}
			if (strpos($line, "'username'") !== false) {
				$file_db[$lineNumber] = "'username'=>'".$username."',";
			}
			
			if (strpos($line, "'password'") !== false) {
				$file_db[$lineNumber] = "'password'=>'".$password."',";
			}
			
			if (strpos($line, "'database'") !== false) {
				$file_db[$lineNumber] = "'database'=>'".$database."',";
			}
		}
		
		return $file_db;
		
	}
	
	private function can_connect_db($user,$password,$host,$database){
		
		
		$dsn = "mysqli://".$user.":".$password."@".$host;//."/".$database;
		$this->load->database($dsn);
		$this->load->dbutil();
		$this->load->dbforge();
		
		$check = $this->dbutil->database_exists($database);
		if(!$check){
			if($_POST["create_db"] == 1){
				if ($this->dbforge->create_database($database))
				{
					echo(json_encode(array("success"=>true,"message"=>"Database  <b>".$database."</b> was created ")));
					exit;
				}
			}else{
				echo(json_encode(array("success"=>false,"errors"=>array("reason"=>"Database : <b>".$database."</b> tidak ada. "))));
				exit;
			}
		}else{
			echo(json_encode(array("success"=>true,"message"=>"Database connection successfull ")));
			exit;
		}
		
	}
	
	
}
