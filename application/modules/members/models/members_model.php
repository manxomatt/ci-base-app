<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * $Id$
 *
 *
 */
  

class Members_model extends CI_Model{
	var $fields = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->fields = array(
				"users" => array(
					"id" => "",
					"username" => "",
					"password" => "",
					"email" => "",
					"fullname" => "",
					"groupid"=>"",
					"lastvisit" => "",
					"registered" => "",	
					"isonline" => "",
					"activation" => "",					
					"isactive" => "",
					"ipaddress" => ""	
			));
	
	}
	
	public function get($params=array())
	{
		$r = $this->db->get_where("users",$params)->row();
		//echo($this->db->last_query());
		return $r;
	}
	public function get_list($params = array())
	{
		$default_params = array
		(
			'order_by' => 'id DESC',
			'limit' => 1,
			'start' => null,
			'where' => null,
			'like' => null,
		);
		
		foreach ($default_params as $key => $value)
		{
			$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
		}
		
		if (!is_null($params['like']))
		{
			$this->db->like($params['like']);
		}
		if (!is_null($params['where']))
		{
			$this->db->where($params['where']);
		}
		
		$this->db->order_by($params['order_by']);
		$this->db->select("users.*,groups.group as group_name");
		$this->db->join('groups', 'groups.id = users.groupid', 'left');
		//$this->db->join('samsat_lokasi sl', 'sl.id_samsat_lokasi = users.id_samsat_lokasi', 'left');
		$this->db->from('users');
		$query = $this->db->get();
		if ($query->num_rows() == 0 )
		{
			$result =  false;
		}else{
			$result = $query->result_array();
		}
		
		return $result;
	}
	
	public function delete($id=null){
		$this->db->db_debug = false;
		$this->db->delete("users",array("id"=>$id));
		
		if(!($this->db->error()["code"])){
			return true;
			
		}else{
			//$this->session->set_flashdata('err_msg', $this->db->error()["message"]);
			echo(json_encode(array("success"=>false,"error"=>array("message"=>$this->db->error()["message"]))));
			return false;
		}
		
	}
	public function save($xdata = array())
	{
		foreach($xdata as $key=>$val)
		{
			if($key == "id_modul" OR $key == "id_wilayah_samsat"){
				$val = serialize($val);
			}
			
			if($key == "password"){
				$val = $this->user->prep_password($val);
			}
			 
			$this->fields["users"][$key] = $val;
			$this->fields["users"]["isactive"] = "1"; // default user adalah aktif
			$this->fields["users"]["registered"] = time();
		}
		$this->db->db_debug = false;
		$this->db->insert("users",$this->fields["users"]);
		
		if(!($this->db->error()["code"])){
			return true;
			
		}else{
			echo(json_encode(array("success"=>false,"error"=>array("message"=>$this->db->error()["message"]))));
			return false;
		}
	}
	
	public function update($xdata = array(),$id=null){
		$rd = $this->db->get_where("users",array("id"=>$id))->row_array();
		foreach($rd as $key=>$val)
		{
			$this->fields["users"][$key] = $val;
		}
		
		foreach($xdata as $key=>$val)
		{
			if($key == "id_modul" OR $key == "id_wilayah_samsat"){
				$val = serialize($val);
			}
			if($key == "password"){
				if($val != ''){
					$val = $this->user->prep_password($val);
				}else{
					$val = $this->fields["users"]["password"];
				}
			}
			$this->fields["users"][$key] = $val;
			$this->fields["users"]["id"] = $id;
			$this->fields["users"]["isactive"] = "1"; // default user adalah aktif
		}
		$this->db->db_debug = false;
		$this->db->update("users",$this->fields["users"],array("id"=>$id));
		//echo($this->db->last_query());
		if(!($this->db->error()["code"])){
			return true;
			
		}else{
			//$this->session->set_flashdata('err_msg', $this->db->error()["message"]);
			echo(json_encode(array("success"=>false,"error"=>array("message"=>$this->db->error()["message"]))));
				
			return false;
		}
		
	}
	public function list_lokasi_samsat()
	{
		$this->db->order_by("id_samsat_lokasi");
		$this->db->where('id_samsat_lokasi <>','0000000' );
		$r = $this->db->get("samsat_lokasi")->result();
		return $r;
	}
	
	public function list_role()
	{
		$this->db->order_by("id");
		$r = $this->db->get("roles")->result();
		return $r;
	}
	
	public function list_group()
	{
		$this->db->order_by("id");
		$r = $this->db->get("groups")->result();
		return $r;
	}
	
	public function list_modul()
	{
		$this->db->order_by("id");
		$r = $this->db->get("mstmodule")->result();
		return $r;
	}
	
	public function list_wilayah()
	{
		$this->db->order_by("id_wilayah_kabupaten_kodya");
		$r = $this->db->get("wilayah_kabupaten_kodya")->result();

		return $r;
	}
}