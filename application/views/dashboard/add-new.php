<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New Product - Admin</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/dashboard.css") ?>">
</head>
<body>
	<div class="container">
<?php
		$this->load->view("partials/header", $header);
?>
		<section>
			<h2>Add a new Product</h2>
			<a href="<?= base_url("dashboard") ?>" class="btn">Return to dashboard</a>
		</section>
		<form action="<?= base_url("products/process_create") ?>" method="POST">
			<div class="error"><?= $this->session->flashdata("errors"); ?></div>
			<p class="error"><?= $this->session->flashdata("duplicates"); ?></p>
			<p class="success"><?= $this->session->flashdata("success"); ?></p>
			<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash("hash") ?>">
			<label>
				Name:
				<input type="text" name="product_name">
			</label>
			<label>
				Description:
				<textarea name="description"></textarea>
			</label>
			<label>
				Price:
				<input type="text" name="price">
			</label>
			<label>
				Inventory Count
				<input type="number" name="inventory_count" min="1" value="1" class="inventory">
			</label>
			<input type="submit" value="Create" class="btn">
		</form>
	</div>
</body>
</html>