<?php

class Api_model extends CI_Model
{

	public function getDevices($userid)
	{
		//$email,$pass
		$this->db->select('id,mac,onlinedate,online');
		$this->db->from('devices');
		$this->db->where('userid', $userid);


		if ($query = $this->db->get()) {
			return $query->result();
		} else {
			return false;
		}
	}


}
