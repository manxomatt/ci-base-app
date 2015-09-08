<?php

class User_persistence {
	
	// Return values
	const AUTHORIZED = 0;
	const UNAUTHORIZED = 1;
	const INVALID = 2;
	
	// Cookie params
	const COOKIE_NAME = 'siteAuth';
	const COOKIE_FIELD_DELIMITER = '?';
	const RANDOM_STRING_LENGTH = 30;

	// DB Table name
	const PERSISTENCE_TABLE = 'ci_user_persistence';
	const USER_TABLE = 'ci_users';
	
	// CI Instance
	private $CI = null;
	
	// Class Params
	private $id = '';
	private $series = '';
	private $token = '';
	
	private $cookieId = '';
	private $cookieSeries = '';
	private $cookieToken = '';
	
	private $state = self::UNAUTHORIZED;	

	/**
	 * constructor.
	 */
	function __construct() 
	{
		$this->CI =& get_instance();
		
		if(!$this->CI->user->logged_in) 
		{
			// get cookie data, if it exists
			$this->_getPersistenceCookieData();
			
			// compare it to the DB if necessary
			$state = $this->getAuthorizationState();
			
			if ($state == self::AUTHORIZED) 
			{
				$this->logInViaCookie();
			} 
			elseif ($state == self::INVALID) 
			{
				// id & series match, but token is invalid. Invalidate all of their logins.
				$this->forget(true);
				redirect('member/login_error');
			}
		} 
		else 
		{
			// already logged in, don't do anything
		}
	}


    /**
     * Creates a series token for the current user, or increments the token
	 * if they've already got one.
     */
	function remember() 
	{
		
		$id = $this->CI->user->id;
		
		if (!$id) 
		{
			// cannot remember user without id!!!
			return;
		} 
		elseif(!$this->cookieId) 
		{
			// cookie not set, first visit
			// create new series and token
			$series = $this->generateFixedLengthRandomString(self::RANDOM_STRING_LENGTH);
			$token = $this->generateFixedLengthRandomString(self::RANDOM_STRING_LENGTH);
			$this->_save_login($id, $series, $token);
		} 
		else 
		{
			$token = $this->generateFixedLengthRandomString(self::RANDOM_STRING_LENGTH);
			$this->_save_login($id, $this->cookieSeries, $token);
		}
	}
	
	
	/**
     * Checks to see if the user has a valid cookie, and modifies the cookie if they do.
	 * @return AUTHORIZED if they do, UNAUTHORIZED if they do not, or INVALID if they
	 * have a cookie state which could indicate they were hacked. If the state is INVALID
	 * all persistence for this user will be removed to force login the next time they return.
     */
	function getAuthorizationState() 
	{
		if (!$this->cookieId) 
		{
			// no cookie
			return self::UNAUTHORIZED;
		}
		
		$this->CI->db->where('series', $this->cookieSeries);
		$query = $this->CI->db->get(self::PERSISTENCE_TABLE);
		if ($query->num_rows != 1) 
		{
			// cookie with nonexistent series
			return self::UNAUTHORIZED;
		}
		$row = $query->row();
		$this->token = $row->token;

		if ($this->token == $this->cookieToken) 
		{
			// id, series, and token match so they're good. Update the login cookie for next time.
			$token = $this->generateFixedLengthRandomString(self::RANDOM_STRING_LENGTH);
			$this->_save_login($this->cookieId, $this->cookieSeries, $token);
			return self::AUTHORIZED;
		}
		else 
		{
			return self::INVALID;
		}
	}

   /**
     * Forgets the user's login by erasing the cookie and clearing the database.
	 * If global = true all login data will be erased.
     */
	function forget($global = false) 
	{
		
		// load the cookie
		$this->_getPersistenceCookieData();
		
		// kill the cookie
		$cookie = array(
			'name'   => self::COOKIE_NAME,
			'value'  => '',
			'expire' => time()-86500,
			'domain' => '',
			'path'   => '/',
			'prefix' => '',
		);
		$this->CI = & get_instance();
		$this->CI->load->helper('cookie');
		set_cookie($cookie);
		
		// wipe it from the DB
		if($this->cookieId) 
		{
			$this->CI->db->where('user_id', $this->cookieId);
		
			if(!$global) 
			{
				// just delete this one
				$this->CI->db->where('series', $this->cookieSeries);
			}
			$this->CI->db->delete(self::PERSISTENCE_TABLE);
		} 
		else 
		{
		}
	}
	
	
	/**
	 * Retreieves the siteAuth Cookie data
	 */
	private function _getPersistenceCookieData() 
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('cookie');
		$cookie = get_cookie(self::COOKIE_NAME);
		if (!$cookie)
			return;

