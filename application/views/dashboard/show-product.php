<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Product Information</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/dashboard.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/reviews.css") ?>">
</head>
<body>
	<div class="container">
<?php
		$this->load->view("partials/header", $header);
?>
		<h2><?= $data["name"] ?></h2>
		<p>Added since: <?= $data["date"]; ?></p>
		<p>Description: <?= $data["description"]; ?></p>
		<p>Total Sold: <?= $data["quantity_sold"]; ?></p>
		<p>Number of available stocks: <?= $data["inventory_count"]; ?></p>
		
		<div class="error"><?= $this->session->flashdata("error"); ?></div>
		<h3>Leave a Review</h3>
		<form action="<?= base_url("reviews/leave_review") ?>" method="POST">
			<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash("hash") ?>">
			<input type="hidden" name="product_id" value="<?= $data["id"]; ?>">
			<textarea name="description"></textarea>
			<input type="submit" value="Post" class="btn">
		</form>

<?php
	if($reviews) {
		foreach($reviews as $review) { 
?>
		<h4>
			<?= ucwords($review["user"]["full_name"]); ?> wrote:
			<span><?= $review["created_at"] ?></span>
		</h4>
		<p><?= $review["description"]; ?></p>
		<div class="replies">
<?php 
			if($review["comments"]) {
				foreach($review["comments"] as $comment) { ?>
			<div class="reply">
				<h4>
					<?= ucwords($comment["full_name"]); ?> wrote:
					<span><?= $comment["created_at"]; ?></span>
				</h4>
				<p><?= $comment["comment"]; ?></p>
			</div>
<?php 			} 
			}
?>
			<form action="<?= base_url("reviews/add_comment") ?>" method="POST">
				<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash("hash") ?>">
				<input type="hidden" name="review_id" value="<?= $review["id"]; ?>">
				<input type="hidden" name="product_id" value="<?= $data["id"]; ?>">
				<textarea name="description" placeholder="write a message"></textarea>
				<input type="submit" value="Reply" class="btn">
			</form>
		</div>
<?php 	
		}
	}
?>
	</div>
</body>
</html>