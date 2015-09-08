<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if (!$this->user->logged_in)
		{
			redirect('auth/login', 'refresh'); # Harus Login
		}
	}
	
	function index(){
		$view_data["tanggal"] 	 = date('d-m-Y');
		$view_data["module"] 	 = "modules";
		$view_data["title_page"] = "JR &raquo; Setting Module";
		$view_data["script_load"] 	= 
								  "<script src='".base_url('assets/js/jquery.dataTables.min.js')."' type='text/javascript'></script>\n".
								  "<script src='".base_url('assets/js/dataTables.bootstrap.min.js')."' type='text/javascript'></script>\n".
								  "<script src='".base_url('assets/js/fnReloadAjax.js')."' type='text/javascript'></script>\n";
	
		$this->layout->load($view_data,"form");
	}
}