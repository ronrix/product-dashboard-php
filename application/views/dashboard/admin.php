<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Product Dashboard - Admin</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/dashboard.css") ?>">
</head>
<body>
	<div class="container">
<?php
		$this->load->view("partials/header", $header);
?>
		<section>
			<h2>Manage Products</h2>
			<a href="<?= base_url("products/new") ?>" class="btn">Add new</a>
			<p class="error"><?= $this->session->flashdata("error") ?></p>
			<p class="success"><?= $this->session->flashdata("success") ?></p>
		</section>
		<table>
			<tr class="title">
				<td>Name</td>
				<td>Inventory Count</td>
				<td>Quantity Sold</td>
				<td>Action</td>
			</tr>
<?php	
	if (!empty($products)) {
		foreach($products as $product) { ?>
			<tr>
				<td><a href="<?= base_url("products/show/{$product["id"]}") ?>"><?= $product["name"] ?></a></td>
				<td><?= $product["inventory_count"]; ?></td>
				<td><?= $product["quantity_sold"]; ?></td>
				<td class="action"><a href="<?= base_url("products/edit/{$product["id"]}") ?>">Edit</a><a href="<?= base_url("products/confirm/{$product["id"]}") ?>">Remove</a></td>
			</tr>
<?php 	} 
	}
	else {
?>
		</table>
		<h3>No Products!</h3>
<?php } ?>

		</table>
	</div>
</body>
</html>