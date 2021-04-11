<?php

class Api extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

	}

	public function devices($userid, $pass = "0")
	{
		$this->load->model("Api_model");

		if ($pass != "sifre1234")
			exit("Authorization Error!");
		$devices = $this->Api_model->getDevices($userid);
		/*
				foreach ($devices as $device){
					echo $device->mac;
				}
				*/
		exit(json_encode($devices));


	}


}
