<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Register Page</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
</head>
<body>
	<div class="container">
<?php
		$this->load->view("partials/header", ["link"=> "login", "name"=>"Login"]);
?>
		<h2>Register</h2>
		<form action="/process_register" method="POST">
			<div class="error">
				<?= $this->session->flashdata("error"); ?>
			</div>
			<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash("hash") ?>">
			<label>
				Email address:
				<input type="text" name="email">
			</label>
			<label>
				First name:
				<input type="text" name="first_name">
			</label>
			<label>
				Last name:
				<input type="text" name="last_name">
			</label>
			<label>
				Password:
				<input type="password" name="password">
			</label>
			<label>
				Confirm password:
				<input type="password" name="confirm_password">
			</label>
			<input type="submit" value="Login" class="btn">
			<a href="login">Don't have an account? Register</a>
		</form>
	</div>
</body>
</html>