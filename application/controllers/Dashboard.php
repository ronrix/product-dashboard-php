<?php 
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Dashboard extends CI_Controller {

		public function __construct(){
			parent::__construct();
			$this->header_data = array(
				"link" => "logoff",
				"name" => "Log off",
				"link_a" => "dashboard",
				"a_name" => "Dashboard",
				"link_b" => "users/edit",
				"b_name" => "Profile",
			);
			# checks if there is a user_id on the session if no, redirect to login page
			$current_user_id = $this->session->userdata("user_id");
			if(!$current_user_id) {
				redirect("/login");
			}
		}

		# this method will view normal user dashboard
		# and check if the user is an admin and redirect to the admin page
		public function index() {
			$user = $this->User->is_admin($this->session->userdata("user_id"));
			if($user["is_admin"] == 1) {
				redirect("/dashboard/admin");
				die();
			}

			$products = $this->Product->get_all_products();
			$view_data = array("products" => $products, "header" => $this->header_data);
			$this->load->view("dashboard/index", $view_data);
		}

		# this method view the admin dashboard
		# if the user is not an admin redirect to the normal page
		public function admin() {			
			$user = $this->User->is_admin($this->session->userdata("user_id"));
			if($user["is_admin"] != 1) {
				redirect("/dashboard");
				die();
			}
			$products = $this->Product->get_all_products_by_user_id($this->session->userdata("user_id"));
			$view_data = array(
				"products" => $products,
				"header" => $this->header_data
			);
			$this->load->view("dashboard/admin", $view_data);
		}

		# this method view profile page that lets the user to edit the information and password
		public function profile_edit() {
			$view_data = array(
				"header" => $this->header_data
			);
			$current_user_id = $this->session->userdata("user_id");
			if(!$current_user_id) {
				redirect("/login");
			}
			else {
				$this->load->view("dashboard/profile", $view_data);
			}
		}

		# this method removes or delete the product by id
		public function show($id = NULL) {
			$result = $this->Product->get_product_by_id($id);
			if($id == NULL || !$result) {
				redirect("/dashboard");
			}
			$reviews = $this->Review->get_all_reviews_by_product_id($id);

			$view_data = array(
				"data" => $result,
				"reviews" => $reviews,
				"header" => $this->header_data
			);
			$this->load->view("dashboard/show-product", $view_data);
		}
	}

?>