<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Profile</title>
	<link rel="stylesheet" href="<?= base_url("assets/css/style.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/wall.css") ?>">
	<link rel="stylesheet" href="<?= base_url("assets/css/profile.css") ?>">
</head>
<body>
	<div class="container">
<?php
		$this->load->view("partials/header", $header);
?>
		<h2>Edit Profile</h2>
		<div class="error">
			<?= $this->session->flashdata("error"); ?>
		</div>
		<p class="success"><?= $this->session->flashdata("success"); ?></p>
		<div class="error">
			<?= $this->session->flashdata("aerror"); ?>
		</div>
		<p class="success"><?= $this->session->flashdata("asuccess"); ?></p>
		<form action="<?= base_url("users/process_edit_information") ?>" method="POST">
			<p>Edit Information</p>
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
			<input type="submit" value="Save" class="btn">
		</form><!--
	--><form action="<?= base_url("users/process_edit_password") ?>" method="POST">
			<p>Change Password</p>
			<input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash("hash") ?>">
			<label>
				Old password:
				<input type="password" name="old_password">
			</label>
			<label>
				Password:
				<input type="password" name="password">
			</label>
			<label>
				Confirm password:
				<input type="password" name="confirm_password">
			</label>
			<input type="submit" value="Save" class="btn">
		</form>
	</div>
</body>
</html>