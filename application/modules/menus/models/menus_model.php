<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * $Id$
 *
 *
 */
  

class Menus_model extends CI_Model{
	var $fields = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->fields = array(
				"menus" => array(
					"id" => "",
					"parent_id" => "",
					"title" => "",
					"url" => "",
					"menu_order" => "1",
					"icon" => "",					
					"description" => ""
			));
	
	}
	
	public function get($id_menu)
	{
		$r = $this->db->get_where("menus",array("id"=>$id_menu))->row();
		//echo($this->db->last_query());
		return $r;
	}
	
	public function get_list($params = array())
	{
		
		$query = $this->db->query("SELECT * FROM menus mn 
						ORDER BY mn.parent_id,mn.menu_order");//-- WHERE mn.parent_id=0
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
		$this->db->delete("menus",array("id"=>$id));
		
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
			$this->fields["menus"][$key] = $val;
		}
		$this->db->db_debug = false;
		$this->db->insert("menus",$this->fields["menus"]);
		
		if(!($this->db->error()["code"])){
			return true;
			
		}else{
			echo(json_encode(array("success"=>false,"error"=>array("message"=>$this->db->error()["message"]))));
			return false;
		}
	}
	
	public function update($xdata = array(),$id=null){
		
		$this->db->db_debug = false;
		$this->db->update("menus",$xdata,array("id"=>$id));
		//echo($this->db->last_query());
		if(!($this->db->error()["code"])){
			return true;			
		}else{
			//$this->session->set_flashdata('err_msg', $this->db->error()["message"]);
			echo(json_encode(array("success"=>false,"error"=>array("message"=>$this->db->error()["message"]))));
				
			return false;
		}
		
	}
	
	
}