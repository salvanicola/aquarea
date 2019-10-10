<?php 
include('functions.php');

if (!isLoggedIn()) {
	$_SESSION['msg'] = "Devi effettuare il login";
	header('location: login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Gestione Curriculum - Rimuovi richiesta</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="stylesheet" type="text/css" href="../css/printmultilogin.css" media="print"/>
	<link rel="shortcut icon" href="../favicon.ico" />
</head>
<body>
	<div class="header">
		<h2>Rimuovi richiesta</h2>
	</div>
	
	<form method="post" action="remove_request.php">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Nome</label>
			<input type="text" name="name" value="<?php echo $name; ?>" required>
		</div>
		<div class="input-group">
			<label>Cognome</label>
			<input type="text" name="surname" value="<?php echo $surname; ?>" required>
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>" required>
		</div>
		<div class="input-group center-btn">
			<button type="submit" class="btn" name="reject_btn"> Rimuovi richiesta</button>
			</br>
			</br>
			<?php
			if (isAdmin()) {
			?>
				<a class="back" href="admin/home.php"> Indietro </a>
			<?php
			}
			else {
			?>
				<a class="back" href="index.php"> Indietro </a>
			<?php
			}
			?>
		</div>
	</form>
</body>
</html>