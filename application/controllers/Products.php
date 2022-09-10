<?php 
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Products extends CI_Controller {

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

			# prevent other user from accessing this controller, only the admin can access this
			$user = $this->User->is_admin($this->session->userdata("user_id"));
			if($user["is_admin"] != 1) {
				redirect("/dashboard");
			}
		}

		# this method view the adding new product form PAGE
		public function new() {
			$view_data = array(
				"header" => $this->header_data
			);
			$this->load->view("dashboard/add-new", $view_data);
		}

		# this method, view the edit product form PAGE
		public function edit($id = NULL) {
			if($id == NULL) {
				redirect("/dashboard");
			}
			$view_data = array(
				"id" => $id,
				"header" => $this->header_data
			);
			$this->load->view("dashboard/edit", $view_data);
		}

		# this method, process the edit form for product
		public function process_edit() {
			$fields = $this->input->post(NULL, TRUE);
			$result = $this->Product->edit_product_by_userid($fields);
			if($result) {
				$this->session->set_flashdata("success", "Successfully edited the product!");
				redirect("/dashboard/admin");
			}
			else {
				$this->session->set_flashdata("error", validation_errors());
				redirect("/products/edit/{$fields["product_id"]}");	
			}
		}
			
		# this method process the form for creating a product
		# not allowing the user to add duplicate products for validation
		public function process_create() {
			$fields = $this->input->post(NULL, TRUE);
			$result = $this->Product->add_new_product($fields);
			if($result == -1) {
				$this->session->set_flashdata("duplicates", "Product already exists!");
			}
			else if($result == 1){
				$this->session->set_flashdata("success", "Successfully added the product!");
			}
			else {
				$this->session->set_flashdata("errors", validation_errors());
			}
			redirect("/products/new");
		}

		# this method load the confirmation before deleting the product
		public function confirm($id = NULL) {
			if($id == NULL) {
				redirect("/dashboard/index");
			}
			$this->load->view("confirmation/index", ["id" => $id]);
		}

		# this method removes or delete the product by id
		public function remove($id = NULL) {
			if($id == NULL) {
				redirect("/dashboard/admin");
			}
			$result = $this->Product->remove_product($id, $this->session->userdata("user_id"));
			if($result) {
				$this->session->set_flashdata("success", "Successfully deleted a product!");
			}
			else {
				$this->session->set_flashdata("error", "No product with that id");	
			}
			redirect("/dashboard/admin");	
		}
	}

?>