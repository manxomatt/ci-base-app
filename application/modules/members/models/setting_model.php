<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * $Id$
 *
 *
 */
  

class Setting_model extends CI_Model{

	var $fields = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->fields = array(
				"mstmodule" => array(
					"id" => "",
					"kode_module" => "",
					"nama_module" => "members",
					"url_module" => "members",
					"iurl_module" => "members",
					"kode_parent" => "0",
					"config" => "",
					"is_active" => "1"
			));
		
		$q = $this->db->get_where("mstmodule",array("nama_module"=>$this->fields["mstmodule"]["nama_module"]));
		if($q->result_id->num_rows > 0){
			$r = $q->row();
			foreach($r as $key=>$val)
			{
				$this->fields["mstmodule"][$key] = $val;
			}			
		}
	}
	
	public function update($xdata = array(),$module_name=null){
		
		foreach($xdata as $key=>$val)
		{
			if($key == "config"){
				$val = serialize((array)$val);
			}
			$this->fields["mstmodule"][$key] = $val; 
		}
		$this->db->db_debug = false;
		$this->db->update("mstmodule",$this->fields["mstmodule"],array("nama_module"=>$module_name));
		//echo($this->db->last_query());
		
		if(!($this->db->error()["code"])){
			echo(json_encode(array("success"=>true,"msg"=>"Data module <b> ".$this->libcms->format_module_name($module_name)."</b> sudah diupdate")));
		}else{
			echo(json_encode(array("success"=>false,"errors"=>array("reason"=>$this->db->error()["message"]))));
		}
		/*
		if(!($this->db->error()["code"])){

			return true;			
		}else{
			$this->session->set_flashdata('err_msg', $this->db->error()["message"]);
			return false;
		}
		// */
		//echo("<pre>");
		//print_r($this->fields);
	}
}