<?php

class Bitki_model extends CI_Model
{

	public function getBitkis($userid)
	{

		$this->db
			->select("*")
			->from("devices")
			->where('userid', $userid);
		$query = $this->db->get();
		$result = $query->result();
		$lastmeasure = $this->getLastMeasure($userid);
		foreach ($result as $res) {
			$res->hum = $lastmeasure->humid;
			$res->temp = $lastmeasure->temp;
			$res->mois = $lastmeasure->mois;

		}

		if ($result) {
			return $result;
		} else {
			return;
		}


	}

	public function getLastMeasure($userid)
	{
		$this->db->select("measures.humidity as humid, measures.temperature as temp, measures.moisture as mois")
			->from("measures")
			->join("devices", "measures.deviceid = devices.id")
			->where("devices.userid", $userid)
			->limit("1")
			->order_by("measures.id","DESC");
		$query = $this->db->get();
		$row = $query->row();
		return $row;

	}


}
