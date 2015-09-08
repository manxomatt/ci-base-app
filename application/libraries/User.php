<?php
/*
| User Library for CI 2.00
| Note this module depends on
| the config constants.php
| having the following definitions
| added to it:
| define('LEVEL_NONE', 0);
| define('LEVEL_VIEW', 1);
| define('LEVEL_ADD', 2);
| define('LEVEL_EDIT', 3);
| define('LEVEL_DEL', 4);
|
|
*/



	class User {
		
		var $id = 0;
		var $logged_in = false;
		var $auto_login = false;
		var $lang;
		var $email;
		var $username = '';
		var $id_samsat_lokasi = '';
		var $g_id;
		var $table = 'users';
		var $level = array();
		var $groups = array();
		
		
		function __construct()
		{
			$this->obj =& get_instance();
			$this->obj->load->library('encryption');
			
			
			//this filter allows us to use another type of auth (LDAP etc)
			$this->obj->plugin->add_filter('user_auth', array(&$this, '_user_auth'), 30, 2);
			
			$this->_session_to_library();
			//$this->_get_levels();
			//$this->_get_groups();
			$this->_update_fields();
		}
		
		
		function _update_fields()
		{	
			if ($this->logged_in)
			{
				if (!empty($_SERVER["HTTP_CLIENT_IP"]))
				{
				 //check for ip from share internet
				 $ip = $_SERVER["HTTP_CLIENT_IP"];
				}
				elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
				{
				 // Check for the Proxy User
				 $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
				}
				else
				{
				 $ip = $_SERVER["REMOTE_ADDR"];
				}
		
				$ipaddress = $ip; // Tambah kolom ip_address [03-2015]
				$this->update($this->username, 
						array('activation' => '',
							  'ipaddress'=>$ipaddress, 
							  'lastvisit' => time(), 
							  'isonline' => 1));
			}
		}

		function _get_groups()
		{
			$this->groups[] = '0';

			if($this->logged_in){
				$this->groups[] = '1';
				/*
				$this->obj->db->select('gm.g_id,gr.g_name');
				$this->obj->db->where('gm.g_user', $this->username);
				$this->obj->db->where("(gm.g_from <= '" . time() . "' OR gm.g_from=0)");
				$this->obj->db->where("(gm.g_to >= '" . time() . "' OR gm.g_to=0)");
				*/
				$query = $this->obj->db->query("SELECT gm.g_id, gr.g_name 
											FROM group_members gm 
											LEFT JOIN groups gr ON gm.g_id=gr.g_id 
											WHERE gm.g_user = '".$this->username."' 
											AND (gm.g_from <= '" . time() . "' OR gm.g_from =0) 
											AND (gm.g_to >= '" . time() . "' OR gm.g_to =0)");//$this->obj->db->get("group_members gm LEFT JOIN groups gr ON gm.g_id=gr.g_id");
				
				//echo($this->obj->db->last_query());
				if($rows = $query->result_array()){
					foreach ($rows as $row) {
						$this->groups[] = $row['g_id'];
						$this->groups[] = $row['g_name'];
					}
				}
				//user also is member of his own groups
				
				$this->obj->db->select('g_id,g_name');
				$this->obj->db->where('g_owner', $this->username);
				$query = $this->obj->db->get("groups");
				if($rows = $query->result_array()){
					foreach ($rows as $row) {
						$this->groups[] = $row['g_id'];
						$this->groups[] = $row['g_name'];
					}
				}
			}		
		}

		
		function _prep_password($password)
		{
			// Salt up the hash pipe
			// Encryption key as suffix.
			return sha1($password.$this->obj->config->item('encryption_key'));
			//return $this->obj->encryption->sha1($password.$this->obj->config->item('encryption_key'));
		}
		
		function prep_password($password)
		{
			// Salt up the hash pipe
			// Encryption key as suffix.
			return sha1($password.$this->obj->config->item('encryption_key'));
			//return $this->obj->encrypt->sha1($password.$this->obj->config->item('encryption_key'));
		}
		
		function _session_to_library()
		{
			// Pulls session data into the library.
			
			$this->id 				= $this->obj->session->userdata('id');
			$this->username			= $this->obj->session->userdata('username');
			$this->logged_in 		= $this->obj->session->userdata('logged_in');
			$this->auto_login		= $this->obj->session->userdata('auto_login');
			$this->lang 			= $this->obj->session->userdata('lang');
			$this->email			= $this->obj->session->userdata('email');
			//$this->id_samsat_lokasi	= $this->obj->session->userdata('id_samsat_lokasi');
			$this->g_id 			= $this->obj->session->userdata('g_id');
			$this->r_id 			= $this->obj->session->userdata('r_id');
			//echo("<pre>");
			//print_r($this->obj->session->userdata);	
		}
		
		function _start_session()
		{
			// $user is an object sent from function login();
			// Let's build an array of data to put in the session.
			
			$data = array(
						'id' 				=> $this->id,
						'username' 			=> $this->username,
						'email'				=> $this->email, 
						'logged_in'			=> $this->logged_in,
						'auto_login' 		=> $this->auto_login,
						'lang'				=> $this->lang,
						//'id_samsat_lokasi' 	=> $this->id_samsat_lokasi,
						'g_id' 				=> $this->g_id,
						'r_id' 				=> $this->r_id
					);
			
			$this->obj->session->set_userdata($data);
			
		}
		
		function _destroy_session()
		{
			$data = array(
						'id' 				=> 0,
						'username' 			=> '',
						'email' 			=> '',
						'logged_in'			=> false,
						'auto_login' 		=> false,
					//	'id_samsat_lokasi' 	=> '',
						'g_id' 				=> '',
						'r_id' 				=> ''
						//'last_activity_time' => ''
					);
					
			$this->obj->session->set_userdata($data);
			
			foreach ($data as $key => $value)
			{
				$this->$key = $value;
			}
		}
		
		function is_user_active($id){ // CEK apakah user sedang digunakan dan dalam kondisi aktif
			$q = $this->obj->db->query("SELECT * FROM users WHERE id='".$id."'");
			$r = $q->row();
		}
		
		function login($username, $password, $remember=false)
		{
			
			//destroy previous sesson
			$this->_destroy_session();
			//First check from the table
			$result = array();
			$result['username'] = $username;
			$result['password'] = $password;
			
			$result = $this->obj->plugin->apply_filters('user_auth', $result);
						
			if(isset($result['logged_in']) && $result['logged_in'] !== false)
			{
				// We found a user!
				// Let's save some data in their session/cookie/pocket whatever.
				
				$this->id 				= $result['id'];
				$this->username			= $result['username'];
				$this->logged_in 		= true;
				$this->lang 			= $this->obj->session->userdata('lang');
				$this->email			= $result['email'];
				$this->g_id				= $result['g_id'];
			//	$this->r_id 			= $result['role'];
				$this->_start_session();
				$this->obj->session->set_flashdata('notification', 'Login berhasil...');
				
				//if ever we need to do action on the registered user (as array)
				$this->obj->plugin->do_action('user_logged_in', $result);
				if($remember !== false)
				{
					$this->obj->load->library('user_persistence');
					$this->obj->user_persistence->remember();
				}
							
				return true;
			}
			else
			{
				$this->_destroy_session();
				
				if (isset($result['error_message']))
				{
					$this->obj->session->set_flashdata('notification', $result['error_message']);
				}
				else
				{
					$this->obj->session->set_flashdata('notification', 'Login gagal...');
				}
				
				return false;
			
			}
		}
		
	
		
		function logout()
		{
			// If the user is logging out destroy thier persistant data
			$this->obj->load->library('user_persistence');
			$this->obj->user_persistence->forget();
			
			//keep last_uri
			$this->update($this->username, array('isonline' => 0,'lastvisit'=>0));
			$last_uri = $this->obj->session->userdata("last_uri");
			$this->_destroy_session();
			$this->obj->session->set_userdata(array('last_uri' => $last_uri));
			$this->obj->session->set_flashdata('notification', 'Anda telah keluar dari sistem');
		}
		
		
		function set_expired(){
			$this->obj->session->set_flashdata('notification','Session anda telah habis, silahkan login kembali');
			$this->update($this->username, array('online' => 0));
			$this->_destroy_session();
		}
		
		function update($username, $data)
		{
			
			//encrypt password
			
			if (isset($data['password']))
			{
				$data['password'] = $this->_prep_password($data['password']);
			}
			
			$this->obj->db->where('username', $username);
			$this->obj->db->set($data);
			$this->obj->db->update($this->table);
			
		}
		
		function register($username, $password, $email)
		{
			// $user is an array...
			$data	= 	array(
							'username'	=> $username,
							'password'	=> $this->_prep_password($password),
							'email'		=> $email,
							'isactive'	=> 1,
							'registered'=> time()
						);
			
			$query = $this->obj->db->insert($this->table, $data);
			$this->obj->plugin->do_action('user_registered', $data);
			return $this->obj->db->insert_id();
		}

		function require_login()
		{
			$this->obj->load->library('user_persistence');
			
			if (!$this->logged_in)
			{
				//save _POST and uri
				$data = array(
				"last_post" => $_POST,
				"redirect" => $this->obj->uri->uri_string()
				);
				$this->obj->session->set_userdata($data);
				
				redirect("member/login");
			}
		}
		
		function get_user($where)
		{
			if (!is_array($where))
			{
				$this->obj->db->where('id', $where);
			}
			elseif(is_array($where))
			{
				foreach($where as $field => $val)
				{
					$this->obj->db->where($field, $val);
				}
			}
			
			$query = $this->obj->db->get('users');
			if ($query->num_rows() > 0 )
			{
				return $query->row_array();
			}
			else
			{
				return false;
			}
		}
		
		function get_users($where)
		{
			if (!is_array($where))
			{
				$where = array('id', $where);
			}
		
			$query = $this->obj->db->get_where('users', $where);
			if ($query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}

		function get_user_number($where = array(), $params = array())
		{
			
			$this->obj->db->select("COUNT(id) total");
			$this->obj->db->where($where);
			$this->obj->db->from("users");
			
			$query = $this->obj->db->get();
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				return $row['total'];
			}
			else
			{
				return 0;
			}
		}
		
		function delete_user($where, $limit = 1)
		{
			if (!is_array($where))
			{
				$where = array('id', $where);
			}
			$this->obj->db->where($where);
			$this->obj->db->limit($limit);
			$this->obj->db->delete("users");
		}
		
		function get_user_list($params = array())
		{
			$default_params = array
			(
				'order_by' => 'id',
				'limit' => null,
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
				$this->obj->db->like($params['like']);
			}
			$this->obj->db->order_by($params['order_by']);
			if (!is_null($params['where']))
			{
				$this->obj->db->where($params['where']);
			}
			$this->obj->db->limit($params['limit'], $params['start']);
		
			$query = $this->obj->db->get('users');
			if ($query->num_rows() > 0 )
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}

		function get_group_list($params = array())
		{
			$default_params = array
			(
				'order_by' => 'id',
				'limit' => null,
				'start' => null,
				'where' => array('g_owner' => $this->username),
				'like' => null,
			);
			
			foreach ($default_params as $key => $value)
			{
				$params[$key] = (isset($params[$key]))? $params[$key]: $default_params[$key];
			}
			if (!is_null($params['like']))
			{
				$this->obj->db->like($params['like']);
			}
			if (!is_null($params['where']))
			{
				$this->obj->db->where($params['where']);
			}
			$this->obj->db->or_where(array('g_id' => '0'));
			$this->obj->db->or_where(array('g_id' => '1'));
			$this->obj->db->order_by($params['order_by']);
			$this->obj->db->limit($params['limit'], $params['start']);
			
			$query = $this->obj->db->get('groups');

			if($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}
		
		function is_online($username)
		{
			$this->obj->db->where(array('username' => $username, 'lastvisit >' => time() - 600, 'online' => 1 ));
			$this->obj->db->order_by('lastvisit DESC');
			$query = $this->obj->db->get('users');
			if($query->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		function is_online_by_id($userid)
		{
			$this->obj->db->where(array('id' => $userid, 'lastvisit >' => time() - 600, 'online' => 1 ));
			$this->obj->db->order_by('lastvisit DESC');
			$query = $this->obj->db->get('users');
			echo($this->obj->db->last_query());
			if($query->num_rows() > 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function is_login_online(){
			if($this->is_login_expired()){
				return false;
			}else{
				$this->obj->session->set_flashdata('notification','Login sedang aktif, silahkan tunggu beberapa saat untuk melakukan login kembali.');
				$this->update($this->username, array('online' => 0));
				$this->_destroy_session();
				return true;
			}
		}
		
		public function is_user_online($id){
			$r = $this->obj->db->query("SELECT online FROM users WHERE id='".$id."'")->row();
			if($r->online == 1){
				return true;
			}else{
				return false;
			}
		}
		
		public function is_login_expired($id){
			$now = mktime();
			$r = $this->obj->db->query("SELECT lastvisit FROM users WHERE id='".$id."'")->row();
			if(@$r->lastvisit != 0){
				$diff = $now - $r->lastvisit;
			}
			if($diff > 6000){ // expired setelah 10 menit
				return true;
			}else{
				return false;
			}
		}
		
		
		function set_activity_time(){//$userid
			$this->obj->session->set_userdata(array("last_activity_time"=>time()));
		}
		
		function get_online()
		{
			$this->obj->db->where(array('lastvisit >' => time() - 600, 'online' => 1 ));
			$this->obj->db->order_by('lastvisit DESC');
			$query = $this->obj->db->get('users');
			if($query->num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
			
		}

		function _user_auth($result)
		{
			// this is the authentication from database
			//used only if no plugin were used before
			if(isset($result['logged_in'])) return $result;
			
			$result['logged_in'] = false;
			
			
			$this->obj->db->where('username', $result['username']);
			$this->obj->db->where('password', $this->_prep_password($result['password']));
			//$this->obj->db->where('status', 'aktif');
			$this->obj->db->where('isactive', 1);
			
			$query = $this->obj->db->get('users', 1);
			
			if ( $query->num_rows() == 1 )
			{
				
				$userdata = $query->row_array();
				$result['logged_in'] 		= true;
				$result['email'] 			= $userdata['email'];
				$result['id'] 				= $userdata['id'];
				$result['g_id'] 			= $userdata['groupid'];
				return $result;
			}
			else
			{
				$result['logged_in'] = false;
				return $result;
			}
		
		}
		
		
		function remember()
		{
			$this->obj->load->library('user_persistence');	
		}
		
	}	
