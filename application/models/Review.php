<?php 
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Review extends CI_Model {

		# get datetime in string 
		# if created_at is within a day, return the hours like "1 hour ago"
		# else return the whole date
		private function get_specific_time($time) {
			$date = date_diff($time, new Datetime("now"));
			if($date && $date->days >= 7) {
				return date_format($time, "M d Y");
			}
			else if($date && $date->d == 1) {
				return "$date->d day ago";
			}
			else if($date && $date->d) {
				return "$date->d days ago";
			}
			else if($date && $date->h) {
				return "$date->h hours ago";	
			}
			else if($date && $date->s && $date->i == 0) {
				return "$date->s seconds ago";	
			}
			else if($date && $date->i == 1) {
				return "$date->i minute ago";	
			}
			else if($date && $date->i) {
				return "$date->i minutes ago";	
			}
		}

		# get review user by id
		private function get_review_user($user_id) {
			$query = "SELECT users.*, CONCAT(users.first_name, ' ', users.last_name) AS full_name FROM users WHERE id=?";
			return $this->db->query($query, $user_id)->row_array();
		}

		# get all replies by product id and commentors id
		private function get_replies_by_product_id_and_user_id($review_id) {
			$query = "SELECT users.*, comments.*, CONCAT(users.first_name, ' ', users.last_name) AS full_name 
				FROM comments
				INNER JOIN users ON comments.user_id = users.id 
				WHERE review_id=?
				ORDER BY comments.created_at ASC";
			$result = $this->db->query($query, $review_id)->result_array();
			# get specific time
			foreach($result as $key => $data) {
				$result[$key]["created_at"] = $this->get_specific_time(date_create($result[$key]["created_at"]));
			}
			return $result;
		}
		
		# get all the review of the product
		public function get_all_reviews_by_product_id($product_id) {
			$query = "SELECT reviews.* FROM reviews 
					INNER JOIN products ON reviews.product_id = products.id
					WHERE products.id = ?";

			$result = $this->db->query($query, $this->security->xss_clean($product_id))->result_array();

			# check if there's a review return it with its users, else return NULL
			if($result) {
				# get all the users and replies of the review
				foreach($result as $key => $data) {
					$result[$key]["user"] = $this->get_review_user($data["user_id"]);
					$result[$key]["comments"] = $this->get_replies_by_product_id_and_user_id($result[$key]["id"]);
					$result[$key]["created_at"] = $this->get_specific_time(date_create($result[$key]["created_at"]));
				}
				return $result;
			}
			else {
				return NULL;
			}
		}

		# validate the review first before inserting to the database
		private function validate($post) {
			$this->form_validation->set_rules("description", "Input", "required|trim");
			if(!$this->form_validation->run()) {
				return validation_errors();
			}
			else {
				return "valid";
			}
		}

		# insert review to db
		public function add_review($fields) {
			$is_valid = $this->validate($this->input->post());
			if($is_valid == "valid") {
				$query = "INSERT INTO reviews(user_id, product_id, description)
					VALUES(?, ?, ?)";
				$params = array(
					$this->session->userdata("user_id"),
					$this->security->xss_clean($fields["product_id"]),
					$this->security->xss_clean($fields["description"])
				);
				return $this->db->query($query, $params);
			}
			return 0;
		}

		# insert comment to db
		public function add_comment($fields) {
			$is_valid = $this->validate($this->input->post());
			if($is_valid == "valid") {
				$query = "INSERT INTO comments(review_id, user_id, comment)
					VALUES(?, ?, ?)";
				$params = array(
					$this->security->xss_clean($fields["review_id"]),
					$this->session->userdata("user_id"),
					$this->security->xss_clean($fields["description"]),
				);
				return $this->db->query($query, $params);
			}
			return 0;
		}
		
	}

?>