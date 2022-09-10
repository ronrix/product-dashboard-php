<?php 
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Product extends CI_Model {

		# get all products for clients
		public function get_all_products() {
			$query = "SELECT products.* FROM products";
			return $this->db->query($query)->result_array();
		}

		# get all products for clients
		public function get_product_by_id($id) {
			$query = "SELECT products.*, DATE_FORMAT(products.created_at, '%M %D %Y') AS date FROM products WHERE id=?";
			return $this->db->query($query, $id)->row_array();
		}

		# get all the products by admin
		public function get_all_products_by_user_id($id) {
			$query = "SELECT products.* FROM products 
					INNER JOIN admins ON products.id = admins.product_id
					INNER JOIN users ON users.id = admins.user_id
					WHERE users.id = ?
					ORDER BY products.created_at DESC";
			return $this->db->query($query, $id)->result_array();
		}

		# validate the fields before insert to db
		public function validate($post) {
			$this->form_validation->set_rules("product_name", "Product Name", "required|max_length[255]");
			$this->form_validation->set_rules("description", "Description", "required");
			$this->form_validation->set_rules("price", "Price", "required|is_numeric");
			$this->form_validation->set_rules("inventory_count", "Inventory Count", "required|is_numeric");
			if(!$this->form_validation->run()) {
				return 0;
			}
			else {
				return 1;
			}
		}

		# check if product is already in the db
		public function check_duplicate_product($product_name) {
			return $this->db->query("SELECT * FROM products WHERE name=?", $product_name)->row_array();
		}

		/*
			DOCU: this function adds the new product to db
			OWNER: ronrix
		*/ 
		# add new product 
		public function add_new_product($fields) {
			if(!$this->validate($fields)) {
				return 0;
			}

			$query = "INSERT INTO products(name, price, description, inventory_count)
					VALUES(?, ?, ?, ?)";
			if(!$this->check_duplicate_product($fields["product_name"])) {
				$data = array(
					$this->security->xss_clean($fields["product_name"]),
					$this->security->xss_clean($fields["price"]),
					$this->security->xss_clean($fields["description"]),
					$this->security->xss_clean($fields["inventory_count"]),
				);
				$this->db->query($query, $data);
				$insert_id = $this->db->insert_id();
				$this->db->query("INSERT INTO admins(user_id, product_id) VALUES(?, ?)", [$this->security->xss_clean($this->session->userdata("user_id")), $insert_id]);
				return $this->db->affected_rows();
			}
			else {
				return -1;
			}
		}

		# check if product is already in the db
		public function edit_product_by_userid($fields) {

			if(!$this->validate($fields)) {
				return 0;
			}

			$query = "UPDATE products SET name=?, description=?, price=?, inventory_count=?, updated_at=NOW()
				WHERE id=?";
			$params = array(
				$this->security->xss_clean($fields["product_name"]),
				$this->security->xss_clean($fields["description"]),
				$this->security->xss_clean($fields["price"]),
				$this->security->xss_clean($fields["inventory_count"]),
				$this->security->xss_clean($fields["product_id"])
			);
			$this->db->query($query, $params);
			return $this->db->affected_rows();
		}

		# check if product is already in the db
		public function remove_product($id, $user_id) {
			$params = array($this->security->xss_clean($user_id), $this->security->xss_clean($id));
			$this->db->query("DELETE FROM products 
					WHERE id IN (SELECT product_id FROM admins WHERE admins.user_id=? AND admins.product_id=?)", 
					$params
			);
			return $this->db->affected_rows();
		}
	}

?>