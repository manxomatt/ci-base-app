<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->layout->theme = "LTE";
	}

	function index()
	{
		redirect("manager/dashboard","refresh");
	}
}