		$values = explode(self::COOKIE_FIELD_DELIMITER, $cookie);
		if (sizeof($values) != 3) 
		{
			// right name, wrong contents
		} 
		else 
		{
			$this->cookieId = $values[0];
			$this->cookieSeries = $values[1];
			$this->cookieToken = $values[2];
		}
	}

	/**
	 * Saves the cookie data to the cookie
	 * and database
	 */
	private function _save_login($id, $series, $token) {
		// save cookie
		$cookie = array(
			'name'   => self::COOKIE_NAME,
			'value'  => $id.self::COOKIE_FIELD_DELIMITER.$series.self::COOKIE_FIELD_DELIMITER.$token,
			'expire' => time()+86500,
			'domain' => '',
			'path'   => '/',
			'prefix' => '',
		);
		$this->CI = & get_instance();
		$this->CI->load->helper('cookie');
		set_cookie($cookie);
		
		// update series in db
		$data = array('token' => $token);
		$where = array('user_id'=>$id, 'series'=>$series);
		$this->CI->db->update(self::PERSISTENCE_TABLE, $data, $where); 
		if ($this->CI->db->affected_rows() == 0) 
		{
			// didn't have one already, add series to db
			$data = array(
			   'user_id' => $id,
			   'series' => $series,
			   'token' => $token
			);
			$this->CI->db->insert(self::PERSISTENCE_TABLE, $data); 
		}
	}


	/**
	 * Returns the Authorization state as a string
	 */
	function getStateString($state) {
		switch($state) {
			case self::AUTHORIZED:
				return "AUTHORIZED (".self::AUTHORIZED.")";
			case self::UNAUTHORIZED:
				return "UNAUTHORIZED (".self::UNAUTHORIZED.")";
			case self::INVALID:
				return "INVALID (".self::INVALID.")";
			default:
				return "UNKNOWN (".$state.")";
			
		}
	}


	/**
	 * Logs the user in via the siteAuth Cookie
	 */
	private function logInViaCookie() 
	{
		if ($this->cookieId != '') 
		{
			$data = array('id', $this->cookieId);
			echo 'loginviacookie';
			
			$this->CI->db->where('id', $this->cookieId);
			$query = $this->CI->db->get(self::USER_TABLE);

			//exit('loginviacookie2');
			if ($query->num_rows() == 1)
			{
				// User foound, log them in
				$row = $query->row();
				
				// verifies if a user has not been banned from the site
				// (i.e. user table, banned=1)
				if ($row->status == 'active')
				{
					$this->CI->user->id			= $row->id;
					$this->CI->user->username	= $row->username;
					$this->CI->user->logged_in 	= true;
					$this->CI->user->auto_login	= true;
					$this->CI->user->email 		= $row->email;
					
					$this->CI->user->_start_session();
				}
				else
				{
					// Set login error message and reidrect to member/login
					$this->CI->session->set_flashdata('notification', __("Persistent login failed", 'member'));
					redirect('member/login');
				}
			}
		}
	}
	
	
    /**
     * Generates a random string.
     *
     * @param integer $minLength
     * @param integer $maxLength
     * @param boolean $useUpper
     * @param boolean $useNumbers
     * @param boolean $useSpecial
     * @return $key random string
     */
    function generateRandomString($min, $max)
    {
		$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ1234567890";            
        $length = mt_rand($min, $max);
        if($min > $max)
		{
            $length = mt_rand($max, $min);
		}
		
        $key = '';
        for($i = 0; $i < $length; $i++)
		{
            $key .= $charset[(mt_rand(0, (strlen($charset)-1)))];
		}
        return $key;
    }

     /**
     * Generates a random string.
     *
     * @param integer $minLength
     * @param integer $maxLength
     * @param boolean $useUpper
     * @param boolean $useNumbers
     * @param boolean $useSpecial
     * @return $key random string
     */
    function generateFixedLengthRandomString($length)
    {
		return $this->generateRandomString($length, $length);
    }



}
?>