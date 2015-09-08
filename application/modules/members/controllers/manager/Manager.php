<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Manager extends CI_Controller {
		
		var $nav;
		var $level = 0;
		var $view_data = array();
		
		function __construct()
		{
			parent::__construct();
			
			$this->user_data = $this->session->userdata;
			$this->load->model("members_model","members");
			
			$this->view_data = array("module"=>"members",
									 "title_page"=>"JR &raquo; Data Pengguna",
									 "breadcrumbs"=>"<li class='active'>Pengguna</li>",
									 "script_load"=>"");
			/*
			$rmodule 	 = $this->db->get_where("mstmodule",array("nama_module"=>$this->view_data["module"]))->row();
			$config 	 = unserialize($rmodule->config);
			$id_module 	 = $rmodule->kode_module;
			$this->valid_access = $this->libcms->is_valid_access($this->user_data,$config,$id_module);	
			*/
			$this->valid_access = true;
			if (!$this->user->logged_in) # HARUS LOGIN
			{
				redirect(base_url('auth/login'));
			}
		}
		
		function index()
		{
			
			$this->view_data["users"] = $this->list_all();
			//$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
		
			$this->layout->load($this->view_data,"members");
		}
		
		public function list_all()
		{
			$list = $this->members->get_list();
			$i = 0;
			foreach($list as $key=>$val)
			{
				$i++;
				$list[$key]["no"]	   = $i;
			}
			return $list;
		}
		
		public function add()
		{
			$this->view_data["tanggal"] = date("d-m-Y");
			$this->view_data["title_page"]="JR &raquo; Data Pengguna &raquo; Add";
			$this->view_data["breadcrumbs"] ="<li><a href='".base_url("admin/members")."'>Pengguna</a></li><li class='active'>Data Baru</li>";
			$this->view_data["list_group"] = $this->members->list_group();
			$this->view_data["data_user"] = array();
			$this->view_data["state_form"] = "add";	
			//$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
		
			$this->layout->load($this->view_data,"form");
			
			
		}
		
		public function edit($id_user=null)
		{
			
			//echo($_REQUEST["username"]);
			$this->view_data["tanggal"] = date("d-m-Y");
			$this->view_data["title_page"]="JR &raquo; Data Pengguna &raquo; Edit";
			$this->view_data["breadcrumbs"] ="<li><a href='".base_url("admin/members")."'>Pengguna</a></li><li class='active'>Edit Data</li>";
			$this->view_data["list_group"] = $this->members->list_group();
			$this->view_data["data_user"] = $this->members->get(array("id"=>$id_user));
			$this->view_data["state_form"] = "edit";
			//$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
		
			$this->layout->load($this->view_data,"form");
			
		}
		
		public function save()
		{
			if($this->members->save($_POST)){
				echo(json_encode(array("success"=>true,"message"=>"Data sudah disimpan.")));				
			}
			//echo(json_encode(array("success"=>true)));			
		}
		
		public function update($id_user=null)
		{
			//echo("<pre>");
			//print_r($_POST);
			
			if($this->members->update($_POST,$id_user)){
				echo(json_encode(array("success"=>true,"message"=>"Perubahan data sudah disimpan.")));
			}
				
			//*/
		}
		
		public function delete($id_user=null)
		{
			if($this->members->delete($id_user)){
				//$this->session->set_flashdata('success_msg', "Data sudah dihapus");
				echo(json_encode(array("success"=>true,"message"=>"Perubahan data sudah disimpan.")));
			}
			//echo(json_encode(array("success"=>true)));	
		}
	}