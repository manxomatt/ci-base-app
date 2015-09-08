<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->layout->theme = "default";
	}

	function index()
	{
		redirect("auth/login","refresh");
	}

	//log the user in
	function login()
	{
		if ($this->user->logged_in)
		{
			redirect(base_url('dashboard'), 'refresh');
		}

		if (is_ajax())//$this->form_validation->run() == true)
		{
			$remember = false;//(bool) $this->input->post('remember');
			
			if ($this->user->login($this->input->post('login'), $this->input->post('password'), $remember))
			{
				echo(json_encode(array("success"=>true)));
			}
			else
			{
				echo(json_encode(array("success"=>false,"error"=>array("message"=>"Login atau password anda salah, silahkan periksa kembali"))));
			}
			// */
		
		}
		else
		{
			$this->view_data["module"] 		= "auth";
			$this->view_data["title_page"] 	= "CI-BASE &raquo; Login";
			$this->view_data["script_load"] = "<script src='".base_url("assets/js/iCheck/icheck.min.js")."' type='text/javascript'></script>\n".
										 "<script src='".base_url("assets/js/app/login.js")."' type='text/javascript'></script>\n";
									  
									  
			$this->layout->load($this->view_data,"login");
		}
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";
		$logout = $this->user->logout();
		//redirect them to the login page
		//redirect('frontend', 'refresh');
		redirect('auth', 'refresh');
	}
	
	
}
