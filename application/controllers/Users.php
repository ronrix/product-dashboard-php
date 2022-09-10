<?php 
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Users extends CI_Controller {

		public function __construct(){
			parent::__construct();
		}

		public function index() {
			redirect("/login");
		}

		/*
			DOCU: this function will render the signin page

			OWNER: ronrix
		*/
		# this method will view the login page
		public function login() {
			$current_user_id = $this->session->userdata("user_id");
			if(!$current_user_id) {
				$this->load->view("users/signin");
				return;
			}
			redirect("/dashboard");
		}
		
		/*
			DOCU: this function will render the registration page

			OWNER: ronrix
		*/ 
		public function register() {
			$current_user_id = $this->session->userdata("user_id");
			if(!$current_user_id) {
				$this->load->view("users/register");
				return;
			}
			redirect("/dashboard");
		}

		/*
			DOCU: this function will process the form data of registration page
				and validates (inputs, and if email is already used) before adding the new user to the db

			OWNER: ronrix
		*/ 
		public function process_register() {
			$fields = $this->input->post(NULL, TRUE);

			$user_id = $this->User->register($fields);
			if($user_id == 1) {
				$this->session->set_userdata(array("user_id" => $user_id));
				redirect("/dashboard");
			}

			$this->session->set_flashdata("error", $user_id == -1 ? "<p>Email already exits!</p>" : validation_errors());
			redirect("/register");
		}
		
		/*
			DOCU: this function will handle the form of login page, 
				it will process the signin functionality, set the id to the session and 
				redirect to the dashboard on success

			OWNER: ronrix 
		*/ 
		public function process_signin() {
			$fields = $this->input->post(NULL, TRUE);

			$user_id = $this->User->login($fields);
			if($user_id == -1 || $user_id == 0) {
				$this->session->set_flashdata("error", $user_id == -1 ? "<p>Wrong email or password!</p>" : validation_errors());
				redirect("/login");	
			}

			$this->session->set_userdata(array("user_id" => $user_id));
			redirect("/dashboard");
		}

		# logout the user, simply unsetting the session user_id
		public function logoff(){
			$this->session->unset_userdata("user_id");
			redirect("/login");
		}
		
		/*
			DOCU: this function edits the user profile information, like email and name
				and set the response message on error and success

			OWNER: ronrix
		*/ 
		public function process_edit_information() {
			$fields = $this->input->post(NULL, TRUE);
			$result = $this->User->edit_information($fields);
			if($result == 1) {
				$this->session->set_flashdata("success", "Successfully edited information!");
			}
			else {
				$this->session->set_flashdata("error", $result == -1 ? "<p>Email already taken!</p>" : validation_errors());
			}
			redirect("users/edit");
		}


		/*
			DOCU: this function changes the user password
				and set the response message on error and success

			OWNER: ronrix
		*/ 
		public function process_edit_password() {
			$fields = $this->input->post(NULL, TRUE);
			$result = $this->User->edit_password($fields, $this->session->userdata("user_id"));
			if($result == 1) {
				$this->session->set_flashdata("asuccess", "Successfully changed password!");
			}
			else {
				$this->session->set_flashdata("aerror", $result == -1 ? "<p>Wrong password!</p>" : validation_errors());	
			}
			redirect("users/edit");
		}
	}

?>