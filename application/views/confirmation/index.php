<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Confirmation</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
</head>
<body>
	<div class="confirm">
		<h2>Are you sure you want to delete?</h2>		
		<a href="<?= base_url("dashboard") ?>" class="no">No</a>
		<a href="<?= base_url("products/remove/$id") ?>" class="yes">Yes</a>
	</div>
</body>
</html>