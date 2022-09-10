<?php 
	defined("BASEPATH") OR exit("No direct script access allowed");

	class Reviews extends CI_Controller {

		public function __construct(){
			parent::__construct();
			$this->csrf = array(
				"name" => $this->security->get_csrf_token_name(),
				"hash" => $this->security->get_csrf_hash("hash"),
			);
		}

		# handle leave review form, if error we just put a message error before we redirect back
		public function leave_review() {
			$fields = $this->input->post(NULL, TRUE);
			if(!$this->Review->add_review($fields)) {
				$this->session->set_flashdata("error", validation_errors());
			}
			redirect("products/show/{$fields["product_id"]}");
		}

		# handle comment form on review
		public function add_comment() {
			$fields = $this->input->post(NULL, TRUE);
			if(!$this->Review->add_comment($fields)) {
				$this->session->set_flashdata("error", validation_errors());
			}
			redirect("products/show/{$fields["product_id"]}");
		}
		
	}

?>