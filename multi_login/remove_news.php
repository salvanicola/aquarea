<?php 
include('functions.php');
if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Sistema di Gestione - Rimuovi News</title>
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="screen and (min-device-width:600px)">
	<link rel="stylesheet" type="text/css" href="../css/multilogin.css" media="handheld, screen and (max-device-width:600px)"/>
	<link rel="stylesheet" type="text/css" href="../css/printmultilogin.css" media="print"/>
	<link rel="shortcut icon" href="../favicon.ico" />
</head>
<body>
	<div class="header">
		<h2>Rimuovi News</h2>
	</div>
	
	<form method="post" action="remove_news.php">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Titolo</label>
			<input type="text" name="title" value="<?php echo $title; ?>" required>
		</div>
		<div class="input-group">
			<label>Autore</label>
			<input type="text" name="author" value="<?php echo $author; ?>" required>
		</div>
		<div class="input-group">
			<label>Data</label>
			<input type="date" name="date" value="<?php echo $date; ?>" required>
		</div>
		<div class="input-group center-btn">
			<button type="submit" class="btn" name="remove_n_btn"> Rimuovi news</button>
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