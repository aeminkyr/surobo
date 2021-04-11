<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller{

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){

		//echo $this->session->userdata("name");
		$this->load->model("Bitki_model");
		$userid = $this->session->userdata('userid');

		$bitkis = $this->Bitki_model->getBitkis($userid);

		$homep_data = array(
			"bitkis"=>$bitkis
		);

		$headerData = array(
			"pagetitle" => "Surobo Plant Watering",
			"stylesheet"=>"styles/onoffswitch",
			"script" =>"assets/js/js-fluid-meter.js"
		);

		$this->load->view("includes/header",$headerData);
		$this->load->view("includes/navbar");
		$this->load->view("user/home_page",$homep_data);
		$this->load->view("includes/footer");


	}

	public  function devices(){


		$headerData = array(
			"pagetitle" => "Surobo Plant Watering | Devices ",

		);

		$this->load->view("includes/header",$headerData);
		$this->load->view("includes/navbar");
		$this->load->view("user/devices_page");
		$this->load->view("includes/footer");


	}

	public function plants(){

		$headerData = array(
			"pagetitle" => "Surobo Plant Watering | Plants ",
			"stylesheet"=>"styles/onoffswitch",


		);

		$this->load->view("includes/header",$headerData);
		$this->load->view("includes/navbar");
		$this->load->view("user/plants_page");
		$this->load->view("includes/footer");



	}


}
