<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends CI_Controller {
	
	var $view_data;

	function __construct()
	{
		parent::__construct();
		$this->layout->theme = "manager";
		
		$this->view_data = array(
							"module"=>"dashboard",
							"title_page"=>"CI-BASE &raquo; SYSTEM MANAGER",
							"script_load"=>''
						);
	}

	function index()
	{
		//redirect("manager/dashboard","refresh");
		//echo("Manager:Dashboard");
			
		$this->layout->load($this->view_data,"dashboard");
	}
}