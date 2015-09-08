<?php 

class Libcms{
	
	protected $obj = null;
	function __construct()
	{
		if ( is_null($this->obj) )
		{
			$this->obj =& get_instance();
			
		}
	}
	
	public function get_config_system(){
		$q = $this->obj->db->query("SELECT * FROM settings WHERE id='1'");
		if($q->num_rows() > 0){
			$sysconfig = $q->row()->config;
		}
		
		return unserialize($sysconfig);
		
	}
}