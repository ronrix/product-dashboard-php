<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login Page</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
</head>
<body>
	<div class="container">
<?php 
		$this->load->view("partials/header", ["link"=> "register", "name"=>"Register"]); 
?>
		<h2>Login</h2>
		<form action="/process_signin" method="POST">
			<div class="error">
					<?= $this->session->flashdata("error"); ?>
			</div>
			<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash("hash") ?>">
			<label>
				Email address:
				<input type="text" name="email">
			</label>
			<label>
				Password:
				<input type="password" name="password">
			</label>
			<input type="submit" value="Login" class="btn">
			<a href="register">Don't have an account? Register</a>
		</form>
	</div>
</body>
</html>