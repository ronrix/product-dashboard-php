<?php 
	defined("BASEPATH") OR exit("No direct script access allowed");

	class User extends CI_Model {

		# this validates the signin page form
		private function login_validation($post) {
			$this->form_validation->set_rules("email", "Email Address", "required|valid_email");
			$this->form_validation->set_rules("password", "Password", "required|min_length[8]");
			if(!$this->form_validation->run()) {
				return 0;
			}
			else {
				return 1;
			}
		}	

		/*
			DOCU: check if the credentials is stored in db.
				it returns the data selected and it returns "no user" as string if no user found
			
			OWNER: ronrix
		*/ 
		public function login($fields) {
			if(!$this->login_validation($fields)) {
				return 0;
			}

			$query = "SELECT * FROM users WHERE email_address=?";
			$user = $this->db->query($query, $this->security->xss_clean($fields["email"]))->row_array();
			$hashed_password = md5($this->security->xss_clean($fields["password"]));

			# user found
			if($user && $user["password"] == $hashed_password) {
				return $user["id"];
			}
			return -1;
		}

		/*
			DOCU: this function only checks if the user table is empty, 
				this function is used for register function, to make the first registered user an admin.

			OWNER: ronrix
		*/
		private function check_first_user() {
			return $this->db->query("SELECT * FROM users")->result_array();
		}

		/*
			DOCU: this function will insert the new user to db.
				and i return back the new user to be used.
				validates the inputs, if not empty and if email is not taken insert it to db
				else return (0, -1)

			OWNER: ronrix
		*/ 
		public function register($fields) {

			if(!$this->register_validation($fields)) {
				return 0;
			}
			else if($this->is_email_taken($fields["email"]))  {
				return -1;
			}

			$query = "INSERT INTO users(first_name, last_name, is_admin, email_address, password) 
					VALUES(?, ?, ?, ?, ?)";
			$is_admin = $this->check_first_user();
			$data = array(
				$this->security->xss_clean($fields["first_name"]),
				$this->security->xss_clean($fields["last_name"]),
				!$is_admin ? 1 : 0, 
				$this->security->xss_clean($fields["email"]),
				md5($this->security->xss_clean($fields["password"]))
			);
			$this->db->query($query, $data);
			return $this->db->insert_id();
		}

		/*
			DOCU: this function checks if the email is already taken
			OWNER: ronrix
		*/
		private function is_email_taken($email) {
			$query = "SELECT * FROM users WHERE email_address=?";
			$this->db->query($query, $this->security->xss_clean($email))->row_array();
			return $this->db->affected_rows();
		}

		# this validates the registration page form
		private function register_validation($post) {
			$this->form_validation->set_rules("email", "Email Address", "required|valid_email");
			$this->form_validation->set_rules("password", "Password", "required|min_length[8]");
			$this->form_validation->set_rules("first_name", "First Name", "required|alpha");
			$this->form_validation->set_rules("last_name", "Last Name", "required|alpha");
			$this->form_validation->set_rules("confirm_password", "Confirm Password", "required|matches[password]");
			if(!$this->form_validation->run()) {
				return 0;
			}
			else {
				return 1;
			}
		}		

		# validate profile information form with controller edit_information 
		private function edit_information_validation($post) {
			$this->form_validation->set_rules("email", "Email Address", "required|valid_email");
			$this->form_validation->set_rules("first_name", "First Name", "required|alpha");
			$this->form_validation->set_rules("last_name", "Last Name", "required|alpha");
			if(!$this->form_validation->run()) {
				return 0;
			}
			else {
				return 1;
			}
		}

		/*
			DOCU: this function edits user information, first_name, last_name and email_address on profile page
			OWNER: ronrix
		*/ 
		# update the user information
		public function edit_information($fields) {
			if(!$this->edit_information_validation($fields)) {
				return 0;
			}
			else if($this->is_email_taken($fields["email"])) {
				return -1;
			}

			$query = "UPDATE users SET email_address=?, first_name=?, last_name=? WHERE id=?";
			$params = array(
				$this->security->xss_clean($fields["email"]),
				$this->security->xss_clean($fields["first_name"]),
				$this->security->xss_clean($fields["last_name"]),
				$this->session->userdata("user_id")
			);

			$this->db->query($query, $params);
			return $this->db->affected_rows();
		}

		# validate profile information form with controller edit_information 
		private function edit_password_validation($post) {
			$this->form_validation->set_rules("old_password", "Old Password", "required");
			$this->form_validation->set_rules("password", "Password", "required");
			$this->form_validation->set_rules("confirm_password", "Confirm Password", "required|matches[password]");
			if(!$this->form_validation->run()) {
				return 0;
			}
			else {
				return 1;
			}
		}

		/*
			DOCU: this function checks if the password is correct
			OWNER: ronrix
		*/ 
		private function is_password_correct($id, $password) {
			$query = "SELECT * FROM users WHERE id=?";
			$user = $this->db->query($query, $id)->row_array();
			$hashed_password = md5($this->security->xss_clean($password));
			if($hashed_password == $user["password"]) {
				return 1;
			}
			return 0;
		}

		/*
			DOCU: this function change the user password on profile page
			OWNER: ronrix
		*/ 
		public function edit_password($fields, $id) {

			if(!$this->edit_password_validation($fields)) {
				return 0;
			}
			else if(!$this->is_password_correct($id, $fields["password"])) {
				return -1;
			}

			$query = "UPDATE users SET password=? WHERE id=?";
			$params = array(
				md5($this->security->xss_clean($fields["password"])),
				$this->session->userdata("user_id")
			);
			$this->db->query($query, $params);
			return $this->db->affected_rows();
		}	
		
		# check if user is an admin
		public function is_admin($id) {
			return $this->db->query("SELECT is_admin FROM users WHERE id=?", $id)->row_array();
		}
	}

?>