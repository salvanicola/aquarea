<?php 
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "Devi effettuare il login";
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
	<title>Stistema di Gestione - Rimuovi Utente</title>
	<link rel="stylesheet" type="text/css" href="../../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="stylesheet" type="text/css" href="../../css/printmultilogin.css" media="print"/>
	<link rel="shortcut icon" href="../../favicon.ico" />
</head>
<body>
	<div class="header">
		<h2>Admin - Rimuovi Utente</h2>
	</div>
	
	<form method="post" action="remove_user.php">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" value="<?php echo $username; ?>" required>
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>" required>
		</div>
		<div class="input-group center-btn">
			<button type="submit" class="btn" name="remove_btn"> Rimuovi Utente</button>
			</br>
			</br>
			<a class="back" href="home.php"> Indietro </a>
		</div>
	</form>
</body>
</html>