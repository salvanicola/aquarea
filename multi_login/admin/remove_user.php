<?php 
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration system - Remove User</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
</head>
<body>
	<div class="header">
		<h2>Admin - Remove User</h2>
	</div>
	
	<form method="post" action="remove_user.php">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="remove_btn"> Remove user</button>
			</br>
			<a class="back" href="home.php"> Back </a>
		</div>
	</form>
</body>
</html>