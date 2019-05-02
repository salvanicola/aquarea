<?php 
include('functions.php');

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Curriculum system - Reject Request</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style>
		.header {
			background: #003366;
		}
		button[name=register_btn] {
			background: #003366;
		}
	</style>
</head>
<body>
	<div class="header">
		<h2>Reject Request</h2>
	</div>
	
	<form method="post" action="remove_request.php">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $name; ?>">
		</div>
		<div class="input-group">
			<label>Cognome</label>
			<input type="text" name="surname" value="<?php echo $surname; ?>">
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="reject_btn"> Reject request</button>
			<a style="margin-left:2%" href="javascript:history.back()"> Back </a>
		</div>
	</form>
</body>
</html>