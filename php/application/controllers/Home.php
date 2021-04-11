<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model("User_model");



	}

	public function index()
	{
		//login page

		if($this->session->userdata("logged_in")){
			redirect("main");
		}

		$headerData = array(
			"pagetitle" => "Surobo Watering",
			"stylesheet" => "styles/loginpagecss"
		);

		$this->load->view("includes/header", $headerData);
		$this->load->view("login_page");
		$this->load->view("includes/footer");


	}

	public function login()
	{


		$email = $this->input->post("email");
		$password = md5($this->input->post("password"));


		$login = $this->User_model->login_user($email, $password);

		if ($login) {
			$userdata = array(
				'userid'=>$login->id,
				'name' => $login->name,
				'email' => $login->email,
				'logged_in' => TRUE
			);

			$this->session->set_userdata($userdata);
			redirect("Main");
		} else {
			$this->session->set_flashdata("info", 'Hata. Şifre yada mail adresiniz yanlış!');
			redirect("home");
		}


	}

	public function register()
	{

		$email = $this->input->get('email');
		$password = $this->input->get('password');

		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			exit("Mail is not valid!");


		$register = $this->User_model->register($email, $password);
		if ($register) {
			echo "kayıt ok ";
		}


	}


	public  function logout(){
		$this->session->sess_destroy();
		redirect("home");

	}
}
