<?php

class User_model extends CI_Model
{

	public function login_user($email, $password)
	{
		//$email,$pass
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('password', $password);

		if ($query = $this->db->get()) {
			return $query->row();
		} else {
			return false;
		}


	}

	public function register($email, $password)
	{
		$userInfo = array(
			"email" => $email,
			"password" => md5($password)
		);
		return $this->db->insert("users", $userInfo);


	}

}
