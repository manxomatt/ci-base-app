<?php

function customError($errno, $errstr) {
	echo(json_encode(array("success"=>false,"errors"=>array("reason"=>"<b>Error:</b> [".$errno."] ".$errstr))));
	exit;
}

function _prep_password($password)
{
	$obj =& get_instance();
	// Salt up the hash pipe
	// Encryption key as suffix.
	return sha1($password.$obj->config->item('encryption_key'));
	//return $this->obj->encryption->sha1($password.$this->obj->config->item('encryption_key'));
}

function is_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}