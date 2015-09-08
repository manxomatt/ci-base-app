<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @author: Randall Morgan
 * 
 */
 
 	
class Layout {

	var $obj = null;
	var $theme_dir = 'themes/';
	var $theme = '';
	var $view = '';
	
	function __construct()
	{
		if ( is_null($this->obj) )
		{
			$this->obj =& get_instance();
		}
		$this->theme = "LTE";// $this->obj->system->theme
	}

	
			
	function _login_action()
	{

		if (!$this->obj->user->logged_in && $this->obj->input->post('username') && $this->obj->input->post('password'))
		{
				$username = $this->obj->input->post('username');
				$password = $this->obj->input->post('password');
				
				if ($this->obj->user->login($username, $password))
				{
				
					if ($this->obj->input->post('redirect')) 
					{
						redirect($this->obj->input->post('redirect'));
					}					
				}
				else
				{
					if ($this->obj->input->post('redirect')) 
					{
						$this->obj->session->set_flashdata('redirect', $this->obj->input->post('redirect'));
					}
					
				}			
		}
	}
	
	
	/**
	 * Load view
	 * 
	 * The data is that passed in the "this->template[]"
	 * param.
	 */
	function load($data, $view)
	{
		$output = '';
		// Generate bread crumb trail
		$breadcrumb = array();
		
		if (empty($data['breadcrumb'])) $data['breadcrumb'] = array();
		
		if ($data['module'] != 'page')
		{
			$breadcrumb[] = array(
								'title' => ucwords($data['module']),
								'uri'	=> $data['module']
							);
		}
						
		$data['breadcrumb'] = array_merge($breadcrumb, $data['breadcrumb']);
		
		$data['view'] = $view;

		//load language for template
		//
		//$mofile = APPPATH . $this->theme_dir. $this->theme.'/locale/' . $this->obj->session->userdata('lang') . '.mo' ;
		$mofile = "";
		if ( file_exists($mofile)) 
		{
			$this->obj->locale->load_textdomain($mofile, $this->theme);
		}
		
		//$this->obj->locale->load_textdomain(APPPATH . 'locale/' . $this->obj->session->userdata('lang') . '.mo');
							
		// Handle admin module
		if ( (isset($data['admin']) && $data['admin']) || $data['module'] == 'admin')
		{
			$template_path = $this->theme_dir. 'admin/index';
		}
		else
		{
			$template_path =  $this->theme_dir. $this->theme. '/index';
		}
		
		$this->obj->load->view($template_path, $data);
		$output = $this->obj->output->get_output();
		
		//echo $output;
		/*
		if ($this->obj->system->debug == 1 && $this->obj->user->level['admin'] > 0)
		{
			$this->obj->output->enable_profiler(true);
		}
		
		$here = substr($this->obj->uri->uri_string(), 1);
		
		$this->obj->session->set_userdata(array('last_uri' => $here));
		*/
		$this->obj->output->set_output($output);
	}

	function get_themes()
	{
		$handle = opendir(APPPATH.'views/'.$this->theme_dir);

		if ($handle)
		{
			while ( false !== ($theme = readdir($handle)) )
			{
				// make sure we don't map silly dirs like .svn, or . or ..

				if (substr($theme, 0, 1) != "." && $theme != 'index.html' && $theme != 'admin')
				{
					$themes[$theme] = $theme;
				}
			}
		}
		
		return $themes;
	}

}
?>