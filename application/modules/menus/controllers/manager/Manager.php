<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Manager extends CI_Controller {
		
		var $nav;
		var $level = 0;
		var $view_data = array();
		
		function __construct()
		{
			parent::__construct();
			
			$this->user_data = $this->session->userdata;
			$this->load->model("menus_model","menus");
			
			$this->view_data = array("module"=>"menus",
									 "title_page"=>"CI-BASE &raquo; Data Menu",
									 "breadcrumbs"=>"<li class='active'>Menu </li>",
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
			
			$this->view_data["menus"] = $this->list_all();
			//$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
		
			$this->layout->load($this->view_data,"menus");
		}
		
		public function list_all()
		{
			$list = $this->menus->get_list();
			
			$tree = $this->buildTree($list);
			
			$res = array();
			foreach($tree as $key => $values){
				
				$xtree[$values["id"]."-0"] = $tree[$key];
				if(isset($values['children']))
				{
				   foreach($values["children"] as $ckey =>$cval){
					$ctree[$cval["parent_id"]."-".$cval["id"]] =  $cval;//ues['children'];// array_merge($xtree, $values['children']);
				   }
				   unset($tree[$key]['children']);
				}
				unset($xtree[$values["id"]."-0"]['children']);
				
			}
			
			
			$res = array_merge($xtree,$ctree);
			ksort($res);
			
			foreach($res as $rkey => $rval){
				if(isset($rval["icon"])){
				//	echo($rval["icon"]."<br>");
					$res[$rkey]["icon"] = "<i class='fa ".$rval["icon"]."'></i>";
				}
			}
			//echo("<pre>");
			//print_r($res);
			return $res;
			// */
		}
		
		private function buildTree(array $elements, $parentId = 0) {
			$branch = array();

			foreach ($elements as $element) {
				if ($element['parent_id'] == $parentId) {
					$children = $this->buildTree($elements, $element['id']);
					if ($children) {
						$element['children'] = $children;
					}
					$branch[] = $element;
				}
			}

			return $branch;
		}


		public function add()
		{
			$this->view_data["tanggal"] = date("d-m-Y");
			$this->view_data["title_page"]="JR &raquo; Data Menu &raquo; Add";
			$this->view_data["breadcrumbs"] ="<li><a href='".base_url("manager/menus")."'>Application Menu</a></li><li class='active'>Add Data</li>";
			//$this->view_data["list_group"] = $this->members->list_group();
			$this->view_data["data_menu"] = array();
			$this->view_data["parent"] = $this->list_all();
			$this->view_data["state_form"] = "add";	
			//$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
		
			$this->layout->load($this->view_data,"form");
			
			
		}
		
		public function edit($id_menu=null)
		{
			
			//echo($_REQUEST["username"]);
			$this->view_data["tanggal"] = date("d-m-Y");
			$this->view_data["title_page"]="JR &raquo; Data Menu &raquo; Edit";
			$this->view_data["breadcrumbs"] ="<li><a href='".base_url("manager/menus")."'>Application Menu</a></li><li class='active'>Edit Data</li>";
			$this->view_data["state_form"] = "edit";
			$this->view_data["parent"] = $this->list_all();
			$this->view_data["data_menu"] = $this->menus->get($id_menu);
			//$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
		
			$this->layout->load($this->view_data,"form");
			
		}
		
		public function save()
		{
			if($this->menus->save($_POST)){
				echo(json_encode(array("success"=>true,"message"=>"Data sudah disimpan.")));				
			}
			//echo(json_encode(array("success"=>true)));			
		}
		
		public function update($id_menu=null)
		{
			//echo("<pre>");
			//echo($id_menu);
			//print_r($_POST);
			//echo(json_encode(array("success"=>true,"data"=>$_POST)));
			
			if($this->menus->update($_POST,$id_menu)){
				echo(json_encode(array("success"=>true,"message"=>"Perubahan data sudah disimpan.")));
			}
				
			//*/
		}
		
		public function delete($id_user=null)
		{
			if($this->menus->delete($id_user)){
				//$this->session->set_flashdata('success_msg', "Data sudah dihapus");
				echo(json_encode(array("success"=>true,"message"=>"Perubahan data sudah disimpan.")));
			}
			//echo(json_encode(array("success"=>true)));	
		}
		
		public function cek_input()
		{
			//echo($_REQUEST["username"]);
			/*
			$this->view_data["tanggal"] = date("d-m-Y");
			$this->view_data["title_page"]="JR &raquo; Data Menu &raquo; Edit";
			$this->view_data["breadcrumbs"] ="<li><a href='".base_url("manager/menus")."'>Application Menu</a></li><li class='active'>Edit Data</li>";
			$this->view_data["state_form"] = "edit";
			$this->view_data["parent"] = $this->list_all();
			$this->view_data["data_menu"] = $this->menus->get($id_menu);
			//$this->view_data["list_menu"] = $this->libcms->list_parent_menu();
			// */
			$this->layout->load($this->view_data,"input");
		}
	}