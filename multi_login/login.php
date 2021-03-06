<?php include('functions.php');
	if (isLoggedIn()) {
		$_SESSION['msg'] = "Hai già effettuato il login";
		if (isAdmin()) {
			header('location: admin/home.php');
		}
		else {
			header('location: index.php');
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sistema amministrativo Aquarea</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="stylesheet" type="text/css" href="../css/printmultilogin.css" media="print"/>
	<link rel="shortcut icon" href="../favicon.ico" />
</head>
<body>
	<div class="header">
		<h2>Login</h2>
	</div>
	<form method="post" action="login.php">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" required>
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password" required>
		</div>
		<div class="input-group">
			<button type="submit" class="btn" name="login_btn">Login</button>
		</div>
	</form>
</body>
</html>