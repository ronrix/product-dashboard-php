<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Product Dashboard - User</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/dashboard.css") ?>">
</head>
<body>
	<div class="container">
<?php
		$this->load->view("partials/header", $header);
?>
		<h2>All Products</h2>
		<table>
			<tr class="title">
				<td>ID</td>
				<td>Name</td>
				<td>Inventory Count</td>
				<td>Quantity Sold</td>
			</tr>
<?php 
	foreach($products as $product) {?>
			<tr class="">
				<td><?= $product["id"] ?></td>
				<td><a href="<?= base_url("products/show/{$product["id"]}") ?>"><?= $product["name"] ?></a></td>
				<td><?= $product["inventory_count"] ?></td>
				<td><?= $product["quantity_sold"] ?></td>
			</tr>
<?php } ?>
		</table>
	</div>
</body>
</html>