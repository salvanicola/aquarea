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
	<title>Registration system - Add User</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
</head>
<body>
	<div class="header">
		<h2>Admin - Add User</h2>
	</div>
	
	<form method="post" action="create_user.php">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" value="<?php echo $username; ?>" required>
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>" required>
		</div>
		<div class="input-group">
			<label>User type</label>
			<select name="user_type" id="user_type" >
				<option value="admin" class="type">Admin</option>
				<option value="mod" class="type">Mod</option>
			</select>
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password_1" required>
		</div>
		<div class="input-group">
			<label>Confirm password</label>
			<input type="password" name="password_2" required>
		</div>
		<div class="input-group center-btn">
			<button type="submit" class="btn" name="register_btn"> Create user</button>
			</br>
			</br>
			<a class="back" href="home.php"> Back </a>
		</div>
	</form>
</body>
</html>