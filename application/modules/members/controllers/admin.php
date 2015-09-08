<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
	
	var $view_data = array();
	function __construct()
	{
		parent::__construct();
		$this->user_data = $this->session->userdata;
		$this->view_data = array("module"=>"members",
								 "title_page"=>"UPC | SAMSAT &raquo; Members &raquo; Pengaturan",
								 "breadcrumbs"=>"<li><a href='".base_url("setting_modul")."'>Members</a></li><li class='active'>Pengaturan</li>",
								 "script_load"=>"");
		$this->load->model("setting_model","setting");
		/*
		$rmodule 	 = $this->db->get_where("mstmodule",array("nama_module"=>"setting_modul"))->row();
		$config 	 = unserialize($rmodule->config);
		$id_module 	 = $rmodule->kode_module;
		//echo($id_module);
		$this->valid_access = $this->libcms->is_valid_access($this->user_data,$config,$id_module);	
			*/
		if(!$this->user->logged_in){
			//echo("gagal");
			redirect(base_url('auth/login'));
		}
	}
	
	public function index(){
		$q = $this->db->query("SELECT * FROM mstmodule WHERE nama_module='".$this->view_data["module"]."'");
		$qroles = $this->db->get("tblroles");
		$qgroup = $this->db->get("tblgroup");
		
		
		if($q->result_id->num_rows > 0)
			$this->view_data["sysmodule"] = $q->row();
		
		if($qroles->result_id->num_rows > 0)
			$this->view_data["lroles"] = $qroles->result();
		
		if($qgroup->result_id->num_rows > 0)
			$this->view_data["lgroup"] = $qgroup->result();
		
		$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
		$this->layout->load($this->view_data,"admin/setting");		
	}
	
	public function save()
	{
				
		$config = json_decode($_REQUEST["data"]);
		$this->setting->update(array("config"=>$config),$this->view_data["module"]);
		
	}
}